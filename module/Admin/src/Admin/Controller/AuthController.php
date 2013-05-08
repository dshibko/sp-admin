<?php

namespace Admin\Controller;

use \Zend\Authentication\Result;
use \Application\Manager\ExceptionManager;
use \Application\Manager\ApplicationManager;
use \Zend\Form\Annotation\AnnotationBuilder;
use \Application\Model\Entities\User;
use \Admin\Form\LoginForm;
use \Neoco\Controller\AbstractActionController;
use \Application\Manager\AuthenticationManager;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use \Application\Model\Helpers\MessagesConstants;

class AuthController extends AbstractActionController {
    
    const ADMIN_HOME_ROUTE = 'admin-home';
    const ADMIN_LOGIN_ROUTE = 'admin-login';

    const ACTION_SESSION_KEY = 'action';
    const ACTION_LOGIN_VALUE = 'login';
    const ACTION_RESET_VALUE = 'reset';

    public function loginAction() {

        $action = false;

        try {

            if ($this->isAdminGranted())
                return $this->redirect()->toRoute(self::ADMIN_HOME_ROUTE);

            $action = $this->fetchActionFromSession();

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        $this->layout('layout/admin-login-layout');

        return array(
            'form' => $this->getLoginForm(),
            'active' => $action
        );
    }

    public function authenticateAction() {

        $this->setActionToSession(self::ACTION_LOGIN_VALUE);

        try {

            $form = $this->getLoginForm();
            $redirect = self::ADMIN_LOGIN_ROUTE;
            $request = $this->getRequest();

            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $identity = $request->getPost('email');
                    $pwd = $request->getPost('password');
                    $remember = $request->getPost('rememberme') == 1;
                    $result = AuthenticationManager::getInstance($this->getServiceLocator())->authenticate($identity, $pwd, $remember);

                    if (in_array($result->getCode(), array(Result::FAILURE_IDENTITY_NOT_FOUND, Result::FAILURE_CREDENTIAL_INVALID)))
                        $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_WRONG_EMAIL_OR_PASSWORD);

                    if ($result->isValid())
                        $redirect = self::ADMIN_HOME_ROUTE;
                } else
                    $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_FORM_FILLED_INCORRECTLY);
            }

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $redirect = self::ADMIN_LOGIN_ROUTE;
        }

        return $this->redirect()->toRoute($redirect);

    }

    public function resetPasswordAction() {

        $this->setActionToSession(self::ACTION_RESET_VALUE);
        $request = $this->getRequest();

        try {

            if ($request->isPost()) {
                $email = $request->getPost('email');
                $user = AuthenticationManager::getInstance($this->getServiceLocator())->findUserByEmail($email);

                if ($user != null) {
                    AuthenticationManager::getInstance($this->getServiceLocator())->sendPasswordResetEmail($user, true);
                    $this->flashmessenger()->addSuccessMessage(MessagesConstants::SUCCESS_RECOVERY_LINK_SENT);
                } else
                    $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_EMAIL_NOT_REGISTERED);
            }

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toRoute(self::ADMIN_LOGIN_ROUTE);

    }

    public function logoutAction() {

        try {

            AuthenticationManager::getInstance($this->getServiceLocator())->logout();
            $this->flashmessenger()->addMessage(MessagesConstants::INFO_LOGGED_OUT);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toRoute(self::ADMIN_LOGIN_ROUTE);

    }

    public function forgotAction() {

        $valid = false;

        try {

            if ($this->isAdminGranted())
                return $this->redirect()->toRoute(self::ADMIN_HOME_ROUTE);

            $hash = (string) $this->params()->fromRoute('hash', '');
            $recovery = AuthenticationManager::getInstance($this->getServiceLocator())->checkHash($hash);
            $request = $this->getRequest();

            if ($recovery != null) {
                if ($request->isPost()) {
                    $pwd = $request->getPost('pwd');
                    $pwd2 = $request->getPost('pwd2');

                    if (!empty($pwd) && $pwd == $pwd2) {
                        AuthenticationManager::getInstance($this->getServiceLocator())->saveNewPassword($recovery, $pwd);
                        $this->flashmessenger()->addSuccessMessage(MessagesConstants::SUCCESS_PASSWORD_CHANGED);
                        return $this->redirect()->toRoute(self::ADMIN_LOGIN_ROUTE);
                    } else
                        $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_FORM_FILLED_INCORRECTLY);
                } else
                    $this->flashmessenger()->addSuccessMessage(MessagesConstants::SUCCESS_CAN_CHANGE_PASSWORD);
            } else
                $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_RECOVERY_LINK_INVALID);

            $valid = $recovery != null;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        $this->layout('layout/admin-login-layout');

        return array(
            'isValid' => $valid
        );

    }

    /**
     * @var \Zend\Form\Form
     */
    protected $form;

    /**
     * @return \Zend\Form\Form
     */
    public function getLoginForm() {

        if ($this->form == null)
            $this->form = new LoginForm();

        return $this->form;

    }

    /**
     * @return bool
     */
    private function isAdminGranted() {

        if (ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser() != null) {
            $rbacService = $this->getServiceLocator()->get('ZfcRbac\Service\Rbac');
            $firewalls = $rbacService->getOptions()->getFirewalls();

            if (array_key_exists('ZfcRbac\Firewall\Route', $firewalls)) {
                $routes = $firewalls['ZfcRbac\Firewall\Route'];
                foreach ($routes as $route) {
                    if ($route['route'] == 'admin') {
                        $adminFirewall = new \ZfcRbac\Firewall\Route(array($route));
                        $adminFirewall->setRbac($rbacService);
                        return $adminFirewall->isGranted('admin');
                    }
                }
            }
        }

        return false;

    }

    /**
     * @param $action
     */
    private function setActionToSession($action) {

        $this->getSessionContainer()->offsetSet(self::ACTION_SESSION_KEY, $action);

    }

    /**
     * @return bool
     */
    private function fetchActionFromSession() {

        if ($this->getSessionContainer()->offsetExists(self::ACTION_SESSION_KEY)) {
            $action = $this->getSessionContainer()->offsetGet(self::ACTION_SESSION_KEY);
            $this->getSessionContainer()->offsetUnset(self::ACTION_SESSION_KEY);
            return $action;
        }

        return false;

    }

}