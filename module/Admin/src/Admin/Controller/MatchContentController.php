<?php

namespace Admin\Controller;

use \Application\Manager\ContentManager;
use \Application\Model\Entities\DefaultReportContent;
use \Application\Manager\ImageManager;
use \Admin\Form\MatchReportContentFieldset;
use \Application\Manager\RegionManager;
use \Admin\Form\MatchReportContentForm;
use \Zend\View\Model\ViewModel;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

abstract class MatchContentController extends AbstractActionController {

    abstract protected function getRedirectRoute();

    abstract protected function getMatchReportType();

    protected function getReportContentForm($init = false) {
        // todo remove
        $regions = array(RegionManager::getInstance($this->getServiceLocator())->getDefaultRegion(true));
//        $regions = RegionManager::getInstance($this->getServiceLocator())->getAllRegions(true);
        $regionFieldsets = array();

        foreach ($regions as $region)
            $regionFieldsets [] = new MatchReportContentFieldset($region);
        $form = new MatchReportContentForm($regionFieldsets);
        if ($init) {
            $contentManager = ContentManager::getInstance($this->getServiceLocator());
            $regionsData = array();
            foreach ($regions as $region)
                $regionsData[$region['id']] = $contentManager->getDefaultReportContentByTypeAndRegion($region['id'], $this->getMatchReportType());
            $form->initForm($regionsData);
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
                    $regionManager = RegionManager::getInstance($this->getServiceLocator());
                    $contentManager = ContentManager::getInstance($this->getServiceLocator());

                    foreach ($form->getFieldsets() as $regionFieldset) {
                        $regionData = $regionFieldset->getRegion();
                        $defaultReportContent = $contentManager->getDefaultReportContentByTypeAndRegion($regionData['id'], $this->getMatchReportType());
                        if ($defaultReportContent === null) {
                            $defaultReportContent = new DefaultReportContent();
                            $defaultReportContent->setRegion($regionManager->getRegionById($regionData['id']));
                            $defaultReportContent->setReportType($this->getMatchReportType());
                        }
                        $defaultReportContent->setTitle($regionFieldset->get('title')->getValue());
                        $defaultReportContent->setIntro($regionFieldset->get('intro')->getValue());
                        $headerImage = $regionFieldset->get('headerImage');
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
