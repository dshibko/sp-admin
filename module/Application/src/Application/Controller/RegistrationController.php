<?php

namespace Application\Controller;

use Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Manager\ExceptionManager;
use Application\Helper\Avatar;
use Application\Manager\RegistrationManager;
use Application\Manager\ApplicationManager;
use Application\Form\SetUpForm;
use Application\Manager\FacebookManager;
use \Application\Manager\AuthenticationManager;
use \Zend\Authentication\Result;
use Application\Model\Helpers\MessagesConstants;

class RegistrationController extends AbstractActionController
{
    const SETUP_PAGE_ROUTE = 'setup';

    //TODO set permission only for guests
    public function indexAction()
    {
        try {
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            //if member - redirect to dashboard
            if (!empty($user)) {
                return $this->redirect()->toRoute('home');
            }

            $form = $this->getServiceLocator()->get('Application\Form\RegistrationForm');
            $form->get('submit')->setValue('Register');
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post)->prepareData();
                if ($form->isValid()) {
                    $avatar = new Avatar($form->get('avatar'), $this->getServiceLocator());
                    $avatar->setDefaultAvatar(!empty($post['default_avatar']) ? $post['default_avatar'] : null);
                    if ($avatar->validate()) {
                        $data = $form->getData();
                        $avatar->save()->resize();
                        if ($avatar->getUseDefault()) {
                            $data['default_avatar_id'] = $avatar->getDefaultAvatar();
                            unset($data['avatar']);
                        } else {
                            $data['avatar'] = $avatar->getPath();
                        }
                        RegistrationManager::getInstance($this->getServiceLocator())->register($data);
                        //Login registered user
                        AuthenticationManager::getInstance($this->getServiceLocator())->signIn($data['email'], false);
                        //TODO send welcome email
                        return $this->redirect()->toRoute(self::SETUP_PAGE_ROUTE);
                    } else {
                        $form->setMessages(array('avatar' => $avatar->getErrorMessages()));
                    }
                }
            }

            return new ViewModel(array(
                'form' => $form,
                'default_avatar' => $this->getRequest()->getPost('default_avatar', null),
                'flashMessages' => $this->flashMessenger()->getMessages(),
            ));
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('registration');
        }
    }

    public function setUpAction()
    {

        try {
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();

            //if guest - redirect to login page
            if (empty($user)) {
                return $this->redirect()->toRoute('login');
            }
            //if active user - redirect to dashboard
            if ($user->getActive()) {
                return $this->redirect()->toRoute('home');
            }

            $form = $this->getServiceLocator()->get('Application\Form\SetUpForm');
            $request = $this->getRequest();

            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $data = $form->getData();
                    RegistrationManager::getInstance($this->getServiceLocator())->setUp($data);
                    return $this->redirect()->toRoute('home');
                }
            }

            return new ViewModel(array(
                'form' => $form,
                'flashMessages' => $this->flashMessenger()->getMessages()
            ));
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::SETUP_PAGE_ROUTE);
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
            //Sign In facebook user
            AuthenticationManager::getInstance($this->getServiceLocator())->signIn($facebookUser->getEmail(), false);
            $route = self::SETUP_PAGE_ROUTE;
            if ($facebookUser->getActive()) {
                $route = 'home';
            }
            return $this->redirect()->toRoute($route);
        } /*catch(\FacebookApiException $e){
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('login');
        }*/ catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('registration');
        }

    }

}
