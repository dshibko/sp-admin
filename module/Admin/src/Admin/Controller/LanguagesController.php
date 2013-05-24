<?php

namespace Admin\Controller;

use \Application\Manager\LanguageManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use \Admin\Form\LanguageForm;
use \Application\Manager\ApplicationManager;

class LanguagesController extends AbstractActionController {

    const LANGUAGE_LIST_PAGE_ROUTE = 'admin-content-languages';
    public function indexAction()
    {

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

    public function addAction()
    {

    }

    public function editAction()
    {
        $languageId = (string) $this->params()->fromRoute('language', '');
        $countryValues = array();
        $language = null;
        $strings = array();

        if (empty($languageId)){
            return $this->redirect()->toRoute(self::LANGUAGE_LIST_PAGE_ROUTE);
        }
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $languageForm = new LanguageForm(ApplicationManager::getInstance($this->getServiceLocator())->getCountriesSelectOptions());
        try {
            $language = $languageManager->getLanguageById($languageId);
            $countries = $language->getCountries();
            if (!empty($countries)){
                foreach($countries as $country){
                    $countryValues[] = $country->getId();
                }
            }
            $languageForm->get('countries')->setValue($countryValues);
            $languageCodeElement = $languageForm->get('language_code');
            $languageCodeElement->setValue($language->getLanguageCode());
            $languageCodeElement->setAttribute('disabled','disabled');
            $strings = $applicationManager->getPOFileContent($language->getLanguageCode());
        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'title' => 'Edit Language',
            'action' => 'edit',
            'form'  => $languageForm,
            'language' => $language,
            'strings' => $strings
        );
    }

}
