<?php

namespace Application\Controller;

use \Application\Manager\LeagueManager;
use \Application\Model\DAOs\LeagueUserDAO;
use \Application\Manager\RegionManager;
use \Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class TablesController extends AbstractActionController {

    const TABLES_ROUTE = 'tables';
    const TOP_PLAYERS_COUNT = 4;

    public function indexAction() {

        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $leagueManager = LeagueManager::getInstance($this->getServiceLocator());

        $globalLeagueUsers = $temporalLeagues = array();
        $globalLeague = $regionalLeague = $regionalLeagueUsers = $region = null;

        try {

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();
            $globalLeague = $applicationManager->getGlobalLeague($season);
            $globalLeagueUsers = $leagueManager->getLeagueTop($globalLeague->getId(), self::TOP_PLAYERS_COUNT);
            $region = $user->getCountry()->getRegion();
            if ($region != null) {
                $regionalLeague = $applicationManager->getRegionalLeague($region, $season);
                $regionalLeagueUsers = $leagueManager->getLeagueTop($regionalLeague->getId(), self::TOP_PLAYERS_COUNT);
                $temporalLeagues = $leagueManager->getTemporalLeagues($region, true);
                foreach ($temporalLeagues as &$temporalLeague)
                    $temporalLeague['leagueUsers'] = $leagueManager->getLeagueTop($temporalLeague['id'], self::TOP_PLAYERS_COUNT);
            }
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'globalLeague' => $globalLeague,
            'globalTopUsers' => $globalLeagueUsers,
            'regionalLeague' => $regionalLeague,
            'regionalTopUsers' => $regionalLeagueUsers,
            'temporalLeagues' => $temporalLeagues,
            'region' => $region,
        );
    }

}
