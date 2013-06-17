<?php

namespace Admin\Controller;

use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\Entities\Season;
use \Application\Manager\ImageManager;
use \Admin\Form\SeasonRegionFieldset;
use \Application\Manager\RegionManager;
use \Admin\Form\SeasonForm;
use \Application\Manager\SeasonManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SeasonController extends AbstractActionController {

    const SEASONS_INDEX_ROUTE = 'admin-seasons';

    public function indexAction() {

        $seasons = array();

        try {

            $seasons = SeasonManager::getInstance($this->getServiceLocator())->getAllSeasons();

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array(
            'seasons' => $seasons
        ));

    }

    public function addAction() {

        try {
            $regions = RegionManager::getInstance($this->getServiceLocator())->getAllRegions(true);

            $regionFieldsets = array();

            foreach ($regions as $region)
                $regionFieldsets [] = new SeasonRegionFieldset($region);

            $form = new SeasonForm($regionFieldsets);

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()) {
                    try {

                        $dates = $form->get('dates')->getValue();
                        $startDate = array_shift(explode(" - ", $dates));
                        $startDate = \DateTime::createFromFormat('d/m/Y', $startDate);
                        $endDate = array_pop(explode(" - ", $dates));
                        $endDate = \DateTime::createFromFormat('d/m/Y', $endDate);

                        $seasonManager = SeasonManager::getInstance($this->getServiceLocator());
                        if (!$seasonManager->checkDates($startDate, $endDate))
                            throw new \Exception(MessagesConstants::ERROR_SEASON_DATES_ARE_NOT_AVAILABLE);

                        $imageManager = ImageManager::getInstance($this->getServiceLocator());

                        list($displayName, $feederId, $regionsData) = $this->prepareUpdateData($form, $regionFieldsets, $imageManager);

                        $seasonManager->createSeason($displayName, $startDate, $endDate, $feederId, $regionsData);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_SEASON_CREATED);

                        return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
            }

            return array(
                'form' => $form,
                'action' => 'add',
                'editableDates' => true,
            );

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE, array('action' => 'add'));
        }

    }

    public function editAction() {
        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE, array(
                    'action' => 'add'
                ));
            }

            $season = SeasonManager::getInstance($this->getServiceLocator())->getSeasonById($id);
            if ($season == null)
                return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);

            $today = new \DateTime();
            $today->setTime(0, 0, 0);
            $editableDates = $today < $season->getStartDate();

            $regions = RegionManager::getInstance($this->getServiceLocator())->getAllRegions(true);

            $regionFieldsets = array();

            foreach ($regions as $region)
                $regionFieldsets [] = new SeasonRegionFieldset($region);

            $form = new SeasonForm($regionFieldsets);
            $form->get('submit')->setValue('Update');

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if (!$editableDates)
                    $form->getInputFilter()->get('dates')->setRequired(false);
                if ($form->isValid()) {
                    try {

                        $seasonManager = SeasonManager::getInstance($this->getServiceLocator());
                        if ($editableDates) {
                            $dates = $form->get('dates')->getValue();
                            $startDate = array_shift(explode(" - ", $dates));
                            $startDate = \DateTime::createFromFormat('d/m/Y', $startDate);
                            $endDate = array_pop(explode(" - ", $dates));
                            $endDate = \DateTime::createFromFormat('d/m/Y', $endDate);

                            if (!$seasonManager->checkDates($startDate, $endDate, $id))
                                throw new \Exception(MessagesConstants::ERROR_SEASON_DATES_ARE_NOT_AVAILABLE);
                        } else
                            $startDate = $endDate = null;

                        $imageManager = ImageManager::getInstance($this->getServiceLocator());

                        list($displayName, $feederId, $regionsData) = $this->prepareUpdateData($form, $regionFieldsets, $imageManager);

                        $seasonManager->updateSeason($displayName, $startDate, $endDate, $feederId, $regionsData, $id);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_SEASON_UPDATED);

                        return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
            }

            $form->initForm($season);

            return array(
                'id' => $id,
                'form' => $form,
                'action' => 'edit',
                'editableDates' => $editableDates,
            );

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);
        }
    }

    public function deleteAction() {

        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            if ($id === 0)
                return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);

            $request = $this->getRequest();

            if ($request->isPost()) {
                try {
                    $del = $request->getPost('del', 'No');

                    if ($del == 'Yes') {
                        $id = (int) $request->getPost('id');
                        SeasonManager::getInstance($this->getServiceLocator())->deleteSeason($id);
                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_SEASON_DELETE);
                    }
                } catch (\Exception $e) {
                    ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
                }
                return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);
            }

            $season = SeasonManager::getInstance($this->getServiceLocator())->getSeasonById($id, true);
            if (empty($season))
                return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);

            return array(
                'id'    => $id,
                'season' => $season
            );
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);
        }
    }

    private function prepareUpdateData($form, $regionFieldsets, $imageManager) {
        $displayName = $form->get('displayName')->getValue();
        $feederId = $form->get('feederId')->getValue();

        $regionsData = array();
        foreach ($regionFieldsets as $regionFieldset) {
            $regionData = array();

            $regionData['displayName'] = $regionFieldset->get('displayName')->getValue();
            $prizeImage = $regionFieldset->get('prizeImage');
            $prizeImagePath = $imageManager->saveUploadedImage($prizeImage, ImageManager::IMAGE_TYPE_LEAGUE);
            $regionData['prizeImagePath'] = $prizeImagePath;
            $regionData['prizeTitle'] = $regionFieldset->get('prizeTitle')->getValue();
            $regionData['prizeDescription'] = $regionFieldset->get('prizeDescription')->getValue();

            $postWinImage = $regionFieldset->get('postWinImage');
            $postWinImagePath = $imageManager->saveUploadedImage($postWinImage, ImageManager::IMAGE_TYPE_LEAGUE);
            $regionData['postWinImagePath'] = $postWinImagePath;
            $regionData['postWinTitle'] = $regionFieldset->get('postWinTitle')->getValue();
            $regionData['postWinDescription'] = $regionFieldset->get('postWinDescription')->getValue();
            $regionData['terms'] = $regionFieldset->get('terms')->getValue();
            $regionData['region'] = $regionFieldset->getRegion();

            $regionsData[$regionData['region']['id']] = $regionData;
        }

        return array($displayName, $feederId, $regionsData);
    }
}
