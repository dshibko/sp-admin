<?php

namespace Admin\Controller;

use Application\Manager\ContentManager;
use Application\Manager\LanguageManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class TermsController extends AbstractActionController {

    const ADMIN_CONTENT_TERMS_ROUTE = 'admin-content-terms';

    public function indexAction()
    {
        $form = null;
        $lanaguageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try {

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'title' => 'Terms',
            'form' => $form,
        );
    }

    public function addAction()
    {
        return array();
    }

    public function editAction()
    {
        return array();
    }

}
