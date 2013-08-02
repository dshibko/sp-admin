<?php

namespace Admin\Controller;

use Admin\Form\LeagueLanguageFieldset;
use Admin\Form\SeasonLanguageFieldset;
use Application\Manager\LanguageManager;
use Application\Manager\MatchManager;
use Application\Model\Entities\League;
use Application\Model\Entities\Region;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\Entities\Season;
use \Application\Manager\ImageManager;
use \Admin\Form\SeasonRegionLanguageFieldset;
use \Application\Manager\RegionManager;
use \Admin\Form\SeasonForm;
use \Application\Manager\SeasonManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SeasonController extends LeagueController {

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
            $languages = LanguageManager::getInstance($this->getServiceLocator())->getAllLanguages(true);

            $regionFieldsets = array();

            $fakeGlobalRegion = new Region();
            $fakeGlobalRegion->setDisplayName(League::GLOBAL_TYPE);
            $fakeGlobalRegion->setIsDefault(true);
            $globalRegionLanguageFieldsets = array();
            foreach ($languages as $language) {
                $seasonLanguageFieldset = new SeasonLanguageFieldset($language, $language['isDefault']);
                if ($language['isDefault'])
                    $seasonLanguageFieldset->get('leagueDisplayName')->setValue('Global League');
                $globalRegionLanguageFieldsets [] = $seasonLanguageFieldset;
            }
            $globalFieldset = new SeasonRegionLanguageFieldset($fakeGlobalRegion->getArrayCopy(), $globalRegionLanguageFieldsets);

            foreach ($regions as $region) {
                $languageFieldsets = array();
                foreach ($languages as $language) {
                    $leagueLanguageFieldset = new LeagueLanguageFieldset($language);
                    if ($language['isDefault'])
                        $leagueLanguageFieldset->get('leagueDisplayName')->setValue($region['displayName'] . ' League');
                    $languageFieldsets [] = $leagueLanguageFieldset;
                }
                $regionFieldsets [] = new SeasonRegionLanguageFieldset($region, $languageFieldsets);
            }

            $form = new SeasonForm(array_merge(array($globalFieldset), $regionFieldsets));

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );

                $form->setData($post);
                $this->setRequiredFormFieldsets($form, $regionFieldsets);
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

                        list($displayName, $feederId, $regionalLeaguesData, $globalLeagueData, $seasonData) = $this->prepareUpdateData($form, $globalFieldset, $regionFieldsets, $imageManager);

                        $seasonManager->createSeason($displayName, $startDate, $endDate, $feederId, $regionalLeaguesData, $globalLeagueData, $seasonData);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_SEASON_CREATED);

                        return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                        return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE, array('action' => 'add'));
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

            $seasonManager = SeasonManager::getInstance($this->getServiceLocator());
            $matchManager = MatchManager::getInstance($this->getServiceLocator());

            $season = $seasonManager->getSeasonById($id);
            if ($season == null)
                return $this->redirect()->toRoute(self::SEASONS_INDEX_ROUTE);

            $editableDates = !$matchManager->getHasFinishedMatches($season);

            $regions = RegionManager::getInstance($this->getServiceLocator())->getAllRegions(true);
            $languages = LanguageManager::getInstance($this->getServiceLocator())->getAllLanguages(true);

            $regionFieldsets = array();

            $fakeGlobalRegion = new Region();
            $fakeGlobalRegion->setDisplayName(League::GLOBAL_TYPE);
            $fakeGlobalRegion->setIsDefault(true);
            $globalRegionLanguageFieldsets = array();
            foreach ($languages as $language)
                $globalRegionLanguageFieldsets [] = new SeasonLanguageFieldset($language, $language['isDefault']);
            $globalFieldset = new SeasonRegionLanguageFieldset($fakeGlobalRegion->getArrayCopy(), $globalRegionLanguageFieldsets);

            foreach ($regions as $region) {
                $languageFieldsets = array();
                foreach ($languages as $language)
                    $languageFieldsets [] = new LeagueLanguageFieldset($language);
                $regionFieldsets [] = new SeasonRegionLanguageFieldset($region, $languageFieldsets);
            }

            $form = new SeasonForm(array_merge(array($globalFieldset), $regionFieldsets));
            $form->get('submit')->setValue('Update');

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                $this->setRequiredFormFieldsets($form, $regionFieldsets);
                if (!$editableDates)
                    $form->getInputFilter()->get('dates')->setRequired(false);
                if ($form->isValid()) {
                    try {

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

                        list($displayName, $feederId, $regionalLeaguesData, $globalLeagueData, $seasonData) = $this->prepareUpdateData($form, $globalFieldset, $regionFieldsets, $imageManager);

                        $seasonManager->updateSeason($displayName, $startDate, $endDate, $feederId, $regionalLeaguesData, $globalLeagueData, $seasonData, $id);

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
                        ini_set('max_execution_time', 0);
                        ini_set('max_input_time', -1);
                        ini_set('memory_limit', -1);
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

    protected function setRequiredFormFieldsets($form, $regionFieldsets) {
        foreach($regionFieldsets as $regionFieldset)
            parent::setRequiredFormFieldsets($form, $regionFieldset->getFieldsets(), $regionFieldset->getName());
    }

    private function prepareUpdateData($form, $globalFieldset, $regionFieldsets, $imageManager) {
        $displayName = $form->get('displayName')->getValue();
        $feederId = $form->get('feederId')->getValue();

        $seasonData = array();
        $globalLeagueData = array();
        $regionalLeaguesData = array();

        $regionFieldsets = array_merge(array($globalFieldset), $regionFieldsets);

        foreach ($regionFieldsets as $regionFieldset) {
            $region = $regionFieldset->getRegion();
            foreach ($regionFieldset->getFieldsets() as $languageFieldset) {
                $language = $languageFieldset->getLanguage();
                if ($languageFieldset->has('seasonDisplayName') && $languageFieldset->has('terms')) {
                    $seasonLanguageData = array();
                    $seasonLanguageData['seasonDisplayName'] = $languageFieldset->get('seasonDisplayName')->getValue();
                    $seasonLanguageData['terms'] = $languageFieldset->get('terms')->getValue();
                    $seasonData [$language['id']] = $seasonLanguageData;
                }

                $leagueData = $this->fillInLeagueData($languageFieldset, $imageManager);

                if ($region['id'] === null) {
                    $globalLeagueData[$language['id']] = $leagueData;
                } else {
                    if (!array_key_exists($region['id'], $regionalLeaguesData))
                        $regionalLeaguesData[$region['id']] = array();
                    $regionalLeaguesData[$region['id']][$language['id']] = $leagueData;
                }
            }
        }

        return array($displayName, $feederId, $regionalLeaguesData, $globalLeagueData, $seasonData);
    }
}
