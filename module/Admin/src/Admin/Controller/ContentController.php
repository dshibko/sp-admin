<?php

namespace Admin\Controller;

use \Admin\Form\FooterImageForm;
use \Admin\Form\FooterSocialForm;
use \Application\Model\Helpers\MessagesConstants;
use \Admin\Form\GameplayContentForm;
use \Application\Manager\ContentManager;
use \Application\Manager\ImageManager;
use \Admin\Form\LandingContentForm;
use \Application\Model\Entities\Region;
use \Application\Manager\ExceptionManager;
use \Application\Manager\RegionManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContentController extends AbstractActionController {

    const MAX_GAMEPLAY_BLOCKS_NUMBER = 8;
    const ADMIN_LANDING_ROUTE = 'admin-content-landing';
    const ADMIN_FOOTER_IMAGES_ROUTE = 'admin-content-footer-images';
    const ADMIN_FOOTER_SOCIALS_ROUTE = 'admin-content-footer-socials';

    public function indexAction() {
        return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE);
    }

    public function landingAction() {

        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        $form = new LandingContentForm();

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
                        $heroBackgroundImageValue = $form->get('heroBackgroundImage')->getValue();
                        if (!array_key_exists('stored', $heroBackgroundImageValue) || $heroBackgroundImageValue['stored'] == 0) {
                            $heroBackgroundImagePath = $imageManager->saveUploadedImage($form->get('heroBackgroundImage'), ImageManager::IMAGE_TYPE_CONTENT);
                            $heroBackgroundImage = $imageManager->prepareContentImage($heroBackgroundImagePath, ImageManager::$HERO_BACKGROUND_SIZES);
                        } else
                            $heroBackgroundImage = null;

                        $heroForegroundImageValue = $form->get('heroForegroundImage')->getValue();
                        if (!array_key_exists('stored', $heroForegroundImageValue) || $heroForegroundImageValue['stored'] == 0) {
                            $heroForegroundImagePath = $imageManager->saveUploadedImage($form->get('heroForegroundImage'), ImageManager::IMAGE_TYPE_CONTENT);
                            $heroForegroundImage = $imageManager->prepareContentImage($heroForegroundImagePath, ImageManager::$HERO_FOREGROUND_SIZES);
                        } else
                            $heroForegroundImage = null;

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveRegionContent($region, $heroBackgroundImage, $heroForegroundImage, $form->get('headlineCopy')->getValue(), $form->get('registerButtonCopy')->getValue());

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_LANDING_UPDATED);

                        return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, array('region' => $region->getId()));

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            } else {
                $regionContent = ContentManager::getInstance($this->getServiceLocator())->getRegionContent($region);
                if ($regionContent != null)
                    $form->populateValues($regionContent->getArrayCopy());
            }

            $regions = $regionManager->getAllRegions(true);

            $gameplayBlocks = ContentManager::getInstance($this->getServiceLocator())->getGameplayBlocks($region, true);

        } catch(\Exception $e) {
            $regions = $gameplayBlocks = array();
            $region = new Region();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array(
            'regions' => $regions,
            'activeRegion' => $region,
            'form' => $form,
            'gameplayBlocks' => $gameplayBlocks,
            'maxBlocks' => self::MAX_GAMEPLAY_BLOCKS_NUMBER,
        ));

    }

    public function addBlockAction() {

        $region = null;

        try {

            $regionManager = RegionManager::getInstance($this->getServiceLocator());

            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null, 'customTitle' => $parentTitle . " - " . $region->getDisplayName()));

            $regionGameplayBlocksCount = $region->getRegionGameplayBlocks()->count();
            if ($regionGameplayBlocksCount >= self::MAX_GAMEPLAY_BLOCKS_NUMBER) {
                $this->flashMessenger()->addErrorMessage(sprintf(MessagesConstants::ERROR_MAX_GAMEPLAY_BLOCKS_NUMBER, self::MAX_GAMEPLAY_BLOCKS_NUMBER));
                return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, array('region' => $region->getId()));
            }

            $form = new GameplayContentForm();

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
                        $foregroundImagePath = $imageManager->saveUploadedImage($form->get('foregroundImage'), ImageManager::IMAGE_TYPE_CONTENT);
                        $foregroundImage = $imageManager->prepareContentImage($foregroundImagePath, ImageManager::$GAMEPLAY_FOREGROUND_SIZES);

                        if ($regionGameplayBlocksCount + 1 != $form->get('order')->getValue())
                            ContentManager::getInstance($this->getServiceLocator())->swapRegionGameplayContentFromOrder($region, $form->get('order')->getValue(), $regionGameplayBlocksCount + 1);

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveRegionGameplayContent($region, $foregroundImage, $form->get('heading')->getValue(), $form->get('description')->getValue(), $form->get('order')->getValue());

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_GAMEPLAY_BLOCK_CREATED);

                        return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, array('region' => $region->getId()));

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }

            return new ViewModel(array(
                'activeRegion' => $region,
                'form' => $form,
                'order' => $regionGameplayBlocksCount + 1,
                'action' => 'addBlock',
            ));

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $region == null ? array() : array('region' => $region->getId());
            return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, $routeParams);
        }

    }

    public function editBlockAction() {

        $region = null;

        try {

            $regionManager = RegionManager::getInstance($this->getServiceLocator());

            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null, 'customTitle' => $parentTitle . " - " . $region->getDisplayName()));

            $blockOrder = (int) $this->params()->fromRoute('block', -1);

            $block = ($blockOrder != -1) ? $region->getRegionGameplayBlockByOrder($blockOrder) : null;

            if ($block == null)
                throw new \Exception(MessagesConstants::ERROR_GAMEPLAY_BLOCK_NOT_FOUND);

            $regionGameplayBlocksCount = $region->getRegionGameplayBlocks()->count();

            $form = new GameplayContentForm();

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
                        $foregroundImageValue = $form->get('foregroundImage')->getValue();
                        if (!array_key_exists('stored', $foregroundImageValue) || $foregroundImageValue['stored'] == 0) {
                            $foregroundImagePath = $imageManager->saveUploadedImage($form->get('foregroundImage'), ImageManager::IMAGE_TYPE_CONTENT);
                            $foregroundImage = $imageManager->prepareContentImage($foregroundImagePath, ImageManager::$GAMEPLAY_FOREGROUND_SIZES);
                        } else
                            $foregroundImage = null;

                        $newOrder = $form->get('order')->getValue();
                        if ($blockOrder != $newOrder)
                            if ($newOrder > $blockOrder)
                                ContentManager::getInstance($this->getServiceLocator())->swapRegionGameplayContentFromOrder($region, $blockOrder, $newOrder);
                            else
                                ContentManager::getInstance($this->getServiceLocator())->swapRegionGameplayContentFromOrder($region, $newOrder, $blockOrder);

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveRegionGameplayContent($region, $foregroundImage, $form->get('heading')->getValue(), $form->get('description')->getValue(), $form->get('order')->getValue(), $block);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_GAMEPLAY_BLOCK_UPDATED);

                        return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, array('region' => $region->getId()));

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            } else
                $form->populateValues($block->getArrayCopy());

            return new ViewModel(array(
                'activeRegion' => $region,
                'form' => $form,
                'order' => $regionGameplayBlocksCount,
                'action' => 'editBlock',
            ));

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $region == null ? array() : array('region' => $region->getId());
            return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, $routeParams);
        }

    }

    public function deleteBlockAction() {

        $region = null;

        try {

            $regionManager = RegionManager::getInstance($this->getServiceLocator());

            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null, 'customTitle' => $parentTitle . " - " . $region->getDisplayName()));

            $blockOrder = (int) $this->params()->fromRoute('block', -1);

            $block = ($blockOrder != -1) ? $region->getRegionGameplayBlockByOrder($blockOrder) : null;

            if ($block == null)
                throw new \Exception(MessagesConstants::ERROR_GAMEPLAY_BLOCK_NOT_FOUND);

            $request = $this->getRequest();

            if ($request->isPost()) {
                try {
                    $del = $request->getPost('del', 'No');

                    if ($del == 'Yes') {
                        ContentManager::getInstance($this->getServiceLocator())->deleteRegionGameplayContent($block);
                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_GAMEPLAY_BLOCK_DELETED);
                    }

                } catch (\Exception $e) {
                    ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
                }

                $routeParams = $region == null ? array() : array('region' => $region->getId());
                return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, $routeParams);
            }

            return new ViewModel(array(
                'activeRegion' => $region,
                'block' => $block,
            ));

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $region == null ? array() : array('region' => $region->getId());
            return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, $routeParams);
        }
    }

    public function reportsAction() {

        try {

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array(
        ));

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
