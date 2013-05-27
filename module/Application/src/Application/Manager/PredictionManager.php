<?php

namespace Application\Manager;

use \Application\Model\Entities\Match;
use \Application\Model\DAOs\TeamDAO;
use \Application\Model\DAOs\PlayerDAO;
use \Application\Model\Entities\PredictionPlayer;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\DAOs\MatchDAO;
use \Application\Model\Entities\Prediction;
use \Application\Model\DAOs\PredictionDAO;
use \Application\Model\DAOs\RegionDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class PredictionManager extends BasicManager {

    /**
     * @var PredictionManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PredictionManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PredictionManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param integer $matchId
     * @param \Application\Model\Entities\User $user
     * @param integer $homeTeamScore
     * @param integer $awayTeamScore
     * @param array $scoresData
     * @throws \Exception
     */
    public function predict($matchId, $user, $homeTeamScore, $awayTeamScore, $scoresData) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        $match = $matchDAO->findOneById($matchId);

        if ($match != null) {

            $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
            $playerDAO = PlayerDAO::getInstance($this->getServiceLocator());

            $prediction = $predictionDAO->getUserPrediction($match->getId(), $user->getId());
            if ($prediction == null) {
                $prediction = new Prediction();
                $prediction->setMatch($match);
                $prediction->setUser($user);
                $prediction->setCreationDate(new \DateTime());
            } else
                $prediction->clearPredictionPlayers();

            $prediction->setLastUpdateDate(new \DateTime());
            $prediction->setHomeTeamScore($homeTeamScore);
            $prediction->setAwayTeamScore($awayTeamScore);

            foreach ($scoresData as $scoreRow) {
                $predictionScore = new PredictionPlayer();
                $predictionScore->setPrediction($prediction);
                $team = null;
                if (array_key_exists('side', $scoreRow))  {
                    $side = $scoreRow['side'];
                    if ($side == 'home')
                        $team = $match->getHomeTeam();
                    else if ($side == 'away')
                        $team = $match->getAwayTeam();
                }
                if ($team == null)
                    throw new \Exception(MessagesConstants::ERROR_TEAM_NOT_FOUND);
                $predictionScore->setTeam($team);
                $playerId = $scoreRow['scorer'];
                if ($playerId != null)
                    $player = $playerDAO->findOneById($playerId);
                else
                    $player = null;
                $predictionScore->setPlayer($player);
                $predictionScore->setOrder($scoreRow['order']);
                $prediction->addPredictionPlayer($predictionScore);
            }

            $predictionDAO->save($prediction);

        } else
            throw new \Exception(MessagesConstants::ERROR_MATCH_NOT_FOUND);

    }

    public function getAvgNumberOfPrediction($season) {
        $avgNumberOfPrediction = PredictionDAO::getInstance($this->getServiceLocator())->getAvgNumberOfPrediction($season);
        $avgNumberOfPrediction = number_format(ceil($avgNumberOfPrediction * 100) / 100, 2);
        return $avgNumberOfPrediction;
    }

    /**
     * @param \DateTime $fromTime
     * @param $offset
     * @param \Application\Model\Entities\User $user
     * @param \Application\Model\Entities\Season $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getNearestMatchWithPrediction($fromTime, $offset, $user, $season, $hydrate = false, $skipCache = false) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        $teamDAO = TeamDAO::getInstance($this->getServiceLocator());
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $matchData = $matchDAO->getNearestMatch($fromTime, $offset, $season, $skipCache);
        if (!empty($matchData)) {
            $match = $matchDAO->getMatchInfo($matchData['matchId'], $hydrate, $skipCache);
            $match['localStartTime'] = ApplicationManager::getInstance($this->getServiceLocator())->getLocalTime($match['startTime'], $match['timezone']);
            $homeSquad = $teamDAO->getTeamSquadInCompetition($match['homeId'], $matchData['competitionId'], $hydrate, $skipCache);
            $match['homeSquad'] = $this->preparePlayers($homeSquad);
            $awaySquad = $teamDAO->getTeamSquadInCompetition($match['awayId'], $matchData['competitionId'], $hydrate, $skipCache);
            $match['awaySquad'] = $this->preparePlayers($awaySquad);
            $match['prediction'] = $predictionDAO->getUserPrediction($matchData['matchId'], $user->getId(), true, $skipCache);
            return $match;
        } else
            return null;
    }

    public function getMatchesLeftInTheSeason($fromTime, $season, $skipCache = false) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getMatchesLeftInTheSeason($fromTime, $season, $skipCache);
    }

    public static $positionsOrder = array('Goalkeeper', 'Defender', 'Midfielder', 'Forward');
    public static $positionsAbbreviation = array('GK', 'DF', 'MF', 'FW');

    private function preparePlayers($players) {
        usort($players, function($p1, $p2) {
            $pos1 = array_search($p1['position'], PredictionManager::$positionsOrder);
            $pos2 = array_search($p2['position'], PredictionManager::$positionsOrder);
            return $pos1 != $pos2 ? $pos1 - $pos2 : $p1['shirtNumber'] - $p2['shirtNumber'];
        });
        array_walk($players, function(&$p) {
            $p['position'] = PredictionManager::$positionsAbbreviation[array_search($p['position'], PredictionManager::$positionsOrder)];
        });
        return $players;
    }

}