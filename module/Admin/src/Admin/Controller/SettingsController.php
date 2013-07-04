<?php

namespace Admin\Controller;

use \Application\Manager\CacheManager;
use \Application\Manager\SettingsManager;
use \Zend\View\Model\ViewModel;
use \Admin\Form\FooterSocialForm;
use \Application\Manager\ImageManager;
use \Admin\Form\FooterImageForm;
use \Application\Manager\ContentManager;
use \Application\Model\Helpers\MessagesConstants;
use \Admin\Form\SettingsForm;
use \Application\Manager\LanguageManager;
use \Application\Manager\RegionManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class SettingsController extends AbstractActionController {

    const ADMIN_REGION_LANGUAGE_ROUTE = 'admin-settings';

    public function indexAction() {

        $form = new SettingsForm();

        try {

            $regionManager = RegionManager::getInstance($this->getServiceLocator());
            $languageManager = LanguageManager::getInstance($this->getServiceLocator());
            $settingsManager = SettingsManager::getInstance($this->getServiceLocator());

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $settings = array();
                    foreach ($form->getElements() as $element)
                        $settings[$element->getName()] = $element->getValue();

                    $settingsManager->saveSettings($settings);
                    $regionValue = $form->get('region')->getValue();
                    $newRegion = $regionManager->getRegionById($regionValue);
                    $languageValue = $form->get('language')->getValue();
                    $newLanguage = $languageManager->getLanguageById($languageValue);
                    if ($newRegion == null)
                        throw new \Exception(MessagesConstants::ERROR_REGION_NOT_FOUND);
                    if ($newLanguage == null)
                        throw new \Exception(MessagesConstants::ERROR_LANGUAGE_NOT_FOUND);
                    $regionManager->setDefaultRegion($newRegion);
                    $languageManager->setDefaultLanguage($newLanguage);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_SETTINGS_UPDATED);
                    return $this->redirect()->toRoute(self::ADMIN_REGION_LANGUAGE_ROUTE);
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }

            $form->populateValues($settingsManager->getSettingsAsArray());

            $regions = $regionManager->getAllRegions(true);
            $defaultRegion = $regionManager->getDefaultRegion();

            $languages = $languageManager->getAllLanguages(true);
            $defaultLanguage = $languageManager->getDefaultLanguage();

            $languagesOptions = array();
            foreach ($languages as $language)
                $languagesOptions [] = array(
                    'value'    => $language['id'],
                    'label'    => $language['displayName'],
                    'selected' => $language['id'] == $defaultLanguage->getId(),
                );

            $form->get('language')->setValueOptions($languagesOptions);

            $regionsOptions = array();
            foreach ($regions as $region)
                $regionsOptions [] = array(
                    'value'    => $region['id'],
                    'label'    => $region['displayName'],
                    'selected' => $region['id'] == $defaultRegion->getId(),
                );

            $form->get('region')->setValueOptions($regionsOptions);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'form' => $form,
        );

    }

    public function clearAppCacheAction() {
        CacheManager::getInstance($this->getServiceLocator())->clearCache();
        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_APP_CACHE_CLEARED);
        return $this->redirect()->toRoute(self::ADMIN_REGION_LANGUAGE_ROUTE);
    }

}
