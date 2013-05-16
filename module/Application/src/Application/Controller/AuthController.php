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

class AuthController extends AbstractActionController {
    


    public function loginAction() {
         //TODO only for guests
        $request = $this->getRequest();
        $form = $this->getLoginForm();

        if ($request->isPost()){
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
                if ($result->isValid()){
                    $this->redirect()->toRoute('persist');
                }
            }
        }
        return array(
            'form' => $form,
            'messages' => $this->flashMessenger()->getErrorMessages()
        );
    }

//    public function resetPasswordAction() {
//
//        $this->setActionToSession(self::ACTION_RESET_VALUE);
//        $request = $this->getRequest();
//
//        try {
//
//            if ($request->isPost()) {
//                $email = $request->getPost('email');
//                $user = AuthenticationManager::getInstance($this->getServiceLocator())->findUserByEmail($email);
//
//                if ($user != null) {
//                    AuthenticationManager::getInstance($this->getServiceLocator())->sendPasswordResetEmail($user, true);
//                    $this->flashmessenger()->addSuccessMessage(MessagesConstants::SUCCESS_RECOVERY_LINK_SENT);
//                } else
//                    $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_EMAIL_NOT_REGISTERED);
//            }
//
//        } catch (\Exception $e) {
//            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
//        }
//
//        return $this->redirect()->toRoute(self::ADMIN_LOGIN_ROUTE);
//
//    }
//
//    public function logoutAction() {
//
//        try {
//
//            AuthenticationManager::getInstance($this->getServiceLocator())->logout();
//            $this->flashmessenger()->addMessage(MessagesConstants::INFO_LOGGED_OUT);
//
//        } catch (\Exception $e) {
//            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
//        }
//
//        return $this->redirect()->toRoute(self::ADMIN_LOGIN_ROUTE);
//
//    }
//
//    public function forgotAction() {
//
//        $valid = false;
//
//        try {
//
//            if ($this->isAdminGranted())
//                return $this->redirect()->toRoute(self::ADMIN_HOME_ROUTE);
//
//            $hash = (string) $this->params()->fromRoute('hash', '');
//            $recovery = AuthenticationManager::getInstance($this->getServiceLocator())->checkHash($hash);
//            $request = $this->getRequest();
//
//            if ($recovery != null) {
//                if ($request->isPost()) {
//                    $pwd = $request->getPost('pwd');
//                    $pwd2 = $request->getPost('pwd2');
//
//                    if (!empty($pwd) && $pwd == $pwd2) {
//                        AuthenticationManager::getInstance($this->getServiceLocator())->saveNewPassword($recovery, $pwd);
//                        $this->flashmessenger()->addSuccessMessage(MessagesConstants::SUCCESS_PASSWORD_CHANGED);
//                        return $this->redirect()->toRoute(self::ADMIN_LOGIN_ROUTE);
//                    } else
//                        $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_FORM_FILLED_INCORRECTLY);
//                } else
//                    $this->flashmessenger()->addSuccessMessage(MessagesConstants::SUCCESS_CAN_CHANGE_PASSWORD);
//            } else
//                $this->flashmessenger()->addErrorMessage(MessagesConstants::ERROR_RECOVERY_LINK_INVALID);
//
//            $valid = $recovery != null;
//
//        } catch (\Exception $e) {
//            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
//        }
//
//        $this->layout('layout/admin-login-layout');
//
//        return array(
//            'isValid' => $valid
//        );
//
//    }
//
//    /**
//     * @var \Zend\Form\Form
//     */
    protected $form;

    /**
     * @return \Zend\Form\Form
     */
    public function getLoginForm() {

        if ($this->form == null)
            $this->form = new LoginForm();

        return $this->form;

    }
//
//    /**
//     * @return bool
//     */
//    private function isAdminGranted() {
//
//        if (ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser() != null) {
//            $rbacService = $this->getServiceLocator()->get('ZfcRbac\Service\Rbac');
//            $firewalls = $rbacService->getOptions()->getFirewalls();
//
//            if (array_key_exists('ZfcRbac\Firewall\Route', $firewalls)) {
//                $routes = $firewalls['ZfcRbac\Firewall\Route'];
//                foreach ($routes as $route) {
//                    if ($route['route'] == 'admin') {
//                        $adminFirewall = new \ZfcRbac\Firewall\Route(array($route));
//                        $adminFirewall->setRbac($rbacService);
//                        return $adminFirewall->isGranted('admin');
//                    }
//                }
//            }
//        }
//
//        return false;
//
//    }
//
//    /**
//     * @param $action
//     */
//    private function setActionToSession($action) {
//
//        $this->getSessionContainer()->offsetSet(self::ACTION_SESSION_KEY, $action);
//
//    }
//
//    /**
//     * @return bool
//     */
//    private function fetchActionFromSession() {
//
//        if ($this->getSessionContainer()->offsetExists(self::ACTION_SESSION_KEY)) {
//            $action = $this->getSessionContainer()->offsetGet(self::ACTION_SESSION_KEY);
//            $this->getSessionContainer()->offsetUnset(self::ACTION_SESSION_KEY);
//            return $action;
//        }
//
//        return false;
//
//    }

}