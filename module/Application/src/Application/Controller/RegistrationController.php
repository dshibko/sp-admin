<?php

namespace Application\Controller;

use Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Manager\ExceptionManager;
use Application\Manager\RegistrationManager;
use Application\Manager\ApplicationManager;
use Application\Form\SetUpForm;
use Application\Manager\FacebookManager;
use \Application\Manager\AuthenticationManager;
use \Zend\Authentication\Result;
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

    //TODO set permission only for guests
    public function indexAction()
    {
        try {
            $form = $this->getServiceLocator()->get('Application\Form\RegistrationForm');
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            //if member - redirect to dashboard
            if (!empty($user)) {
                return $this->redirect()->toRoute(self::HOME_PAGE_ROUTE);
            }
            $form->get('submit')->setValue('Register');
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post)->prepareData();
                if ($form->isValid()) {
                    $data = $form->getData();
                    $defaultAvatarId = !empty($post['default_avatar'])  ? $post['default_avatar'] : null;
                    $data['avatar'] = UserManager::getInstance($this->getServiceLocator())->getUserAvatar($form, $defaultAvatarId);

                    if (!empty($data['avatar'])){
                        RegistrationManager::getInstance($this->getServiceLocator())->register($data);
                        //Login registered user
                        AuthenticationManager::getInstance($this->getServiceLocator())->signIn($data['email']);
                        //TODO send welcome email
                        return $this->redirect()->toRoute(self::SETUP_PAGE_ROUTE);
                    }
                }else{
                    foreach ($form->getMessages() as $el => $messages) {
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " . (is_array($messages) ? implode(", ", $messages) : $messages) . "<br />");
                    }
                }
            }
            $viewModel = new ViewModel(array(
                'form' => $form,
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
            $country = $userManager->getUserGeoIpCountry();
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
                }else{
                    foreach ($form->getMessages() as $el => $messages) {
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " . (is_array($messages) ? implode(", ", $messages) : $messages) . "<br />");
                    }
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

    //TODO move to auth controller
    public function facebookLoginAction()
    {
       try {
            $code = $this->getRequest()->getQuery('code');
            if (empty($code)) {
                throw new \Exception(MessagesConstants::FAILED_CONNECTION_TO_FACEBOOK);
            }
            $facebook = $this->getServiceLocator()->get('facebook');
            if (!$facebook->getUser()) {
                throw new \Exception(MessagesConstants::FAILED_CONNECTION_TO_FACEBOOK);
            }

            $user = $facebook->api('/me');
            if (empty($user['id'])) {
                throw new \Exception(MessagesConstants::FAILED_CONNECTION_TO_FACEBOOK);
            }

            $facebookUser = FacebookManager::getInstance($this->getServiceLocator())->setFacebookAPI($facebook)->process($user);

            if (empty($facebookUser)) {
                throw new \Exception(MessagesConstants::FAILED_UPDATING_DATA_FROM_FACEBOOK);
            }

            $currentUser = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            $route = ($facebookUser->getIsActive()) ? self::HOME_PAGE_ROUTE : self::SETUP_PAGE_ROUTE;
            if (empty($currentUser)){
                //Sign In facebook user
                AuthenticationManager::getInstance($this->getServiceLocator())->signIn($facebookUser->getEmail());
            }else{ // User connect account to facebook
                $route = self::USER_SETTINGS_PAGE_ROUTE;
                $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_CONNECT_TO_FACEBOOK_ACCOUNT);
            }
            return $this->redirect()->toRoute($route);
        } /*catch(\FacebookApiException $e){
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::LOGIN_PAGE_ROUTE);
        }*/ catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::REGISTRATION_PAGE_ROUTE);
        }

    }

}
