<?php

namespace Admin\Controller;

use \Application\Manager\LanguageManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use \Admin\Form\LanguageForm;
use \Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\Entities\Language;

class LanguagesController extends AbstractActionController
{

    const LANGUAGE_LIST_PAGE_ROUTE = 'admin-content-languages';

    public function indexAction()
    {

        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        try {

            $languages = $languageManager->getAllLanguages(true);

        } catch (\Exception $e) {
            $languages = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'languages' => $languages,
        );
    }

    public function addAction()
    {
        $strings = array();
        $params = array('action' => 'add');
        $request = $this->getRequest();
        $languageForm = new LanguageForm(ApplicationManager::getInstance($this->getServiceLocator())->getCountriesSelectOptions());
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        try {
            $language = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
            $strings = $languageManager->getPoFileContent($language->getLanguageCode());
            //Add validation of language code
            if ($request->isPost()) {
                $languageForm->setData($request->getPost());
                if ($languageForm->isValid()) {
                    $data = $languageForm->getData();
                    $newLanguage = new Language();
                    $newLanguage->setDisplayName($data['display_name'])
                                ->setLanguageCode($data['language_code']);

                    $oldErrorReporting = error_reporting(E_ERROR);
                    //Create Po file content
                    if (!$languageManager->savePoFileContent($newLanguage->getLanguageCode(), $data['strings'])) {
                        throw new \Exception(MessagesConstants::ERROR_UPDATE_PO_FILE_FAILED);
                    }
                    //Generate Mo from Po file
                    if (!$languageManager->convertPoToMo($newLanguage->getLanguageCode())) {
                        throw new \Exception(MessagesConstants::ERROR_CONVERTING_PO_FILE_TO_MO_FAILED);
                    }
                    error_reporting($oldErrorReporting);

                    //Update language countries
                    if (!$languageManager->updateLanguageCountries($newLanguage, $data['countries'])) {
                        throw new \Exception(MessagesConstants::ERROR_UPDATE_LANGUAGE_COUNTRIES_FAILED);
                    }
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_LANGUAGE_CREATED);
                    return $this->redirect()->toRoute(self::LANGUAGE_LIST_PAGE_ROUTE);
                } else {
                    foreach ($languageForm->getMessages() as $el => $messages) {
                        $this->flashMessenger()->addErrorMessage($languageForm->get($el)->getLabel() . ": " . (is_array($messages) ? implode(", ", $messages) : $messages));
                    }

                }
            }
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'title' => 'Add Language',
            'form' => $languageForm,
            'url_params' => $params,
            'strings' => $strings
        );
    }

    public function editAction()
    {
        $languageId = (string)$this->params()->fromRoute('language', '');
        $strings = array();
        $params = array();
        $request = $this->getRequest();
        if (empty($languageId) && !$request->isPost()) {
            return $this->redirect()->toRoute(self::LANGUAGE_LIST_PAGE_ROUTE);
        }
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $languageForm = new LanguageForm(ApplicationManager::getInstance($this->getServiceLocator())->getCountriesSelectOptions());
        try {
            $language = $languageManager->getLanguageById($languageId);
            if (null === $language){ //Cannot find language
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_LANGUAGE);
                return $this->redirect()->toRoute(self::LANGUAGE_LIST_PAGE_ROUTE);
            }
            $countryValues = array();
            $countries = $language->getCountries();
            if (!empty($countries)) {
                foreach ($countries as $country) {
                    $countryValues[] = $country->getId();
                }
            }
            $params = array(
                'language' => $language->getId(),
                'action' => 'edit'
            );
            $languageForm->get('countries')->setValue($countryValues);
            //Language Code
            $languageCodeElement = $languageForm->get('language_code');
            $languageCodeElement->setValue($language->getLanguageCode());
            $languageCodeElement->setAttribute('disabled', 'disabled');


            //Display Name
            $displayNameElement = $languageForm->get('display_name');
            $displayNameElement->setValue($language->getDisplayName());

            $strings = $languageManager->getPoFileContent($language->getLanguageCode());

            if ($request->isPost()) {
                $languageForm->setData($request->getPost());
                $languageForm->getInputFilter()->get('language_code')->setRequired(false);
                if ($languageForm->isValid()) {
                    $data = $languageForm->getData();

                    $oldErrorReporting = error_reporting(E_ERROR);
                    //Update Po file content
                    if (!$languageManager->savePoFileContent($language->getLanguageCode(), $data['strings'])) {
                        throw new \Exception(MessagesConstants::ERROR_UPDATE_PO_FILE_FAILED);
                    }
                    //Generate Mo from Po file
                    if (!$languageManager->convertPoToMo($language->getLanguageCode())) {
                        throw new \Exception(MessagesConstants::ERROR_CONVERTING_PO_FILE_TO_MO_FAILED);
                    }
                    error_reporting($oldErrorReporting);

                    //Update language countries
                    $countries = $data['countries'];
                    $language->setDisplayName($data['display_name']);
                    if (!$languageManager->updateLanguageCountries($language, $countries)) {
                        throw new \Exception(MessagesConstants::ERROR_UPDATE_LANGUAGE_COUNTRIES_FAILED);
                    }
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_LANGUAGE_UPDATED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::LANGUAGE_LIST_PAGE_ROUTE, $params));
                } else {
                    //TODO move this to separate method
                    foreach ($languageForm->getMessages() as $el => $messages) {
                        $this->flashMessenger()->addErrorMessage($languageForm->get($el)->getLabel() . ": " . (is_array($messages) ? implode(", ", $messages) : $messages));
                    }

                }
            }
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'title' => 'Edit Language',
            'form' => $languageForm,
            'strings' => $strings,
            'url_params' => $params
        );
    }

}
