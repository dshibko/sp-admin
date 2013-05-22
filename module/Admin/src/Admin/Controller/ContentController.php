<?php

namespace Admin\Controller;

use \Zend\View\Helper\Navigation\Breadcrumbs;
use \Application\Model\Entities\RegionGameplayContent;
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
            } else if ($region->getRegionContent() != null)
                $form->populateValues($region->getRegionContent()->getArrayCopy());

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

            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null));

            $regionManager = RegionManager::getInstance($this->getServiceLocator());

            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setTitle($parentTitle . " - " . $region->getDisplayName());

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

            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null));

            $regionManager = RegionManager::getInstance($this->getServiceLocator());

            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setTitle($parentTitle . " - " . $region->getDisplayName());

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

                        if ($blockOrder != $form->get('order')->getValue())
                            ContentManager::getInstance($this->getServiceLocator())->swapRegionGameplayContentFromOrder($region, $form->get('order')->getValue(), $regionGameplayBlocksCount);

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

            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null));

            $regionManager = RegionManager::getInstance($this->getServiceLocator());

            $regionId = (string) $this->params()->fromRoute('region', '');
            $region = null;
            if (!empty($regionId))
                $region = $regionManager->getRegionById($regionId);
            if ($region == null)
                $region = $regionManager->getDefaultRegion();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setTitle($parentTitle . " - " . $region->getDisplayName());

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

}
