<?php

namespace Admin\Controller;

use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\MatchManager;
use \Admin\Form\FixtureForm;
use \Application\Manager\SeasonManager;
use \Application\Manager\CompetitionManager;
use \Application\Manager\ApplicationManager;
use \Application\Manager\TeamManager;
use \Application\Model\Entities\Match;
use \Application\Manager\UserManager;
use \Application\Manager\ExportManager;
use \Application\Manager\RegionManager;
use \Application\Manager\ImageManager;
use \Application\Manager\PlayerManager;
use \Admin\Form\FeaturedPlayerForm;
use \Admin\Form\FeaturedGoalkeeperForm;
use \Admin\Form\FeaturedPredictionForm;
use \Admin\Form\MatchReportForm;


class FixturesController extends AbstractActionController
{
    const FIXTURES_LIST_ROUTE = 'admin-fixtures';
    const FIXTURE_FORM_TYPE = 'fixture';
    const FEATURED_PLAYER_FORM_TYPE = 'featured_player';
    const FEATURED_GOALKEEPER_FORM_TYPE = 'featured_goalkeeper';
    const FEATURED_PREDICTION_FORM_TYPE = 'featured_prediction';
    const MATCH_REPORT_FORM_TYPE = 'match_report';

    public function indexAction()
    {
        $matchManager = MatchManager::getInstance($this->getServiceLocator());
        $seasonsManager = SeasonManager::getInstance($this->getServiceLocator());
        $competitionManager = CompetitionManager::getInstance($this->getServiceLocator());

        try {
            $fixtures = $matchManager->getAllMatches();
            $seasons = $seasonsManager->getAllSeasonsByFields(array('id', 'displayName'), true);
            $currentSeason = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
            $currentSeasonId = (!is_null($currentSeason) && $currentSeason instanceof \Application\Model\Entities\Season) ? $currentSeason->getId() : null;
            $competitions = $competitionManager->getAllCompetitionsByFields(array('displayName'), true);
        } catch (\Exception $e) {
            $fixtures = array();
            $seasons = array();
            $competitions = array();
            $currentSeasonId = null;
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'fixtures' => $fixtures,
            'seasons' => $seasons,
            'competitions' => $competitions,
            'currentSeasonId' => $currentSeasonId
        );
    }
    //TODO move to manager
    private function setRequiredFormFieldsets($form){
        $requiredGroup = false;
        foreach($form->getFieldsets() as $fieldset){
            foreach($fieldset->getElements() as $element){
                $value = $element->getValue();
                //Check image value
                if ($element->getAttribute('isImage')){
                    if (!$value['stored'] && $value['error'] == UPLOAD_ERR_NO_FILE){
                        $value = false;
                    }
                }
                if (!empty($value)){
                    $requiredGroup = true;
                    break 2;
                }
            }
        }

        if ($requiredGroup){
            foreach($form->getFieldsets() as $fieldset){
                foreach($fieldset->getElements() as $element){
                    if ($element->getAttribute('isImage')){
                        $value = $element->getValue();
                        if ($value ['stored'] == 1){
                            continue;
                        }
                    }
                    $form->getInputFilter()
                        ->get($fieldset->getName())
                        ->get($element->getName())
                        ->setRequired(true)->setAllowEmpty(false);
                }
            }
        }
    }
    public function editAction()
    {
        $fixtureId = (string)$this->params()->fromRoute('fixture', '');
        if (empty($fixtureId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_FIXTURE_ID);
            return $this->redirect()->toRoute(self::FIXTURES_LIST_ROUTE);
        }
        $params = array();
        $analytics = array();
        $isBlocked = 0;
        $isFullTime = false;
        $matchManager = MatchManager::getInstance($this->serviceLocator);
        $teamManager = TeamManager::getInstance($this->getServiceLocator());
        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        try {
            $fixture = $matchManager->getMatchById($fixtureId);
            if (is_null($fixture)) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_FIXTURE);
                return $this->redirect()->toRoute(self::FIXTURES_LIST_ROUTE);
            }
            $teamIds = array($fixture->getHomeTeam()->getId(), $fixture->getAwayTeam());
            $playerPositions = array(PlayerManager::DEFENDER_POSITION, PlayerManager::MIDFIELDER_POSITION, PlayerManager::FORWARD_POSITION);
            $goalkeeperPositions = array(PlayerManager::GOALKEEPER_POSITION);
            $featuredPlayerRegions = $regionManager->getRegionsFieldsets('\Admin\Form\FeaturedPlayerFieldset');

            $featuredPlayerRegions = $matchManager->getFieldsetWithPlayers($featuredPlayerRegions,$teamIds , $playerPositions, 'featured_player');

            $featuredGoalkeeperRegions = $regionManager->getRegionsFieldsets('\Admin\Form\FeaturedGoalkeeperFieldset');
            $featuredGoalkeeperRegions = $matchManager->getFieldsetWithPlayers($featuredGoalkeeperRegions,$teamIds , $goalkeeperPositions, 'featured_goalkeeper');

            $featuredPredictionRegions = $regionManager->getRegionsFieldsets('\Admin\Form\FeaturedPredictionFieldset');

            $matchReportRegions = $regionManager->getRegionsFieldsets('\Admin\Form\MatchReportFieldset');

            $form = new FixtureForm($teamManager->getTeamsSelectOptions(), self::FIXTURE_FORM_TYPE);
            $featuredPlayerForm = new FeaturedPlayerForm($featuredPlayerRegions, self::FEATURED_PLAYER_FORM_TYPE);
            $featuredGoalkeeperForm = new FeaturedGoalkeeperForm($featuredGoalkeeperRegions,self::FEATURED_GOALKEEPER_FORM_TYPE);
            $featuredPredictionForm = new FeaturedPredictionForm($featuredPredictionRegions, self::FEATURED_PREDICTION_FORM_TYPE);
            $matchReportForm = new MatchReportForm($matchReportRegions,self::MATCH_REPORT_FORM_TYPE);


            $params = array(
                'fixture' => $fixture->getId(),
                'action' => 'edit'
            );

            $isBlocked = (bool)$fixture->getIsBlocked();
            $request = $this->getRequest();
            //Get match analytics
            $analytics = $matchManager->getMatchAnalytics($fixture);
            //Check full time
            if ($fixture->getStatus() == Match::FULL_TIME_STATUS) {
                $isFullTime = true;
            }
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $type = $post['type'];
                switch($type){
                    case self::FIXTURE_FORM_TYPE : {
                        $form->setData($post);
                        $form->getInputFilter()->get('competition')->setRequired(false);
                        if ($form->isValid()) {
                            $data = $form->getData();

                            $startTime = $data['date'] . $data['kick_off_time'];
                            $isChangedDate = strtotime($startTime) != $fixture->getStartTime()->getTimestamp();
                            $isChangedHomeTeam = $fixture->getHomeTeam()->getId() != $data['homeTeam'];
                            $isChangedAwayTeam = $fixture->getAwayTeam()->getId() != $data['awayTeam'];

                            //Check changed data
                            if (!$fixture->getIsBlocked()) {
                                if ($isChangedHomeTeam || $isChangedAwayTeam || $isChangedDate) {
                                    $fixture->setIsBlocked(true);
                                }
                            }
                            if ($isChangedAwayTeam) {
                                $fixture->setAwayTeam($teamManager->getTeamById($data['awayTeam']));
                            }
                            if ($isChangedHomeTeam) {
                                $fixture->setHomeTeam($teamManager->getTeamById($data['homeTeam']));
                            }
                            if ($isChangedDate) {
                                $fixture->setStartTime(new \DateTime($startTime));
                            }
                            $fixture->setIsDoublePoints(!empty($data['isDoublePoints']));

                            $matchManager->save($fixture);
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FIXTURE_SAVED);
                            return $this->redirect()->toUrl($this->url()->fromRoute(self::FIXTURES_LIST_ROUTE, $params));
                        } else {
                            $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
                        }
                        break;
                    }
                    case self::FEATURED_PLAYER_FORM_TYPE : {
                        $featuredPlayerForm->setData($post);
                        $this->setRequiredFormFieldsets($featuredPlayerForm);
                        if ($featuredPlayerForm->isValid()){
                            $regionsData = $regionManager->getFeaturedPlayerRegionsData($featuredPlayerRegions);
                            $matchManager->save($fixture,$regionsData);
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FIXTURE_SAVED);
                            return $this->redirect()->toUrl($this->url()->fromRoute(self::FIXTURES_LIST_ROUTE, $params));
                        }else{
                            $featuredPlayerForm->handleErrorMessages($featuredPlayerForm->getMessages(), $this->flashMessenger());
                        }
                        break;
                    }
                    case self::FEATURED_GOALKEEPER_FORM_TYPE : {
                        $featuredGoalkeeperForm->setData($post);
                        $this->setRequiredFormFieldsets($featuredGoalkeeperForm);
                        if ($featuredGoalkeeperForm->isValid()){
                            $regionsData = $regionManager->getFeaturedGoalkeeperRegionsData($featuredGoalkeeperRegions);
                            $matchManager->save($fixture,$regionsData);
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FIXTURE_SAVED);
                            return $this->redirect()->toUrl($this->url()->fromRoute(self::FIXTURES_LIST_ROUTE, $params));
                        }else{
                            $featuredGoalkeeperForm->handleErrorMessages($featuredGoalkeeperForm->getMessages(), $this->flashMessenger());
                        }
                        break;
                    }
                    case self::FEATURED_PREDICTION_FORM_TYPE : {
                        $featuredPredictionForm->setData($post);
                        $this->setRequiredFormFieldsets($featuredPredictionForm);
                        if ($featuredPredictionForm->isValid()){
                            $regionsData = $regionManager->getFeaturedPredictionRegionsData($featuredPredictionRegions);
                            $matchManager->save($fixture,$regionsData);
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FIXTURE_SAVED);
                            return $this->redirect()->toUrl($this->url()->fromRoute(self::FIXTURES_LIST_ROUTE, $params));
                        }else{
                            $featuredPredictionForm->handleErrorMessages($featuredPredictionForm->getMessages(), $this->flashMessenger());
                        }
                        break;
                    }
                    case self::MATCH_REPORT_FORM_TYPE :{
                        $matchReportForm->setData($post);
                        $this->setRequiredFormFieldsets($matchReportForm);
                        if ($matchReportForm->isValid()){
                            $regionsData = $regionManager->getMatchReportRegionsData($matchReportRegions);
                            $matchManager->save($fixture,$regionsData);
                            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FIXTURE_SAVED);
                            return $this->redirect()->toUrl($this->url()->fromRoute(self::FIXTURES_LIST_ROUTE, $params));
                        }else{
                            $matchReportForm->handleErrorMessages($matchReportForm->getMessages(), $this->flashMessenger());
                        }
                        break;
                    }
                    default : {
                        throw new \Exception(MessagesConstants::ERROR_INVALID_FORM_TYPE);
                    }
                }
            }

            $form->initForm($fixture);
            $featuredPlayerForm->initForm($fixture);
            $featuredGoalkeeperForm->initForm($fixture);
            $featuredPredictionForm->initForm($fixture);
            $matchReportForm->initForm($fixture);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'form' => $form,
            'featuredPlayerForm' => $featuredPlayerForm,
            'featuredGoalkeeperForm' => $featuredGoalkeeperForm,
            'featuredPredictionForm' => $featuredPredictionForm,
            'matchReportForm' => $matchReportForm,
            'params' => $params,
            'title' => 'Edit Fixture',
            'isBlocked' => $isBlocked,
            'analytics' => $analytics,
            'isFullTime' => $isFullTime
        );
    }

    public function addAction()
    {
        $matchManager = MatchManager::getInstance($this->serviceLocator);
        $teamManager = TeamManager::getInstance($this->getServiceLocator());
        $seasonManager = SeasonManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        $form = new FixtureForm($teamManager->getTeamsSelectOptions(), self::FIXTURE_FORM_TYPE);
        try {
            $params = array(
                'action' => 'add'
            );
            $seasons = $seasonManager->getCurrentAndFutureSeasons(true);
            $currentSeason = $applicationManager->getCurrentSeason();
            $currentSeasonId = (!is_null($currentSeason) && $currentSeason instanceof \Application\Model\Entities\Season) ? $currentSeason->getId() : null;
            $form->get('submit')->setValue('Create');
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()) {
                    $data = $form->getData();
                    $startTime = $data['date'] . $data['kick_off_time'];
                    $fixture = new Match();
                    $dateTime = new \DateTime($startTime);
                    $fixture->setIsDoublePoints(!empty($data['isDoublePoints']))
                        ->setAwayTeam($teamManager->getTeamById($data['awayTeam']))
                        ->setHomeTeam($teamManager->getTeamById($data['homeTeam']))
                        ->setStartTime($dateTime)
                        ->setCompetition(CompetitionManager::getInstance($this->getServiceLocator())->getCompetitionById($data['competition']));
                    $matchManager->save($fixture);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FIXTURE_SAVED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::FIXTURES_LIST_ROUTE, array('action' => 'edit', 'fixture' => $fixture->getId())));
                } else {
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
                }
            }
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toUrl($this->url()->fromRoute(self::FIXTURES_LIST_ROUTE));
        }

        return array(
            'form' => $form,
            'params' => $params,
            'isBlocked' => 1,
            'title' => 'Add Fixture',
            'seasons' => $seasons,
            'currentSeasonId' => $currentSeasonId
        );
    }

    public function syncWithFeedAction()
    {
        $fixtureId = (string)$this->params()->fromRoute('fixture', '');
        if (empty($fixtureId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_FIXTURE_ID);
            return $this->redirect()->toRoute(self::FIXTURES_LIST_ROUTE);
        }
        $params = array();
        $matchManager = MatchManager::getInstance($this->serviceLocator);
        try {
            $fixture = $matchManager->getMatchById($fixtureId);
            if (is_null($fixture)) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_FIXTURE);
                return $this->redirect()->toRoute(self::FIXTURES_LIST_ROUTE);
            }
            $fixture->setIsBlocked(false);
            $matchManager->save($fixture);
            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_SYNC_WITH_FEED);
            $params = array(
                'action' => 'edit',
                'fixture' => $fixture->getId()
            );
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toUrl($this->url()->fromRoute(self::FIXTURES_LIST_ROUTE, $params));
    }

    public function getUserDataAction()
    {
        $userId = (string)$this->params()->fromRoute('fixture', '');
        $userManager = UserManager::getInstance($this->getServiceLocator());
        $exportManager = ExportManager::getInstance($this->getServiceLocator());

        if (empty($userId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_USER_ID);
            return $this->redirect()->toRoute(self::FIXTURES_LIST_ROUTE);
        }
        try {
            $user = $userManager->getUserById($userId);
            if (is_null($user)) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_USER);
                return $this->redirect()->toRoute(self::FIXTURES_LIST_ROUTE);
            }
            $data = array(
                array(
                    'id' => $user->getId(),
                    'displayName' => $user->getDisplayName()
                )
            );

            $content = $exportManager->exportArrayToCSV($data, array('id' => 'number', 'displayName' => 'string'));
            $response = $this->getResponse();
            $headers = $response->getHeaders();
            $headers->addHeaderLine('Content-Type', 'text/csv');
            $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"user_export.csv\"");
            $headers->addHeaderLine('Accept-Ranges', 'bytes');
            $headers->addHeaderLine('Content-Length', strlen($content));

            $response->setContent($content);

            return $response;
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::FIXTURES_LIST_ROUTE);
        }
    }

}
