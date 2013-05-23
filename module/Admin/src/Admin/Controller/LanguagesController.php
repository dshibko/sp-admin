<?php

namespace Admin\Controller;

use \Application\Manager\LanguageManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class LanguagesController extends AbstractActionController {


    public function indexAction() {

        $languageManager = LanguageManager::getInstance($this->getServiceLocator());

        try {

            $languages = $languageManager->getAllLanguages(true);

        } catch(\Exception $e) {
            $languages = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'languages' => $languages,
        );

    }

}
