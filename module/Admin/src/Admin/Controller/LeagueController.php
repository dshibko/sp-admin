<?php

namespace Admin\Controller;

use \Application\Model\Entities\League;
use \Application\Manager\ImageManager;
use \Admin\Form\MiniLeagueForm;
use \Admin\Form\LeagueRegionFieldset;
use \Application\Manager\RegionManager;
use \Application\Manager\SeasonManager;
use \Application\Manager\LeagueManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LeagueController extends AbstractActionController {

    const LEAGUES_INDEX_ROUTE = 'admin-leagues';

    public function indexAction() {

        try {

            $leagues = LeagueManager::getInstance($this->getServiceLocator())->getAllLeagues(true);
            $seasons = SeasonManager::getInstance($this->getServiceLocator())->getAllSeasons(true);

        } catch(\Exception $e) {
            $leagues = array();
            $seasons = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array(
            'leagues' => $leagues,
            'seasons' => $seasons,
        ));

    }

    public function viewTableAction() {

        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id)
                return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
            $league = $leagueManager->getLeagueById($id);

            if ($league == null)
                return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

            $leagueUsers = $leagueManager->getLeagueTop($id);

            return array(
                'leagueUsers' => $leagueUsers,
                'league' => $league,
            );

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);
        }

    }

    public function addMiniLeagueAction() {

        try {
            $regions = RegionManager::getInstance($this->getServiceLocator())->getAllRegions(true);
            $notFinishedSeasons = SeasonManager::getInstance($this->getServiceLocator())->getAllNotFinishedSeasons(true);

            $regionFieldsets = array();

            foreach ($regions as $region)
                $regionFieldsets [] = new LeagueRegionFieldset($region);

            $form = new MiniLeagueForm($regionFieldsets);

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);

                $selectedRegions = explode(",",$post['regions']);
                foreach ($regions as $region)
                    if (!in_array($region['id'], $selectedRegions))
                        $form->remove(str_replace(" ", "_", $region['displayName']));
                if ($form->isValid()) {
                    try {

                        $seasonId = $form->get('season')->getValue();
                        $dates = $form->get('dates')->getValue();
                        $startDate = array_shift(explode(" - ", $dates));
                        $startDate = \DateTime::createFromFormat('d/m/Y', $startDate);
                        $endDate = array_pop(explode(" - ", $dates));
                        $endDate = \DateTime::createFromFormat('d/m/Y', $endDate);

                        $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
                        if (!$leagueManager->checkDates($startDate, $endDate, $seasonId))
                            throw new \Exception(MessagesConstants::ERROR_LEAGUE_DATES_ARE_NOT_AVAILABLE);

                        list($displayName, $regionsData) = $this->prepareMiniLeagueUpdateData($form);
                        $leagueManager->saveMiniLeague($displayName, $seasonId, $startDate, $endDate, $regionsData);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_LEAGUE_CREATED);

                        return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
            }

            $seasonValues = array();
            foreach ($notFinishedSeasons as $notFinishedSeason)
                $seasonValues[$notFinishedSeason['id']] = $notFinishedSeason['displayName'];
            $form->get('season')->setValueOptions($seasonValues);

            return array(
                'form' => $form,
                'action' => 'add',
                'leagueId' => null,
                'regions' => $regions,
                'editableLeague' => true,
            );

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE, array('action' => 'addMiniLeague'));
        }

    }

    public function editMiniLeagueAction() {

        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE, array(
                    'action' => 'addMiniLeague'
                ));
            }

            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
            $league = $leagueManager->getLeagueById($id);

            if ($league == null)
                return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

            $today = new \DateTime();
            $today->setTime(0, 0, 0);
            $editableLeague = $today < $league->getStartDate();

            $regions = RegionManager::getInstance($this->getServiceLocator())->getAllRegions(true);

            $regionFieldsets = array();

            foreach ($regions as $region)
                $regionFieldsets [] = new LeagueRegionFieldset($region);

            $form = new MiniLeagueForm($regionFieldsets);
            $form->remove('season');

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);

                $selectedRegions = explode(",",$post['regions']);
                foreach ($regions as $region)
                    if (!in_array($region['id'], $selectedRegions))
                        $form->remove(str_replace(" ", "_", $region['displayName']));
                if (!$editableLeague)
                    $form->getInputFilter()->get('dates')->setRequired(false);
                if ($form->isValid()) {
                    try {
                        if ($editableLeague) {
                            $dates = $form->get('dates')->getValue();
                            $startDate = array_shift(explode(" - ", $dates));
                            $startDate = \DateTime::createFromFormat('d/m/Y', $startDate);
                            $endDate = array_pop(explode(" - ", $dates));
                            $endDate = \DateTime::createFromFormat('d/m/Y', $endDate);
                            if (!$leagueManager->checkDates($startDate, $endDate, $league->getSeason()->getId()))
                                throw new \Exception(MessagesConstants::ERROR_LEAGUE_DATES_ARE_NOT_AVAILABLE);
                        } else
                            $startDate = $endDate = null;

                        list($displayName, $regionsData) = $this->prepareMiniLeagueUpdateData($form);
                        $leagueManager->saveMiniLeague($displayName, $league->getSeason()->getId(), $startDate, $endDate, $regionsData, $id, $editableLeague);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_LEAGUE_UPDATED);

                        return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
            }

            $form->initForm($league);

            return array(
                'form' => $form,
                'action' => 'edit',
                'leagueId' => $id,
                'regions' => $regions,
                'editableLeague' => $editableLeague,
            );

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE, array('action' => 'editMiniLeague'));
        }

    }

    public function deleteMiniLeagueAction() {

        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id)
                return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
            $league = $leagueManager->getLeagueById($id);

            if ($league == null)
                return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

            if ($league->getType() == League::MINI_TYPE) {
                $leagueManager->deleteLeague($league);
                $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_MINI_LEAGUE_DELETE);
            }

            return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);
        }

    }

    private function prepareMiniLeagueUpdateData($form) {
        $imageManager = ImageManager::getInstance($this->getServiceLocator());
        $displayName = $form->get('displayName')->getValue();

        $regionFieldsets = $form->getFieldsets();
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
            $regionData['region'] = $regionFieldset->getRegion();

            $regionsData[$regionData['region']['id']] = $regionData;
        }

        return array($displayName, $regionsData);
    }

}
