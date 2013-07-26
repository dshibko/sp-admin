<?php

namespace Admin\Controller;

use \Application\Manager\ContentManager;
use \Application\Model\Entities\DefaultReportContent;
use \Application\Manager\ImageManager;
use \Admin\Form\MatchReportContentFieldset;
use \Application\Manager\LanguageManager;
use \Admin\Form\MatchReportContentForm;
use \Zend\View\Model\ViewModel;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

abstract class MatchContentController extends AbstractActionController {

    abstract protected function getRedirectRoute();

    abstract protected function getMatchReportType();

    protected function getReportContentForm($init = false) {
        $languages = LanguageManager::getInstance($this->getServiceLocator())->getAllLanguages(true);
        $languageFieldsets = array();

        foreach ($languages as $language)
            $languageFieldsets [] = new MatchReportContentFieldset($language);
        $form = new MatchReportContentForm($languageFieldsets);
        if ($init) {
            $contentManager = ContentManager::getInstance($this->getServiceLocator());
            $languagesData = array();
            foreach ($languages as $language)
                $languagesData[$language['id']] = $contentManager->getDefaultReportContentByTypeAndLanguage($language['id'], $this->getMatchReportType());
            $form->initForm($languagesData);
        }
        return $form;
    }

    public function saveDefaultContentAction() {

        try {

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form = $this->getReportContentForm();
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()) {

                    $imageManager = ImageManager::getInstance($this->getServiceLocator());
                    $languageManager = LanguageManager::getInstance($this->getServiceLocator());
                    $contentManager = ContentManager::getInstance($this->getServiceLocator());

                    foreach ($form->getFieldsets() as $languageFieldset) {
                        $languageData = $languageFieldset->getData();
                        $defaultReportContent = $contentManager->getDefaultReportContentByTypeAndLanguage($languageData['id'], $this->getMatchReportType());
                        if ($defaultReportContent === null) {
                            $defaultReportContent = new DefaultReportContent();
                            $defaultReportContent->setLanguage($languageManager->getLanguageById($languageData['id']));
                            $defaultReportContent->setReportType($this->getMatchReportType());
                        }
                        $defaultReportContent->setTitle($languageFieldset->get('title')->getValue());
                        $defaultReportContent->setIntro($languageFieldset->get('intro')->getValue());
                        $headerImage = $languageFieldset->get('headerImage');
                        $headerImagePath = $imageManager->saveUploadedImage($headerImage, ImageManager::IMAGE_TYPE_REPORT);
                        if (!empty($headerImagePath)) {
                            $oldHeaderImage = $defaultReportContent->getHeaderImage();
                            if (!empty($oldHeaderImage))
                                $imageManager->deleteImage($oldHeaderImage);
                            $defaultReportContent->setHeaderImage($headerImagePath);
                        }
                        $contentManager->saveDefaultReportContent($defaultReportContent, false, false);
                    }
                    $contentManager->flushAndClearCacheDefaultReportContent();
                    $this->flashMessenger()->addSuccessMessage(sprintf(MessagesConstants::SUCCESS_DEFAULT_MATCH_REPORT_CONTENT_UPDATE, $this->getMatchReportType()));
                } else
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());

            }

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toRoute($this->getRedirectRoute());

    }

}
