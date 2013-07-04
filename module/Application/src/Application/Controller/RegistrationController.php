<?php

namespace Application\Controller;

use Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Manager\ExceptionManager;
use Application\Manager\RegistrationManager;
use Application\Manager\ApplicationManager;
use Application\Manager\FacebookManager;
use \Application\Manager\AuthenticationManager;
use Application\Model\Helpers\MessagesConstants;
use Application\Manager\UserManager;

class RegistrationController extends AbstractActionController
{
    const SETUP_PAGE_ROUTE = 'setup';
    const USER_SETTINGS_PAGE_ROUTE = 'user-settings';
    const HOME_PAGE_ROUTE = 'home';
    const LOGIN_PAGE_ROUTE = 'login';
    const PREDICT_PAGE_ROUTE = 'predict';
    const REGISTRATION_PAGE_ROUTE = 'registration';

    public function indexAction()
    {
        try {
            $terms = null;
            $form = $this->getServiceLocator()->get('Application\Form\RegistrationForm');
            $fieldsets = $form->getFieldsets();
            if (!empty($fieldsets['terms'])) {
                $terms = $fieldsets['terms'];
            }
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            $registrationManager = RegistrationManager::getInstance($this->getServiceLocator());
            //if member - redirect to dashboard
            if (!empty($user)) {
                return $this->redirect()->toRoute(self::PREDICT_PAGE_ROUTE);
            }
            $form->get('submit')->setValue('Register');
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $post['email'] = isset($post['email']) ? strtolower($post['email']) : null;
                $post['confirm_email'] = isset($post['confirm_email']) ? strtolower($post['confirm_email']) : null;

                $form->setData($post)->prepareData();
                if ($form->isValid()) {
                    $data = $form->getData();
                    $defaultAvatarId = !empty($post['default_avatar']) ? $post['default_avatar'] : null;
                    $data['avatar'] = UserManager::getInstance($this->getServiceLocator())->getUserAvatar($form, $defaultAvatarId);

                    if (!empty($data['avatar'])) {
                        $registrationManager->register($data);
                        //Login registered user
                        AuthenticationManager::getInstance($this->getServiceLocator())->signIn($data['email']);
                        return $this->redirect()->toRoute(self::SETUP_PAGE_ROUTE);
                    }
                } else {
                    $this->formErrors($form, $this);
                }
            }
            $viewModel = new ViewModel(array(
                'form' => $form,
                'terms' => $terms,
                'default_avatar' => $this->getRequest()->getPost('default_avatar', null)
            ));

            $viewModel->setTerminal(true);
            return $viewModel;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

    public function setUpAction()
    {

        try {
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();

            //if guest - redirect to login page
            if (empty($user)) {
                return $this->redirect()->toRoute(self::LOGIN_PAGE_ROUTE);
            }
            //if active user - redirect to dashboard
            if ($user->getIsActive()) {
                return $this->redirect()->toRoute(self::PREDICT_PAGE_ROUTE);
            }
            $form = $this->getServiceLocator()->get('Application\Form\SetUpForm');
            $userManager = UserManager::getInstance($this->getServiceLocator());
            $country = $user->getCountry();
            $language = $userManager->getUserLanguage();
            $form->get('region')->setValue($country->getId());
            $form->get('language')->setValue($language->getId());
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $data = $form->getData();
                    RegistrationManager::getInstance($this->getServiceLocator())->setUp($data);
                    return $this->redirect()->toRoute(self::PREDICT_PAGE_ROUTE);
                } else {
                    $this->formErrors($form, $this);
                }
            }

            $viewModel = new ViewModel(array(
                'form' => $form
            ));

            $viewModel->setTerminal(true);
            return $viewModel;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

    public function facebookLoginAction()
    {
        $userManager = UserManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $facebookManager = FacebookManager::getInstance($this->getServiceLocator());
        $authenticationManager = AuthenticationManager::getInstance($this->getServiceLocator());

        $route = self::REGISTRATION_PAGE_ROUTE;

        try {
            $code = $this->getRequest()->getQuery('code');
            if (empty($code)) {
                throw new \Exception($this->getTranslator()->translate(MessagesConstants::FAILED_CONNECTION_TO_FACEBOOK));
            }
            $facebook = $this->getServiceLocator()->get('facebook');
            if (!$facebook->getUser()) {
                throw new \Exception($this->getTranslator()->translate(MessagesConstants::FAILED_CONNECTION_TO_FACEBOOK));
            }

            $fUser = $facebook->api('/me');
            if (empty($fUser['id'])) {
                throw new \Exception($this->getTranslator()->translate(MessagesConstants::FAILED_CONNECTION_TO_FACEBOOK));
            }

            $facebookManager->setFacebookAPI($facebook);
            $facebookManager->getFacebookAPI()->setExtendedAccessToken();
            $facebookUserData = $facebookManager->getFacebookUserData($fUser);
            $facebookUserData['facebook_access_token'] = $facebookManager->getFacebookAPI()->getAccessToken();
            $facebookUserData['password'] = $applicationManager->encryptPassword(uniqid());
            $facebookUser = null;

            $currentUser = $applicationManager->getCurrentUser();

            if (!is_null($currentUser)){
                $route = self::USER_SETTINGS_PAGE_ROUTE;
                $oldEmail = $currentUser->getEmail();
                $userByIdentity = $userManager->getUserByIdentity($facebookUserData['email']);
                if (!is_null($userByIdentity) && $userByIdentity->getId() != $currentUser->getId()){
                    throw new \Exception($this->getTranslator()->translate(MessagesConstants::ERROR_CANNOT_CONNECT_TO_FACEBOOK_ACCOUNT));
                }

                $facebookUser = $facebookManager->updateUser($currentUser, $facebookUserData);
                $authenticationManager->changeIdentity($oldEmail, $facebookUser->getEmail());
                $this->flashMessenger()->addSuccessMessage($this->getTranslator()->translate(MessagesConstants::SUCCESS_CONNECT_TO_FACEBOOK_ACCOUNT));
            }else{
                $route = self::PREDICT_PAGE_ROUTE;
                $userByFacebookId = $userManager->getUserByFacebookId($facebookUserData['facebook_id']);
                if (!is_null($userByFacebookId)){
                   $facebookUser = $facebookManager->updateUser($userByFacebookId, $facebookUserData);
                }else{
                    $userByIdentity = $userManager->getUserByIdentity($facebookUserData['email']);
                    if (!is_null($userByIdentity)){
                        $facebookUser = $facebookManager->updateUser($userByIdentity, $facebookUserData);
                    }else{
                        $facebookUser = $facebookManager->registerUser($facebookUserData);
                    }
                }
                $authenticationManager->signIn($facebookUser->getEmail());
            }
            if (empty($facebookUser)) {
                throw new \Exception($this->getTranslator()->translate(MessagesConstants::FAILED_UPDATING_DATA_FROM_FACEBOOK));
            }
            return $this->redirect()->toRoute($route);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute($route);
        }

    }

}
