<?php

namespace Admin\Controller;

use Admin\Form\LeagueLanguageFieldset;
use Admin\Form\LeagueRegionLanguageFieldset;
use Application\Manager\LanguageManager;
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

    public final function viewTableAction() {

        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id)
                return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
            $league = $leagueManager->getLeagueById($id);

            if ($league == null)
                return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

            $leagueUsers = $leagueManager->getLeagueTop($id, $league->getType(), 20);

            return array(
                'leagueUsers' => $leagueUsers,
                'league' => $league,
            );

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);
        }

    }

    public final function addMiniLeagueAction() {

        try {
            $regions = RegionManager::getInstance($this->getServiceLocator())->getAllRegions(true);
            $languages = LanguageManager::getInstance($this->getServiceLocator())->getAllLanguages(true);
            $notFinishedSeasons = SeasonManager::getInstance($this->getServiceLocator())->getAllNotFinishedSeasons(true);

            $languageFieldsets = array();
            foreach ($languages as $language)
                $languageFieldsets [] = new LeagueLanguageFieldset($language);

            $form = new MiniLeagueForm($languageFieldsets);

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );

                $form->setData($post);
                $this->setRequiredFormFieldsets($form, $form->getFieldsets());

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

                        list($displayName, $regionsArr, $languagesData) = $this->prepareMiniLeagueUpdateData($form);

                        $leagueManager->saveMiniLeague($displayName, $seasonId, $startDate, $endDate, $regionsArr, $languagesData);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_LEAGUE_CREATED);

                        return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                        return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE, array('action' => 'addMiniLeague'));
                    }
                } else {
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
                }
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

    public final function editMiniLeagueAction() {

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
            $languages = LanguageManager::getInstance($this->getServiceLocator())->getAllLanguages(true);

            $languageFieldsets = array();
            foreach ($languages as $language)
                $languageFieldsets [] = new LeagueLanguageFieldset($language);

            $form = new MiniLeagueForm($languageFieldsets);
            $form->remove('season');

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);

                if (!$editableLeague)
                    $form->getInputFilter()->get('dates')->setRequired(false);

                $this->setRequiredFormFieldsets($form, $form->getFieldsets());

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

                        list($displayName, $regionsArr, $languagesData) = $this->prepareMiniLeagueUpdateData($form);
                        $leagueManager->saveMiniLeague($displayName, $league->getSeason()->getId(), $startDate, $endDate, $regionsArr, $languagesData, $id, $editableLeague);

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
            return $this->redirect()->toRoute(self::LEAGUES_INDEX_ROUTE);
        }

    }

    public final function deleteMiniLeagueAction() {

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
        $regions = $form->get('regions')->getValue();
        $regionsArr = explode(",", $regions);

        $languagesData = array();
        foreach ($form->getFieldsets() as $languageFieldset) {
            $language = $languageFieldset->getLanguage();
            $leagueData = $this->fillInLeagueData($languageFieldset, $imageManager);
            $languagesData[$language['id']] = $leagueData;
        }

        return array($displayName, $regionsArr, $languagesData);
    }

    protected function fillInLeagueData($languageFieldset, $imageManager) {
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
        return $leagueData;
    }

    protected function setRequiredFormFieldsets($form, $languageFieldsets, $regionFieldsetName = null) {
        foreach($languageFieldsets as $languageFieldset) {
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
                            foreach($languageFieldsets as $aLanguageFieldset) {
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
                                    $this->setElementRequired($form, array($regionFieldsetName, $targetLanguageFieldset->getName(), $anElement->getName()));
                            }
                        break 2;
                    }
                } else {
                    if ($isDefault)
                        $this->setElementRequired($form, array($regionFieldsetName, $languageFieldset->getName(), $element->getName()));
                }

        }
    }

    protected function setElementRequired($form, array $elementKeys) {
        $filter = $form->getInputFilter();
        foreach ($elementKeys as $elementKey)
            if ($elementKey !== null && $filter->has($elementKey))
                $filter = $filter->get($elementKey);
        $filter->setRequired(true)->setAllowEmpty(false);
    }

}
