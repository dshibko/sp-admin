<?php

namespace Admin\Controller;

use \Application\Model\Helpers\MessagesConstants;
use \Admin\Form\RegionLanguageForm;
use \Application\Manager\LanguageManager;
use \Application\Manager\RegionManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class SettingsController extends AbstractActionController {

    const ADMIN_REGION_LANGUAGE_ROUTE = 'admin-settings-region-language';

    public function indexAction() {
        return $this->redirect()->toRoute(self::ADMIN_REGION_LANGUAGE_ROUTE);
    }

    public function regionAction() {

        $form = new RegionLanguageForm();

        try {

            $regionManager = RegionManager::getInstance($this->getServiceLocator());
            $languageManager = LanguageManager::getInstance($this->getServiceLocator());

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
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
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_REGION_LANGUAGE_UPDATED);
                    return $this->redirect()->toRoute(self::ADMIN_REGION_LANGUAGE_ROUTE);
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }

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

    public function footerImagesAction() {

//        $regionManager = RegionManager::getInstance($this->getServiceLocator());
//
//        try {
//
//            $regionId = (string) $this->params()->fromRoute('region', '');
//            $region = null;
//            if (!empty($regionId))
//                $region = $regionManager->getRegionById($regionId);
//            if ($region == null)
//                $region = $regionManager->getDefaultRegion();
//
//            $request = $this->getRequest();
//            if ($request->isPost()) {
//
//            } else if ($region->getRegionContent() != null)
//                $form->populateValues($region->getRegionContent()->getArrayCopy());
//
//
//        } catch(\Exception $e) {
//            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
//        }

        return array(
//            'form' => $form,
        );

    }

}
