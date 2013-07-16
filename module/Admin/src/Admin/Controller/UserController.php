<?php

namespace Admin\Controller;

use Admin\Form\AdminForm;
use Application\Manager\ApplicationManager;
use Application\Manager\AuthenticationManager;
use \Application\Manager\ExceptionManager;
use Application\Manager\LanguageManager;
use Application\Manager\MailManager;
use Application\Manager\RegistrationManager;
use Application\Manager\RoleManager;
use \Application\Manager\UserManager;
use Application\Model\DAOs\AvatarDAO;
use Application\Model\Entities\Role;
use Application\Model\Entities\User;
use Application\Model\Helpers\MessagesConstants;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    const ADMIN_USER_ROUTE = 'admin-users';
    const ADMIN_USER_ADMINS_ACTION = 'admins';

    public function indexAction()
    {

        try {

            $users = UserManager::getInstance($this->getServiceLocator())->getAllUsers(true);

        } catch (\Exception $e) {
            $users = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array('users' => $users));

    }

    public function viewAction()
    {

        try {

            $id = $this->params()->fromRoute('id', 0);
            if ($id == 0)
                return $this->redirect()->toRoute(self::ADMIN_USER_ROUTE);

            $user = UserManager::getInstance($this->getServiceLocator())->getUserById($id, true);

            if ($user == null)
                return $this->notFoundAction();

        } catch (\Exception $e) {
            $user = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array('user' => $user));

    }

    public function exportAction()
    {

        try {

            ini_set('max_execution_time', 0);
            ini_set('max_input_time', -1);
            ini_set('memory_limit', -1);

            $content = UserManager::getInstance($this->getServiceLocator())->getUsersExportContent();

            $response = $this->getResponse();
            $headers = $response->getHeaders();
            $headers->addHeaderLine('Content-Type', 'text/csv');
            $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"users_export.csv\"");
            $headers->addHeaderLine('Accept-Ranges', 'bytes');
            $headers->addHeaderLine('Content-Length', strlen($content));

            $response->setContent($content);

            return $response;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::ADMIN_USER_ROUTE);
        }

    }

    public function adminsAction()
    {
        $admins = array();
        try {
            $admins = UserManager::getInstance($this->getServiceLocator())->getUsersByRoles(array(Role::SUPER_ADMIN, Role::REGIONAL_MANAGER), true);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array('admins' => $admins));
    }

    public function addAdminAction()
    {
        $form = null;
        $params = array();
        $userManager = UserManager::getInstance($this->getServiceLocator());
        $roleManager = RoleManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $avatarDAO = AvatarDAO::getInstance($this->getServiceLocator());
        $mailManager = MailManager::getInstance($this->getServiceLocator());
        try {
            $params = array(
                'action' => 'addAdmin'
            );
            $form = new AdminForm();
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                $post['email'] = isset($post['email']) ? strtolower($post['email']) : null;
                $form->setData($post);
                $form->setInputFilter($this->getServiceLocator()->get('adminFormFilter'));
                if ($form->isValid()) {
                    $data = $form->getData();
                    $user = new User();
                    $password = uniqid();
                    $data['password'] = $applicationManager->encryptPassword($password);
                    $data['country'] = $applicationManager->getDefaultCountry();
                    $data['language'] = $languageManager->getDefaultLanguage();
                    $data['date'] = new \DateTime();
                    $data['avatar'] = $avatarDAO->findOneById(RegistrationManager::DEFAULT_AVATAR_ID);
                    $role = $roleManager->getRoleByName($data['permissions']);
                    if (!is_null($role)) {
                        $data['role'] = $role;
                    }
                    $userManager->saveAdmin($user, $data);
                    $mailManager->sendNewAdminEmail($user->getEmail(), $password);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_ADMIN_CREATED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_USER_ROUTE, array('action' => 'editAdmin', 'id' => $user->getId())));
                } else {
                    $this->formErrors($form, $this);
                }
            }
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        return array(
            'form' => $form,
            'params' => $params,
            'title' => 'Add Admin'
        );
    }

    public function editAdminAction()
    {
        $id = (string)$this->params()->fromRoute('id', '');
        if (empty($id)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_ADMIN_ID);
            return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_USER_ROUTE, array('action' => self::ADMIN_USER_ADMINS_ACTION)));
        }
        $form = null;
        $params = array();
        $userManager = UserManager::getInstance($this->getServiceLocator());

        try {
            $user = $userManager->getUserById($id);
            if (is_null($user)) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_ADMIN);
                return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_USER_ROUTE, array('action' => self::ADMIN_USER_ADMINS_ACTION)));
            }
            $params = array(
                'action' => 'editAdmin',
                'id' => $user->getId()
            );
            $form = new AdminForm();
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                $post['email'] = isset($post['email']) ? strtolower($post['email']) : null;
                $form->setInputFilter($this->getServiceLocator()->get('adminFormFilter'));
                $oldEmail = false;
                if ($post['email'] === strtolower($user->getEmail())) {
                    $form->getInputFilter()->remove('email');
                }else{
                    $oldEmail = $user->getEmail();
                }
                $form->setData($post);
                if ($form->isValid()) {
                    $data = $form->getData();
                    $userManager->saveAdmin($user, $data);
                    if ($oldEmail){
                        AuthenticationManager::getInstance($this->getServiceLocator())->changeIdentity($oldEmail, $user->getEmail());
                    }
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_ADMIN_UPDATED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_USER_ROUTE, $params));
                } else {
                    $this->formErrors($form, $this);
                }
            }
            $form->initForm($user);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        return array(
            'form' => $form,
            'title' => 'Edit Admin',
            'params' => $params
        );
    }

    public function deleteAdminAction()
    {
        $id = (string)$this->params()->fromRoute('id', '');
        if (empty($id)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_ADMIN_ID);
            return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_USER_ROUTE, array('action' => self::ADMIN_USER_ADMINS_ACTION)));
        }
        $userManager = UserManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

        try {
            $user = $userManager->getUserById($id);
            if (is_null($user)) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_ADMIN);
                return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_USER_ROUTE, array('action' => self::ADMIN_USER_ADMINS_ACTION)));
            }
            $currentUser = $applicationManager->getCurrentUser();
            $userManager->deleteAccount($user);
            if ($currentUser->getId() == $user->getId()){
                AuthenticationManager::getInstance($this->getServiceLocator())->logout();
            }
            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_ADMIN_DELETED);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_USER_ROUTE, array('action' => self::ADMIN_USER_ADMINS_ACTION)));
    }

}