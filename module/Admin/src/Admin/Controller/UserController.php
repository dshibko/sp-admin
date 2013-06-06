<?php

namespace Admin\Controller;

use \Application\Manager\ExceptionManager;
use \Application\Manager\UserManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController {
    
    public function indexAction() {

        try {

            $users = UserManager::getInstance($this->getServiceLocator())->getAllUsers(true);

        } catch(\Exception $e) {
            $users = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array('users' => $users));

    }

    public function viewAction() {

        try {

            $id = $this->params()->fromRoute('id', 0);
            if ($id == 0)
                return $this->redirect()->toRoute('admin-users');

            $user = UserManager::getInstance($this->getServiceLocator())->getUserById($id, true);

            if ($user == null)
                return $this->notFoundAction();

        } catch(\Exception $e) {
            $user = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array('user' => $user));

    }

    public function exportAction() {

        try {

            $content = UserManager::getInstance($this->getServiceLocator())->getUsersExportContent();

            $response = $this->getResponse();
            $headers = $response->getHeaders();
            $headers->addHeaderLine('Content-Type', 'text/csv');
            $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"users_export.csv\"");
            $headers->addHeaderLine('Accept-Ranges', 'bytes');
            $headers->addHeaderLine('Content-Length', strlen($content));

            $response->setContent($content);

            return $response;

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-users');
        }

    }

}