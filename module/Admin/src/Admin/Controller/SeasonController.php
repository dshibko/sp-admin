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

    private function setRequiredFormFieldsets($form, $regionFieldsets) {
        foreach($regionFieldsets as $regionFieldset) {
            foreach($regionFieldset->getFieldsets() as $languageFieldset) {
                $language = $languageFieldset->getLanguage();
                $isDefault = $language['isDefault'];
                foreach($languageFieldset->getElements() as $element)
                    if ($element->getName() != 'leagueDisplayName') {

                        $value = $element->getValue();

                        //Check image value
                        if ($element->getAttribute('isImage') &&
                            !$value['stored'] && $value['error'] == UPLOAD_ERR_NO_FILE)
                                $value = false;

                        if (!empty($value)) {
                            if ($isDefault)
                                $targetLanguageFieldset = $languageFieldset;
                            else {
                                foreach($regionFieldset->getFieldsets() as $aLanguageFieldset) {
                                    $aLanguage = $aLanguageFieldset->getLanguage();
                                    if ($aLanguage['isDefault']) {
                                        $targetLanguageFieldset = $aLanguageFieldset;
                                        break;
                                    }
                                }
                            }
                            if (isset($targetLanguageFieldset))
                                foreach($targetLanguageFieldset->getElements() as $anElement) {
                                    $aValue = $anElement->getValue();
                                    if (!$anElement->getAttribute('isImage') || (!$aValue['stored'] && $anElement->getAttribute('isImage')))
                                        $this->setElementRequired($form, $regionFieldset->getName(), $languageFieldset->getName(), $anElement->getName());
                                }
                            break 2;
                        }
                    } else {
                        if ($isDefault)
                            $this->setElementRequired($form, $regionFieldset->getName(), $languageFieldset->getName(), $element->getName());
                    }

            }
        }
    }

    private function setElementRequired($form, $regionName, $languageName, $elementName) {
        $form->getInputFilter()
            ->get($regionName)
            ->get($languageName)
            ->get($elementName)
            ->setRequired(true)->setAllowEmpty(false);
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

                $leagueData = array();
                $leagueData['leagueDisplayName'] = $languageFieldset->get('leagueDisplayName')->getValue();
                $prizeImage = $languageFieldset->get('prizeImage');
                $prizeImagePath = $imageManager->saveUploadedImage($prizeImage, ImageManager::IMAGE_TYPE_PRIZES);
                $leagueData['prizeImagePath'] = $prizeImagePath;
                $leagueData['prizeTitle'] = $languageFieldset->get('prizeTitle')->getValue();
                $leagueData['prizeDescription'] = $languageFieldset->get('prizeDescription')->getValue();

                $postWinImage = $languageFieldset->get('postWinImage');
                $postWinImagePath = $imageManager->saveUploadedImage($postWinImage, ImageManager::IMAGE_TYPE_PRIZES);
                $leagueData['postWinImagePath'] = $postWinImagePath;
                $leagueData['postWinTitle'] = $languageFieldset->get('postWinTitle')->getValue();
                $leagueData['postWinDescription'] = $languageFieldset->get('postWinDescription')->getValue();
                $leagueData['region'] = $region;

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
