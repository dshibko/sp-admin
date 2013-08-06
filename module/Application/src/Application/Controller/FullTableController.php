<?php

namespace Application\Controller;

use \Application\Manager\SeasonManager;
use \Application\Manager\FacebookManager;
use \Application\Manager\SettingsManager;
use \Application\Model\Entities\League;
use \Zend\View\Model\ViewModel;
use \Application\Manager\LeagueManager;
use \Application\Model\DAOs\LeagueUserDAO;
use \Application\Manager\RegionManager;
use \Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class FullTableController extends AbstractActionController {

    const FULL_TABLE_ROUTE = 'full-table';
    const PER_PAGE_PLAYERS_COUNT = 20;
    const AROUND_YOU_POSITIONS_NUMBER = 10;

    public function indexAction() {

        try {

            $leagueId = (int) $this->params()->fromRoute('table', 0);
            if ($leagueId <= 0)
                return $this->notFoundAction();

            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
            $seasonManager = SeasonManager::getInstance($this->getServiceLocator());
            $facebookManager = FacebookManager::getInstance($this->getServiceLocator());

            $league = $leagueManager->getLeagueById($leagueId);
            if ($league === null)
                return $this->notFoundAction();

            $leagueUsers = array();
            if ($league->getType() != League::PRIVATE_TYPE)
                $leagueName = $leagueManager->getLeagueDisplayName($leagueId);
            else
                $leagueName = $league->getDisplayName();

            $user = $applicationManager->getCurrentUser();

            $seasonName = $seasonManager->getSeasonDisplayName($league->getSeason()->getId());

            $offset = (int) $this->params()->fromQuery('offset', 0);
            $leagueUsersCount = $leagueManager->getLeagueUsersCount($league->getId(), $league->getType());
            if ($offset < 0) $offset = 0;
            if ($leagueUsersCount > $offset) {
                $aroundYou = (boolean) $this->params()->fromQuery('aroundYou', false);
                $yourFriends = (boolean) $this->params()->fromQuery('yourFriends', false);
                if ($aroundYou) {
                    $yourPlaceInLeague = $leagueManager->getYourPlaceInLeague($league->getId(), $user->getId());
                    if ($yourPlaceInLeague > 0) {
                        if ($yourPlaceInLeague > self::AROUND_YOU_POSITIONS_NUMBER)
                            $showRowsBefore = self::AROUND_YOU_POSITIONS_NUMBER + 1;
                        else
                            $showRowsBefore = $yourPlaceInLeague;
                        $leagueUsers = $leagueManager->getLeagueTop($league->getId(), $league->getType(), $showRowsBefore, $yourPlaceInLeague - $showRowsBefore);
                        if ($yourPlaceInLeague + self::AROUND_YOU_POSITIONS_NUMBER > $leagueUsersCount)
                            $showRowsAfter = $yourPlaceInLeague + self::AROUND_YOU_POSITIONS_NUMBER - $leagueUsersCount;
                        else
                            $showRowsAfter = self::AROUND_YOU_POSITIONS_NUMBER;
                        $leagueUsers = array_merge($leagueUsers, $leagueManager->getLeagueTop($league->getId(), $league->getType(), $showRowsAfter, $yourPlaceInLeague));
                    }
                } else if ($yourFriends) {
                    $friendsFacebookIds = $facebookManager->getFriendsUsers($user);
                    $leagueUsers = $leagueManager->getLeagueTop($league->getId(), $league->getType(), 0, 0, $friendsFacebookIds);
                } else
                    $leagueUsers = $leagueManager->getLeagueTop($league->getId(), $league->getType(), self::PER_PAGE_PLAYERS_COUNT, $offset);
            }

            $onlyRows = (boolean) $this->params()->fromQuery('onlyRows', false);
            $viewModel = new ViewModel();
            $viewModel->setTerminal($onlyRows);
            return $viewModel->setVariables(array(
                'seasonName' => $seasonName,
                'leagueName' => $leagueName,
                'leagueUsers' => $leagueUsers,
                'leagueUsersCount' => $leagueUsersCount,
                'league' => $league,
                'onlyRows' => $onlyRows,
                'perPage' => self::PER_PAGE_PLAYERS_COUNT,
            ));

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

}
