<?php

namespace Application\Manager;

use \Application\Model\Entities\LeagueUser;
use \Doctrine\Common\Collections\ArrayCollection;
use \Application\Model\Entities\LeagueRegion;
use \Application\Model\Entities\Prize;
use \Application\Model\DAOs\RegionDAO;
use \Application\Model\Entities\League;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\DAOs\LeagueUserDAO;
use \Application\Model\DAOs\SeasonDAO;
use \Application\Model\DAOs\LeagueDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class LeagueManager extends BasicManager {

    /**
     * @var LeagueManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LeagueManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LeagueManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function recalculateLeaguesTables() {
        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();

        if ($season != null) {

            $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
            $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
            $now = new \DateTime();
            foreach ($season->getLeagues() as $league)
                if ($league->getType() != League::MINI_TYPE || $league->getIsActive($now)) {
                    $usersData = $leagueDAO->getLeagueUsersScores($league);

                    foreach ($usersData as $i => $userRow) {
                        $userRow['accuracy'] = $userRow['correct_results'] / $userRow['predictions_count'] + $userRow['correct_scores'] / $userRow['predictions_count'];
                        $divider = 2;
                        if ($userRow['predictions_players_count'] > 0) {
                            $userRow['accuracy'] += $userRow['correct_scorers'] / $userRow['predictions_players_count'] + $userRow['correct_scorers_order'] / $userRow['predictions_players_count'];
                            $divider = 4;
                        }
                        $userRow['accuracy'] /= $divider;
                        $userRow['date'] = new \DateTime($userRow['date']);
                        $usersData[$i] = $userRow;
                    }

                    usort($usersData, function($u2, $u1) {
                        $res = $u1['points'] != $u2['points'] ? $u1['points'] - $u2['points'] :
                            (floor(100 * $u1['accuracy']) != floor(100 * $u2['accuracy']) ? $u1['accuracy'] - $u2['accuracy'] :
                            ($u1['predictions_count'] != $u2['predictions_count'] ? $u1['predictions_count'] - $u2['predictions_count'] :
                            ($u1['correct_results'] != $u2['correct_results'] ? $u1['correct_results'] - $u2['correct_results'] :
                            ($u1['correct_scores'] != $u2['correct_scores'] ? $u1['correct_scores'] - $u2['correct_scores'] :
                            ($u1['correct_scorers'] != $u2['correct_scorers'] ? $u1['correct_scorers'] - $u2['correct_scorers'] :
                            ($u1['date']->getTimestamp() != $u2['date']->getTimestamp() ? $u1['date']->getTimestamp() - $u2['date']->getTimestamp() : 1))))));
                        return (int)($res/abs($res));
                    });

                    foreach ($usersData as $i => $userRow) {
                        $leagueUser = $leagueUserDAO->findOneById($userRow['id']);
                        $leagueUser->setPreviousPlace($leagueUser->getPlace());
                        $leagueUser->setPlace($i + 1);
                        $leagueUser->setPoints($userRow['points']);
                        $leagueUser->setAccuracy(floor(100 * $userRow['accuracy']));
                        $leagueUserDAO->save($leagueUser, false, false);
                    }
                    $leagueUserDAO->flush();
                    $leagueUserDAO->clearCache();

                }

        } else
            throw new \Exception(MessagesConstants::INFO_OUT_OF_SEASON);

    }

    public function getAllLeagues($hydrate = false, $skipCache = false) {
        return LeagueDAO::getInstance($this->getServiceLocator())->getAllLeagues($hydrate, $skipCache);
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param int $seasonId
     * @return bool
     */
    public function checkDates($startDate, $endDate, $seasonId) {
        return LeagueDAO::getInstance($this->getServiceLocator())->checkLeagueDatesInterval($startDate, $endDate, $seasonId);
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return League
     */
    public function getLeagueById($id, $hydrate = false, $skipCache = false) {
        return LeagueDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    public function saveMiniLeague($displayName, $seasonId, $startDate, $endDate, $regionsData, $leagueId = -1) {
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        if ($leagueId == -1) {
            $league = new League();
            $season = SeasonDAO::getInstance($this->getServiceLocator())->findOneById($seasonId);
            $league->setSeason($season);
            $league->setCreationDate(new \DateTime());
            $league->setCreator(ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser());
        } else
            $league = $leagueDAO->findOneById($leagueId);
        $league->setStartDate($startDate);
        $league->setEndDate($endDate);
        $league->setType(League::MINI_TYPE);
        $league->setDisplayName($displayName);
        $regionDAO = RegionDAO::getInstance($this->getServiceLocator());
        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        $prizes = array();
        $leagueRegions = array();
        $leagueUsers = array();
        foreach ($regionsData as $regionId => $regionRow) {
            $region = $regionDAO->findOneById($regionId);
            if ($region != null) {
                $prize = new Prize();
                if (empty($regionRow['prizeImagePath']))
                    $regionRow['prizeImagePath'] = $league->getPrizeByRegionId($regionId)->getPrizeImage();
                $prize->setPrizeImage($regionRow['prizeImagePath']);
                $prize->setPrizeDescription($regionRow['prizeDescription']);
                $prize->setPrizeTitle($regionRow['prizeTitle']);
                if (empty($regionRow['postWinImagePath']))
                    $regionRow['postWinImagePath'] = $league->getPrizeByRegionId($regionId)->getPostWinImage();
                $prize->setPostWinImage($regionRow['postWinImagePath']);
                $prize->setPostWinDescription($regionRow['postWinDescription']);
                $prize->setPostWinTitle($regionRow['postWinTitle']);
                $prize->setLeague($league);
                $prize->setRegion($region);
                $prizes [] = $prize;
                $leagueRegion = new LeagueRegion();
                $leagueRegion->setDisplayName($regionRow['displayName']);
                $leagueRegion->setLeague($league);
                $leagueRegion->setRegion($region);
                $leagueRegions [] = $leagueRegion;
                $users = $regionManager->getUsers($regionId);
                foreach ($users as $user) {
                    $leagueUser = new LeagueUser();
                    $leagueUser->setUser($user);
                    $leagueUser->setJoinDate(new \DateTime());
                    $leagueUser->setLeague($league);
                    $leagueUsers [] = $leagueUser;
                }
            }
        }
        $league->getPrizes()->clear();
        $league->setPrizes(new ArrayCollection($prizes));
        $league->getLeagueRegions()->clear();
        $league->setLeagueRegions(new ArrayCollection($leagueRegions));
        $league->getLeagueUsers()->clear();
        $league->setLeagueUsers(new ArrayCollection($leagueUsers));
        $leagueDAO->save($league);
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTemporalLeagues($region, $hydrate = false, $skipCache = false) {
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        return $leagueDAO->getTemporalLeagues($region, $hydrate, $skipCache);
    }

    /**
     * @param int $leagueId
     * @param int $top
     * @param int $offset
     * @return array
     */
    public function getLeagueTop($leagueId, $top, $offset = 0) {
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        return $leagueUserDAO->getLeagueTop($leagueId, $top, $offset);
    }

    /**
     * @param int $leagueId
     * @param bool $skipCache
     * @return int
     */
    public function getLeagueUsersCount($leagueId, $skipCache = false) {
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        return $leagueUserDAO->getLeagueUsersCount($leagueId, $skipCache);
    }

}