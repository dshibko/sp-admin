<?php

namespace Application\Controller;

use Application\Manager\SeasonManager;
use \Neoco\Exception\OutOfSeasonException;
use \Neoco\Exception\InfoException;
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

        try {

            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
            $seasonManager = SeasonManager::getInstance($this->getServiceLocator());

            $temporalLeagues = array();
            $regionalLeague = $regionalLeagueName = $regionalLeagueUsers = null;

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();
            if ($season == null)
                throw new OutOfSeasonException();

            $globalLeague = $applicationManager->getGlobalLeague($season);
            $globalLeagueName = $leagueManager->getLeagueDisplayName($globalLeague->getId());
            $globalLeagueUsers = $leagueManager->getLeagueTop($globalLeague->getId(), self::TOP_PLAYERS_COUNT);
            $region = $user->getCountry()->getRegion();
            if ($region != null) {
                $regionalLeague = $applicationManager->getRegionalLeague($region, $season);
                $regionalLeagueName = $leagueManager->getLeagueDisplayName($regionalLeague->getId());
                $regionalLeagueUsers = $leagueManager->getLeagueTop($regionalLeague->getId(), self::TOP_PLAYERS_COUNT);
                $temporalLeagues = $leagueManager->getTemporalLeagues($region, true);
                foreach ($temporalLeagues as &$temporalLeague) {
                    $temporalLeague['leagueUsers'] = $leagueManager->getLeagueTop($temporalLeague['id'], self::TOP_PLAYERS_COUNT);
                    $temporalLeague['displayName'] = $leagueManager->getLeagueDisplayName($temporalLeague['id']);
                }
            }
            $seasonName = $seasonManager->getSeasonDisplayName($season->getId());
            return array(
                'globalLeague' => $globalLeague,
                'globalLeagueName' => $globalLeagueName,
                'globalTopUsers' => $globalLeagueUsers,
                'regionalLeague' => $regionalLeague,
                'regionalLeagueName' => $regionalLeagueName,
                'regionalTopUsers' => $regionalLeagueUsers,
                'temporalLeagues' => $temporalLeagues,
                'seasonName' => $seasonName,
                'region' => $region,
            );

        } catch (InfoException $e) {
            return $this->infoAction($e);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

}
