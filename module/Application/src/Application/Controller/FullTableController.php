<?php

namespace Application\Controller;

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

        $leagueId = (int) $this->params()->fromRoute('table', 0);
        if ($leagueId <= 0)
            return $this->notFoundAction();

        $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        $facebookManager = FacebookManager::getInstance($this->getServiceLocator());
        $translator = $this->getServiceLocator()->get('translator');

        $leagueUsers = array();
        $leagueUsersCount = 0;
        $seasonName = $leagueName = '';

        try {

            $user = $applicationManager->getCurrentUser();

            $league = $leagueManager->getLeagueById($leagueId, true);
            if ($league === null)
                return $this->notFoundAction();

            switch ($league['type']) {
                case League::GLOBAL_TYPE:
                    $leagueName = $translator->translate($league['type']);
                    break;
                case League::REGIONAL_TYPE:
                    $regionId = $league['leagueRegions'][0]['regionId'];
                    $region = $regionManager->getRegionById($regionId, true);
                    $leagueName = $region['displayName'];
                    break;
                case League::MINI_TYPE:
                    $region = $user->getCountry()->getRegion();
                    if ($region == null) return $this->notFoundAction();
                    foreach ($league['leagueRegions'] as $leagueRegion)
                        if ($leagueRegion['regionId'] == $region->getId()) {
                            $leagueName = $leagueRegion['displayName'];
                            break;
                        }
                    break;
            }

            $seasonName = $league['season']['displayName'];

            $offset = (int) $this->params()->fromQuery('offset', 0);
            $leagueUsersCount = $leagueManager->getLeagueUsersCount($league['id']);
            if ($offset < 0) $offset = 0;
            if ($leagueUsersCount > $offset) {
                $aroundYou = (boolean) $this->params()->fromQuery('aroundYou', false);
                $yourFriends = (boolean) $this->params()->fromQuery('yourFriends', false);
                if ($aroundYou) {
                    $yourPlaceInLeague = $leagueManager->getYourPlaceInLeague($league['id'], $user->getId());
                    if ($yourPlaceInLeague > 0) {
                        if ($yourPlaceInLeague > self::AROUND_YOU_POSITIONS_NUMBER)
                            $showRowsBefore = self::AROUND_YOU_POSITIONS_NUMBER + 1;
                        else
                            $showRowsBefore = $yourPlaceInLeague;
                        $leagueUsers = $leagueManager->getLeagueTop($league['id'], $showRowsBefore, $yourPlaceInLeague - $showRowsBefore);
                        if ($yourPlaceInLeague + self::AROUND_YOU_POSITIONS_NUMBER > $leagueUsersCount)
                            $showRowsAfter = $yourPlaceInLeague + self::AROUND_YOU_POSITIONS_NUMBER - $leagueUsersCount;
                        else
                            $showRowsAfter = self::AROUND_YOU_POSITIONS_NUMBER;
                        $leagueUsers = array_merge($leagueUsers, $leagueManager->getLeagueTop($league['id'], $showRowsAfter, $yourPlaceInLeague));
                    }
                } else if ($yourFriends) {
                    $friendsFacebookIds = $facebookManager->getFriendsUsers($user);
                    $leagueUsers = $leagueManager->getLeagueTop($league['id'], 0, 0, $friendsFacebookIds);
                } else
                    $leagueUsers = $leagueManager->getLeagueTop($league['id'], self::PER_PAGE_PLAYERS_COUNT, $offset);
            }

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        $onlyRows = (boolean) $this->params()->fromQuery('onlyRows', false);
        $viewModel = new ViewModel();
        $viewModel->setTerminal($onlyRows);
        return $viewModel->setVariables(array(
            'seasonName' => $seasonName,
            'leagueName' => $leagueName,
            'leagueUsers' => $leagueUsers,
            'leagueUsersCount' => $leagueUsersCount,
            'onlyRows' => $onlyRows,
            'perPage' => self::PER_PAGE_PLAYERS_COUNT,
        ));

    }

}
