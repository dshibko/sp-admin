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

class FixturesController extends AbstractActionController
{
    const FIXTURES_LIST_ROUTE = 'admin-fixtures';

    //TODO move to manager!!!
    private function setPlayersForFieldsets(array $fieldsets)
    {
        $playerManager = PlayerManager::getInstance($this->getServiceLocator());
        $players = $playerManager->getPlayersByPositions(array(PlayerManager::DEFENDER_POSITION, PlayerManager::MIDFIELDER_POSITION, PlayerManager::FORWARD_POSITION), true);
        $playersOptions = $playerManager->getPlayersSelectOptions($players);
        $goalkeepers = $playerManager->getInstance($this->getServiceLocator())->getPlayersByPositions(array(PlayerManager::GOALKEEPER_POSITION), true);
        $goalkeepersOptions = $playerManager->getPlayersSelectOptions($goalkeepers);
        foreach($fieldsets as &$fieldset){
            $fieldset->get('featured_player')->setValueOptions($playersOptions);
            $fieldset->get('featured_goalkeeper')->setValueOptions($goalkeepersOptions);
        }
        unset($fieldset);
        return $fieldsets;
    }


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
        $regionFieldsets = $regionManager->getRegionsFieldsets('\Admin\Form\FixtureRegionFieldset');
        $regionFieldsets = $this->setPlayersForFieldsets($regionFieldsets);

        $form = new FixtureForm($regionFieldsets, $teamManager->getTeamsSelectOptions());
        try {
            $fixture = $matchManager->getMatchById($fixtureId);
            if (is_null($fixture)) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_FIXTURE);
                return $this->redirect()->toRoute(self::FIXTURES_LIST_ROUTE);
            }
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
                    $regionsData = $regionManager->getMatchRegionsFieldsetData($regionFieldsets);

                    $matchManager->save($fixture, $regionsData);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FIXTURE_SAVED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::FIXTURES_LIST_ROUTE, $params));
                } else {
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
                }
            }

            $form->initForm($fixture);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'form' => $form,
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
        $regionFieldsets = $regionManager->getRegionsFieldsets('\Admin\Form\FixtureRegionFieldset');

        $form = new FixtureForm($regionFieldsets, $teamManager->getTeamsSelectOptions());
        $regionFieldsets = $this->setPlayersForFieldsets($regionFieldsets);
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
                    $regionsData = $regionManager->getMatchRegionsFieldsetData($regionFieldsets);
                    $matchManager->save($fixture, $regionsData);
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
