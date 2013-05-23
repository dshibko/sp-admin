<?php

namespace Admin\Controller;

use \Zend\View\Model\ViewModel;
use \Admin\Form\FooterSocialForm;
use \Application\Manager\ImageManager;
use \Admin\Form\FooterImageForm;
use \Application\Manager\ContentManager;
use \Application\Model\Helpers\MessagesConstants;
use \Admin\Form\RegionLanguageForm;
use \Application\Manager\LanguageManager;
use \Application\Manager\RegionManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class SettingsController extends AbstractActionController {

    const ADMIN_REGION_LANGUAGE_ROUTE = 'admin-settings-region-language';
    const ADMIN_FOOTER_IMAGES_ROUTE = 'admin-settings-footer-images';
    const ADMIN_FOOTER_SOCIALS_ROUTE = 'admin-settings-footer-socials';

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

        $form = new FooterImageForm();

        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());

        try {

            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()) {
                    try {

                        $imageManager = ImageManager::getInstance($this->getServiceLocator());
                        $footerImagePath = $imageManager->saveUploadedImage($form->get('footerImage'), ImageManager::IMAGE_TYPE_CONTENT);
                        $contentManager->addFooterImage($region, $footerImagePath);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_IMAGE_ADDED);
                        return $this->redirect()->toRoute(self::ADMIN_FOOTER_IMAGES_ROUTE, array('region' => $region->getId()));
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }

            $regions = $regionManager->getAllRegions(true);

            $footerImages = $contentManager->getFooterImages($region, true);

        } catch(\Exception $e) {
            if (empty($regions))
                $regions = array();
            if (empty($region))
                $region = $regionManager->getDefaultRegion();
            if (empty($footerImages))
                $footerImages = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'form' => $form,
            'regions' => $regions,
            'footerImages' => $footerImages,
            'activeRegion' => $region,
        );

    }

    public function deleteFooterImageAction() {

        try {
            $footerImageId = (int) $this->params()->fromRoute('image', -1);
            $contentManager = ContentManager::getInstance($this->getServiceLocator());
            $footerImageDeleted = $contentManager->deleteFooterImage($footerImageId);
            if ($footerImageDeleted)
                $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_IMAGE_DELETED);
            else
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_FOOTER_IMAGE_NOT_DELETED);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toRoute(self::ADMIN_FOOTER_IMAGES_ROUTE);

    }

    public function footerSocialsAction() {

        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());

        try {

            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $regions = $regionManager->getAllRegions(true);

            $footerSocials = $contentManager->getFooterSocials($region, true);

        } catch(\Exception $e) {
            if (empty($regions))
                $regions = array();
            if (empty($region))
                $region = $regionManager->getDefaultRegion();
            if (empty($footerSocials))
                $footerSocials = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'regions' => $regions,
            'footerSocials' => $footerSocials,
            'activeRegion' => $region,
        );

    }

    public function addFooterSocialAction() {

        $region = null;

        try {

            $regionManager = RegionManager::getInstance($this->getServiceLocator());
            $contentManager = ContentManager::getInstance($this->getServiceLocator());

            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null, 'customTitle' => $parentTitle . " - " . $region->getDisplayName()));

            $footerSocialsCount = count($contentManager->getFooterSocials($region));

            $form = new FooterSocialForm();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()) {
                    try {

                        $imageManager = ImageManager::getInstance($this->getServiceLocator());
                        $iconPath = $imageManager->saveUploadedImage($form->get('icon'), ImageManager::IMAGE_TYPE_CONTENT);

                        if ($footerSocialsCount + 1 != $form->get('order')->getValue())
                            ContentManager::getInstance($this->getServiceLocator())->swapFooterSocialsFromOrder($region, $form->get('order')->getValue(), $footerSocialsCount + 1);

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveFooterSocial($region, $iconPath, $form->get('url')->getValue(), $form->get('copy')->getValue(), $form->get('order')->getValue());

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_SOCIAL_CREATED);

                        return $this->redirect()->toRoute(self::ADMIN_FOOTER_SOCIALS_ROUTE, array('region' => $region->getId()));

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }

            return array(
                'activeRegion' => $region,
                'form' => $form,
                'order' => $footerSocialsCount + 1,
                'action' => 'addFooterSocial',
            );

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $region == null ? array() : array('region' => $region->getId());
            return $this->redirect()->toRoute(self::ADMIN_FOOTER_SOCIALS_ROUTE, $routeParams);
        }

    }

    public function editFooterSocialAction() {

        $region = null;

        try {

            $regionManager = RegionManager::getInstance($this->getServiceLocator());
            $contentManager = ContentManager::getInstance($this->getServiceLocator());

            $regionId = (string) $this->params()->fromRoute('region', '');
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if (empty($region))
                $region = $regionManager->getDefaultRegion();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'social' => null, 'customTitle' => $parentTitle . " - " . $region->getDisplayName()));

            $socialOrder = (int) $this->params()->fromRoute('social', -1);

            $social = ($socialOrder != -1) ? $region->getFooterSocialByOrder($socialOrder) : null;

            if ($social == null)
                throw new \Exception(MessagesConstants::ERROR_SOCIAL_NOT_FOUND);

            $footerSocialsCount = count($contentManager->getFooterSocials($region));

            $form = new FooterSocialForm();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()) {
                    try {
                        $imageManager = ImageManager::getInstance($this->getServiceLocator());

                        $iconValue = $form->get('icon')->getValue();
                        if (!array_key_exists('stored', $iconValue) || $iconValue['stored'] == 0) {
                            $iconPath = $imageManager->saveUploadedImage($form->get('icon'), ImageManager::IMAGE_TYPE_CONTENT);
                        } else
                            $iconPath = null;

                        $newOrder = $form->get('order')->getValue();
                        if ($socialOrder != $newOrder)
                            if ($newOrder > $socialOrder)
                                ContentManager::getInstance($this->getServiceLocator())->swapFooterSocialsToOrder($region, $socialOrder, $newOrder);
                            else
                                ContentManager::getInstance($this->getServiceLocator())->swapFooterSocialsFromOrder($region, $newOrder, $socialOrder);

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveFooterSocial($region, $iconPath, $form->get('url')->getValue(), $form->get('copy')->getValue(), $form->get('order')->getValue(), $social);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_SOCIAL_UPDATED);

                        return $this->redirect()->toRoute(self::ADMIN_FOOTER_SOCIALS_ROUTE, array('region' => $region->getId()));

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            } else
                $form->populateValues($social->getArrayCopy());

            return new ViewModel(array(
                'activeRegion' => $region,
                'form' => $form,
                'order' => $footerSocialsCount,
                'action' => 'editFooterSocial',
            ));

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $region == null ? array() : array('region' => $region->getId());
            return $this->redirect()->toRoute(self::ADMIN_FOOTER_SOCIALS_ROUTE, $routeParams);
        }

    }

    public function deleteFooterSocialAction() {

        try {
            $regionManager = RegionManager::getInstance($this->getServiceLocator());
            $footerSocialOrder = (int) $this->params()->fromRoute('social', -1);
            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $contentManager = ContentManager::getInstance($this->getServiceLocator());
            $footerSocial = $region->getFooterSocialByOrder($footerSocialOrder);
            if ($footerSocial != null) {
                $contentManager->deleteFooterSocial($footerSocial);
                $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_SOCIAL_DELETED);
            } else
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_FOOTER_SOCIAL_NOT_DELETED);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toRoute(self::ADMIN_FOOTER_SOCIALS_ROUTE);

    }

}
