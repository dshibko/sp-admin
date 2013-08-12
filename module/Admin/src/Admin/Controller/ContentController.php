<?php

namespace Admin\Controller;

use \Admin\Form\FooterImageForm;
use \Admin\Form\FooterSocialForm;
use Application\Manager\LanguageManager;
use \Application\Model\Helpers\MessagesConstants;
use \Admin\Form\GameplayContentForm;
use \Application\Manager\ContentManager;
use \Application\Manager\ImageManager;
use \Admin\Form\LandingContentForm;
use \Application\Manager\ExceptionManager;
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

        $languageManager = LanguageManager::getInstance($this->getServiceLocator());

        try {

            $languageId = (string) $this->params()->fromRoute('language', '');
            $language = null;
            if (!empty($languageId))
                $language = $languageManager->getLanguageById($languageId);
            if ($language == null)
                $language = $languageManager->getDefaultLanguage();

            $form = new LandingContentForm(null, $language->getIsDefault());

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
                        if ((!array_key_exists('stored', $heroBackgroundImageValue) || $heroBackgroundImageValue['stored'] == 0) && $heroBackgroundImageValue['error'] != 4) {
                            $heroBackgroundImagePath = $imageManager->saveUploadedImage($form->get('heroBackgroundImage'), ImageManager::IMAGE_TYPE_CONTENT);
                            $heroBackgroundImage = $imageManager->prepareContentImage($heroBackgroundImagePath, ImageManager::$HERO_BACKGROUND_SIZES);
                        } else
                            $heroBackgroundImage = null;

                        $heroForegroundImageValue = $form->get('heroForegroundImage')->getValue();
                        if ((!array_key_exists('stored', $heroForegroundImageValue) || $heroForegroundImageValue['stored'] == 0) && $heroForegroundImageValue['error'] != 4) {
                            $heroForegroundImagePath = $imageManager->saveUploadedImage($form->get('heroForegroundImage'), ImageManager::IMAGE_TYPE_CONTENT);
                            $heroForegroundImage = $imageManager->prepareContentImage($heroForegroundImagePath, ImageManager::$HERO_FOREGROUND_SIZES);
                        } else
                            $heroForegroundImage = null;

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveLanguageContent($language, $heroBackgroundImage, $heroForegroundImage, $form->get('headlineCopy')->getValue(), $form->get('registerButtonCopy')->getValue());

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_LANDING_UPDATED);

                        return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, array('language' => $language->getId()));

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            } else {
                $languageContent = ContentManager::getInstance($this->getServiceLocator())->getLanguageContent($language);
                if ($languageContent != null)
                    $form->populateValues($languageContent->getArrayCopy());

            }

            $languages = $languageManager->getAllLanguages(true);

            $gameplayBlocks = ContentManager::getInstance($this->getServiceLocator())->getGameplayBlocks($language, true);

        } catch(\Exception $e) {
            $languages = $gameplayBlocks = array();
            $language = new Language();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array(
            'languages' => $languages,
            'activeLanguage' => $language,
            'form' => $form,
            'gameplayBlocks' => $gameplayBlocks,
            'maxBlocks' => self::MAX_GAMEPLAY_BLOCKS_NUMBER,
        ));

    }

    public function addBlockAction() {

        $language = null;

        try {

            $languageManager = LanguageManager::getInstance($this->getServiceLocator());

            $languageId = (string) $this->params()->fromRoute('language', '');
            $language = null;
            if (!empty($languageId))
                $language = $languageManager->getLanguageById($languageId);
            if ($language == null)
                $language = $languageManager->getDefaultLanguage();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null, 'customTitle' => $parentTitle . " - " . $language->getDisplayName()));

            $languageGameplayBlocksCount = $language->getLanguageGameplayBlocks()->count();
            if ($languageGameplayBlocksCount >= self::MAX_GAMEPLAY_BLOCKS_NUMBER) {
                $this->flashMessenger()->addErrorMessage(sprintf(MessagesConstants::ERROR_MAX_GAMEPLAY_BLOCKS_NUMBER, self::MAX_GAMEPLAY_BLOCKS_NUMBER));
                return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, array('language' => $language->getId()));
            }

            $form = new GameplayContentForm(null, $language->getIsDefault());

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

                        if ($languageGameplayBlocksCount + 1 != $form->get('order')->getValue())
                            ContentManager::getInstance($this->getServiceLocator())->swapLanguageGameplayContentFromOrder($language, $form->get('order')->getValue(), $languageGameplayBlocksCount + 1);

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveLanguageGameplayContent($language, $foregroundImage, $form->get('heading')->getValue(), $form->get('description')->getValue(), $form->get('order')->getValue());

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_GAMEPLAY_BLOCK_CREATED);

                        return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, array('language' => $language->getId()));

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }

            return new ViewModel(array(
                'activeLanguage' => $language,
                'form' => $form,
                'order' => $languageGameplayBlocksCount + 1,
                'action' => 'addBlock',
            ));

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $language == null ? array() : array('language' => $language->getId());
            return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, $routeParams);
        }

    }

    public function editBlockAction() {

        $language = null;

        try {

            $languageManager = LanguageManager::getInstance($this->getServiceLocator());

            $languageId = (string) $this->params()->fromRoute('language', '');
            $language = null;
            if (!empty($languageId))
                $language = $languageManager->getLanguageById($languageId);
            if ($language == null)
                $language = $languageManager->getDefaultLanguage();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null, 'customTitle' => $parentTitle . " - " . $language->getDisplayName()));

            $blockOrder = (int) $this->params()->fromRoute('block', -1);

            $block = ($blockOrder != -1) ? $language->getLanguageGameplayBlockByOrder($blockOrder) : null;

            if ($block == null)
                throw new \Exception(MessagesConstants::ERROR_GAMEPLAY_BLOCK_NOT_FOUND);

            $languageGameplayBlocksCount = $language->getLanguageGameplayBlocks()->count();

            $form = new GameplayContentForm(null, $language->getIsDefault());

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
                                ContentManager::getInstance($this->getServiceLocator())->swapLanguageGameplayContentFromOrder($language, $blockOrder, $newOrder);
                            else
                                ContentManager::getInstance($this->getServiceLocator())->swapLanguageGameplayContentFromOrder($language, $newOrder, $blockOrder);

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveLanguageGameplayContent($language, $foregroundImage, $form->get('heading')->getValue(), $form->get('description')->getValue(), $form->get('order')->getValue(), $block);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_GAMEPLAY_BLOCK_UPDATED);

                        return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, array('language' => $language->getId()));

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
                'activeLanguage' => $language,
                'form' => $form,
                'order' => $languageGameplayBlocksCount,
                'action' => 'editBlock',
            ));

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $language == null ? array() : array('language' => $language->getId());
            return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, $routeParams);
        }

    }

    public function deleteBlockAction() {

        $language = null;

        try {

            $languageManager = LanguageManager::getInstance($this->getServiceLocator());

            $languageId = (string) $this->params()->fromRoute('language', '');
            $language = null;
            if (!empty($languageId))
                $language = $languageManager->getLanguageById($languageId);
            if ($language == null)
                $language = $languageManager->getDefaultLanguage();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null, 'customTitle' => $parentTitle . " - " . $language->getDisplayName()));

            $blockOrder = (int) $this->params()->fromRoute('block', -1);

            $block = ($blockOrder != -1) ? $language->getLanguageGameplayBlockByOrder($blockOrder) : null;

            if ($block == null)
                throw new \Exception(MessagesConstants::ERROR_GAMEPLAY_BLOCK_NOT_FOUND);

            $request = $this->getRequest();

            if ($request->isPost()) {
                try {
                    $del = $request->getPost('del', 'No');

                    if ($del == 'Yes') {
                        ContentManager::getInstance($this->getServiceLocator())->deleteLanguageGameplayContent($block);
                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_GAMEPLAY_BLOCK_DELETED);
                    }

                } catch (\Exception $e) {
                    ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
                }

                $routeParams = $language == null ? array() : array('language' => $language->getId());
                return $this->redirect()->toRoute(self::ADMIN_LANDING_ROUTE, $routeParams);
            }

            return new ViewModel(array(
                'activeLanguage' => $language,
                'block' => $block,
            ));

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $language == null ? array() : array('language' => $language->getId());
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
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());

        try {

            $languageId = (string) $this->params()->fromRoute('language', '');
            $language = null;
            if (!empty($languageId)){
                $language = $languageManager->getLanguageById($languageId);
            }
            if ($language == null){
                $language = $languageManager->getDefaultLanguage();
            }

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
                        $contentManager->addFooterImage($language, $footerImagePath);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_IMAGE_ADDED);

                        return $this->redirect()->toRoute(self::ADMIN_FOOTER_IMAGES_ROUTE, array('language' => $language->getId()));
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }

            $languages = $languageManager->getAllLanguages(true);

            $footerImages = $contentManager->getFooterImages($language, true);

        } catch(\Exception $e) {
            if (empty($languages))
                $languages = array();
            if (empty($language))
                $language = $languageManager->getDefaultLanguage();
            if (empty($footerImages))
                $footerImages = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'form' => $form,
            'languages' => $languages,
            'footerImages' => $footerImages,
            'activeLanguage' => $language,
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

        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());

        try {

            $languageId = (string) $this->params()->fromRoute('language', '');
            $language = null;
            if (!empty($languageId))
                $language = $languageManager->getLanguageById($languageId);
            if ($language == null)
                $language = $languageManager->getDefaultLanguage();

            $languages = $languageManager->getAllLanguages(true);

            $footerSocials = $contentManager->getFooterSocials($language, true);

        } catch(\Exception $e) {
            if (empty($languages))
                $languages = array();
            if (empty($language))
                $language = $languageManager->getDefaultLanguage();
            if (empty($footerSocials))
                $footerSocials = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'languages' => $languages,
            'footerSocials' => $footerSocials,
            'activeLanguage' => $language,
        );

    }

    public function addFooterSocialAction() {

        $language = null;

        try {

            $languageManager = LanguageManager::getInstance($this->getServiceLocator());
            $contentManager = ContentManager::getInstance($this->getServiceLocator());

            $languageId = (string) $this->params()->fromRoute('language', '');
            $language = null;
            if (!empty($languageId))
                $language = $languageManager->getLanguageById($languageId);
            if ($language == null)
                $language = $languageManager->getDefaultLanguage();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null, 'customTitle' => $parentTitle . " - " . $language->getDisplayName()));

            $footerSocialsCount = count($contentManager->getFooterSocials($language));

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
                            ContentManager::getInstance($this->getServiceLocator())->swapFooterSocialsFromOrder($language, $form->get('order')->getValue(), $footerSocialsCount + 1);

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveFooterSocial($language, $iconPath, $form->get('url')->getValue(), $form->get('copy')->getValue(), $form->get('order')->getValue());

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_SOCIAL_CREATED);

                        return $this->redirect()->toRoute(self::ADMIN_FOOTER_SOCIALS_ROUTE, array('language' => $language->getId()));

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $el => $messages)
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                            (is_array($messages) ? implode(", ", $messages): $messages));
            }

            return array(
                'activeLanguage' => $language,
                'form' => $form,
                'order' => $footerSocialsCount + 1,
                'action' => 'addFooterSocial',
            );

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $language == null ? array() : array('language' => $language->getId());
            return $this->redirect()->toRoute(self::ADMIN_FOOTER_SOCIALS_ROUTE, $routeParams);
        }

    }

    public function editFooterSocialAction() {

        $language = null;

        try {

            $languageManager = LanguageManager::getInstance($this->getServiceLocator());
            $contentManager = ContentManager::getInstance($this->getServiceLocator());

            $languageId = (string) $this->params()->fromRoute('language', '');
            if (!empty($languageId))
                $language = $languageManager->getLanguageById($languageId);
            if (empty($language))
                $language = $languageManager->getDefaultLanguage();

            $parentTitle = $this->getActivePage()->getParent()->getTitle();
            $this->getActivePage()->getParent()->setParams(array('action' => null, 'social' => null, 'customTitle' => $parentTitle . " - " . $language->getDisplayName()));

            $socialOrder = (int) $this->params()->fromRoute('social', -1);

            $social = ($socialOrder != -1) ? $language->getFooterSocialByOrder($socialOrder) : null;

            if ($social == null)
                throw new \Exception(MessagesConstants::ERROR_SOCIAL_NOT_FOUND);

            $footerSocialsCount = count($contentManager->getFooterSocials($language));

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
                                ContentManager::getInstance($this->getServiceLocator())->swapFooterSocialsToOrder($language, $socialOrder, $newOrder);
                            else
                                ContentManager::getInstance($this->getServiceLocator())->swapFooterSocialsFromOrder($language, $newOrder, $socialOrder);

                        ContentManager::getInstance($this->getServiceLocator())->
                            saveFooterSocial($language, $iconPath, $form->get('url')->getValue(), $form->get('copy')->getValue(), $form->get('order')->getValue(), $social);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_SOCIAL_UPDATED);

                        return $this->redirect()->toRoute(self::ADMIN_FOOTER_SOCIALS_ROUTE, array('language' => $language->getId()));

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
                'activeLanguage' => $language,
                'form' => $form,
                'order' => $footerSocialsCount,
                'action' => 'editFooterSocial',
            ));

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = $language == null ? array() : array('language' => $language->getId());
            return $this->redirect()->toRoute(self::ADMIN_FOOTER_SOCIALS_ROUTE, $routeParams);
        }

    }

    public function deleteFooterSocialAction() {

        try {
            $languageManager = LanguageManager::getInstance($this->getServiceLocator());
            $footerSocialOrder = (int) $this->params()->fromRoute('social', -1);
            $languageId = (string) $this->params()->fromRoute('language', '');
            $language = null;
            if (!empty($languageId))
                $language = $languageManager->getLanguageById($languageId);
            if ($language == null)
                $language = $languageManager->getDefaultLanguage();

            $contentManager = ContentManager::getInstance($this->getServiceLocator());
            $footerSocial = $language->getFooterSocialByOrder($footerSocialOrder);
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
