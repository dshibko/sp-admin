<?php

namespace Application\Manager;

use \Application\Model\DAOs\LeagueUserPlaceDAO;
use Application\Model\DAOs\PredictionDAO;
use \Application\Model\Entities\LeagueUserPlace;
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

    const GLOBAL_LEAGUE_NAME = 'Global';
    const REGIONAL_LEAGUE_NAME = 'Regional';
    const USER_LEAGUE_MOVEMENT_UP = 'up';
    const USER_LEAGUE_MOVEMENT_DOWN  = 'down';
    const USER_LEAGUE_MOVEMENT_SAME = 'same';

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

    public function recalculateLeaguesTables(\Application\Model\Entities\Match $match, array $predictions) {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();

        if ($season != null) {

            $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
            $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());

            $prevPredictions = PredictionDAO::getInstance($this->getServiceLocator())->getPredictionsByMatchId(1);
            $prevPredictionsArr = array();

            foreach($prevPredictions as $prevPrediction)
                $prevPredictionsArr[$prevPrediction['user_id']] = $prevPrediction;

            $leagueUserDAO->beginLeagueUsersUpdate();

            foreach ($season->getLeagues() as $league)
                if ($league->getType() != League::MINI_TYPE || $league->getIsActive($match->getStartTime())) {
                    $usersData = $leagueDAO->getLeagueUsersScores($league);

                    $leagueUsers = array();
                    foreach ($usersData as $userRow) {
                        if (array_key_exists($userRow['user_id'], $predictions)) {
                            $prediction = $predictions[$userRow['user_id']];
                            $prevPrediction = array_key_exists($userRow['user_id'], $prevPredictionsArr) ? $prevPredictionsArr[$userRow['user_id']] : null;
                            $userRow['predictions_players_count'] = $prediction['predictions_players_count'] + ($prevPrediction !== null ? $prevPrediction['predictions_players_count'] : 0);
                            $userRow['predictions_count'] = 1 + ($prevPrediction !== null ? 1 : 0);
                            $userRow['correct_results'] = $prediction['is_correct_result'] + ($prevPrediction !== null ? $prevPrediction['is_correct_result'] : 0);
                            $userRow['correct_scores'] = $prediction['is_correct_score'] + ($prevPrediction !== null ? $prevPrediction['is_correct_score'] : 0);
                            $userRow['correct_scorers'] = $prediction['correct_scorers'] + ($prevPrediction !== null ? $prevPrediction['correct_scorers'] : 0);
                            $userRow['correct_scorers_order'] = $prediction['correct_scorers_order'] + ($prevPrediction !== null ? $prevPrediction['correct_scorers_order'] : 0);
//                            $userRow['predictions_players_count'] += $prediction['predictions_players_count'];
//                            $userRow['predictions_count']++;
//                            $userRow['correct_results'] += $prediction['is_correct_result'];
//                            $userRow['correct_scores'] += $prediction['is_correct_score'];
//                            $userRow['correct_scorers'] += $prediction['correct_scorers'];
//                            $userRow['correct_scorers_order'] += $prediction['correct_scorers_order'];
                            $userRow['accuracy'] = $userRow['correct_results'] / $userRow['predictions_count'] + $userRow['correct_scores'] / $userRow['predictions_count'];
                            $divider = 2;
                            if ($userRow['predictions_players_count'] > 0) {
                                $userRow['accuracy'] += $userRow['correct_scorers'] / $userRow['predictions_players_count'] + $userRow['correct_scorers_order'] / $userRow['predictions_players_count'];
                                $divider = 4;
                            }
                            $userRow['accuracy'] /= $divider;
                            $userRow['points'] = $prediction['points'];
//                            if ($userRow['points'] === null)
//                                $userRow['points'] = $prediction['points'];
//                            else
//                                $userRow['points'] += $prediction['points'];
                        }
                        $userRow['date'] = new \DateTime($userRow['registration_date']);
                        if ($userRow['points'] !== null)
                            $leagueUsers[] = $userRow;
                    }

                    usort($leagueUsers, function($u2, $u1) {
                        $res = $u1['points'] != $u2['points'] ? $u1['points'] - $u2['points'] :
                            (floor(100 * $u1['accuracy']) != floor(100 * $u2['accuracy']) ? $u1['accuracy'] - $u2['accuracy'] :
                            ($u1['predictions_count'] != $u2['predictions_count'] ? $u1['predictions_count'] - $u2['predictions_count'] :
                            ($u1['correct_results'] != $u2['correct_results'] ? $u1['correct_results'] - $u2['correct_results'] :
                            ($u1['correct_scores'] != $u2['correct_scores'] ? $u1['correct_scores'] - $u2['correct_scores'] :
                            ($u1['correct_scorers'] != $u2['correct_scorers'] ? $u1['correct_scorers'] - $u2['correct_scorers'] :
                            ($u1['date']->getTimestamp() != $u2['date']->getTimestamp() ? $u2['date']->getTimestamp() - $u1['date']->getTimestamp() :
                            ($u2['user_id'] - $u1['user_id'])))))));
                        return (int)($res/abs($res));
                    });

                    foreach ($leagueUsers as $i => $userRow) {
                        $leagueUserDAO->appendLeagueUsersUpdate($userRow, $i + 1);
                        $leagueUserDAO->appendLeagueUserPlace($userRow, $i + 1, $match->getId());
                    }

                }

            $leagueUserDAO->commitLeagueUsersUpdate();
            $leagueUserDAO->clearCache();

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

    /**
     * @param League $league
     */
    public function deleteLeague($league) {
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $leagueDAO->remove($league);
    }

    public function saveMiniLeague($displayName, $seasonId, $startDate, $endDate, $regionsData, $leagueId = -1, $editableLeague = true) {
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        if ($leagueId == -1) {
            $league = new League();
            $season = SeasonDAO::getInstance($this->getServiceLocator())->findOneById($seasonId);
            $league->setSeason($season);
            $league->setCreationDate(new \DateTime());
            $league->setCreator(ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser());
        } else
            $league = $leagueDAO->findOneById($leagueId);
        if ($startDate != null && $endDate != null) {
            $league->setStartDate($startDate);
            $league->setEndDate($endDate);
        }
        $league->setType(League::MINI_TYPE);
        $league->setDisplayName($displayName);
        $regionDAO = RegionDAO::getInstance($this->getServiceLocator());
        $prizes = array();
        $leagueRegions = array();
        foreach ($regionsData as $regionId => $regionRow) {
            $region = $regionDAO->findOneById($regionId);
            if ($region != null) {
                if (!$editableLeague)
                    $prize = $league->getPrizeByRegionId($regionId);
                else
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
                if (!$editableLeague)
                    $leagueRegion = $league->getLeagueRegionByRegionId($regionId);
                else
                    $leagueRegion = new LeagueRegion();
                $leagueRegion->setDisplayName($regionRow['displayName']);
                $leagueRegion->setLeague($league);
                $leagueRegion->setRegion($region);
                $leagueRegions [] = $leagueRegion;
            }
        }
        if ($editableLeague) {
            $league->getPrizes()->clear();
            $league->setPrizes(new ArrayCollection($prizes));
            $league->getLeagueRegions()->clear();
            $league->setLeagueRegions(new ArrayCollection($leagueRegions));
        }
        $leagueDAO->save($league);
        if ($editableLeague && $leagueId === -1) {
            $userManager = UserManager::getInstance($this->getServiceLocator());
            foreach ($regionsData as $regionId => $regionRow)
                $userManager->registerLeagueUsers($league, $regionId);
        }
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
     * @param array|null $facebookIds
     * @return array
     */
    public function getLeagueTop($leagueId, $top = 0, $offset = 0, $facebookIds = null) {
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        return $leagueUserDAO->getLeagueTop($leagueId, $top, $offset, $facebookIds);
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

    /**
     * @param int $leagueId
     * @param int $userId
     * @return int
     */
    public function getYourPlaceInLeague($leagueId, $userId) {
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        return $leagueUserDAO->getYourPlaceInLeague($leagueId, $userId);
    }

}