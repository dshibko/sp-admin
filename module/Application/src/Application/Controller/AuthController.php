<?php

namespace Application\Controller;

use \Zend\Authentication\Result;
use \Application\Manager\ExceptionManager;
use \Application\Manager\ApplicationManager;
use \Zend\Form\Annotation\AnnotationBuilder;
use \Application\Model\Entities\User;
use \Application\Form\LoginForm;
use \Neoco\Controller\AbstractActionController;
use \Application\Manager\AuthenticationManager;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use \Application\Model\Helpers\MessagesConstants;
use Application\Form\ForgotPasswordForm;
use Application\Form\ResetPasswordForm;

class AuthController extends AbstractActionController
{
    const HOME_PAGE_ROUTE = 'home';
    const LOGIN_PAGE_ROUTE = 'login';
    const FORGOT_PAGE_ROUTE = 'forgot';

    public function loginAction()
    {
        //TODO check only guests
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        if ($user){
            return $this->redirect()->toRoute(self::HOME_PAGE_ROUTE);
        }
        $request = $this->getRequest();
        $form = $this->getLoginForm();
        try {

            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $data = $form->getData();
                    $identity = $data['email'];
                    $password = $data['password'];
                    $remember = $data['rememberme'] == 1;
                    $result = AuthenticationManager::getInstance($this->getServiceLocator())->authenticate($identity, $password, $remember);
                    if (in_array($result->getCode(), array(Result::FAILURE_IDENTITY_NOT_FOUND, Result::FAILURE_CREDENTIAL_INVALID))) {
                        $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_WRONG_EMAIL_OR_PASSWORD);
                    }
                    if ($result->isValid()) {
                        $this->redirect()->toRoute(self::HOME_PAGE_ROUTE);
                    }
                }
            }
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        $viewModel = new ViewModel(array(
            'form' => $form,
        ));

        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function forgotAction()
    {
        $request = $this->getRequest();
        $form = new ForgotPasswordForm();
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        if (!empty($user)){
            return $this->redirect()->toRoute(self::HOME_PAGE_ROUTE);
        }
        try {
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $data = $form->getData();
                    $email = $data['email'];
                    $user = AuthenticationManager::getInstance($this->getServiceLocator())->findUserByEmail($email);
                    if (!is_null($user)) {
                        //Check for facebook user
                        if (!$user->getFacebookId()) {
                            AuthenticationManager::getInstance($this->getServiceLocator())->sendPasswordResetEmail($user, true);
                            $this->flashmessenger()->addSuccessMessage(MessagesConstants::SUCCESS_USER_RECOVERY_LINK_SENT);
                        } else {
                            $this->flashMessenger()->addErrorMessage(MessagesConstants::FACEBOOK_USER_PASSWORD_RECOVERY);
                        }
                        return $this->redirect()->toRoute(self::LOGIN_PAGE_ROUTE);
                    } else {
                        $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_EMAIL_NOT_REGISTERED);
                    }
                }
            }
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        $viewModel = new ViewModel(array(
            'form' => $form,
        ));

        $viewModel->setTerminal(true);
        return $viewModel;

    }

    public function logoutAction()
    {

        try {

            AuthenticationManager::getInstance($this->getServiceLocator())->logout();
            $this->flashmessenger()->addMessage(MessagesConstants::INFO_LOGGED_OUT);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toRoute(self::HOME_PAGE_ROUTE);

    }


    public function resetAction()
    {
        $isValid = false;
        $displayLinkToResetPage = false;
        $form = new ResetPasswordForm();
        //Redirect if user logged in
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        if (!empty($user)){
            return $this->redirect()->toRoute(self::HOME_PAGE_ROUTE);
        }

        try {

            $hash = (string)$this->params()->fromRoute('hash', '');
            $recovery = AuthenticationManager::getInstance($this->getServiceLocator())->checkUserHash($hash);

            if (!is_null($recovery)) {
                //Check date
                $recovery = AuthenticationManager::getInstance($this->getServiceLocator())->checkHashDate($recovery->getHash());
                if (!is_null($recovery)){
                    $request = $this->getRequest();
                    $isValid = true;
                    if ($request->isPost()) {
                        $form->setData($request->getPost());
                        if ($form->isValid()){
                            $data = $form->getData();
                            AuthenticationManager::getInstance($this->getServiceLocator())->saveNewPassword($recovery, $data['password']);
                            $this->flashmessenger()->addSuccessMessage(MessagesConstants::SUCCESS_PASSWORD_CHANGED);
                            return $this->redirect()->toRoute(self::LOGIN_PAGE_ROUTE);
                        }
                    } else {
                        $this->flashmessenger()->addSuccessMessage(MessagesConstants::SUCCESS_CAN_CHANGE_PASSWORD);
                    }
                }else{//Recovery hash expired
                    $displayLinkToResetPage = true;
                    $this->flashmessenger()->addErrorMessage(MessagesConstants::EXPIRED_RECOVERY_PASSWORD_HASH);
                }

            } else {
                $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_RECOVERY_LINK_INVALID);
            }

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        $viewModel = new ViewModel(array(
            'form' => $form,
            'isValid' => $isValid,
            'displayLinkToResetPage' => $displayLinkToResetPage
        ));

        $viewModel->setTerminal(true);
        return $viewModel;
    }

    /**
     * @var \Zend\Form\Form
     */
    protected $form;

    /**
     * @return \Zend\Form\Form
     */
    public function getLoginForm()
    {

        if (null === $this->form) {
            $this->form = new LoginForm();
        }

        return $this->form;

    }
}