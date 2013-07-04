<?php

namespace Application\Manager;

use \Application\Model\DAOs\PredictionPlayerDAO;
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
            PredictionPlayerDAO::getInstance($this->getServiceLocator())->clearCache();

        } else
            throw new \Exception(MessagesConstants::ERROR_MATCH_NOT_FOUND);

    }

    /**
     * @param $season
     * @return int|string
     */
    public function getAvgNumberOfPredictions($season) {
        $numberOfPredictions = PredictionDAO::getInstance($this->getServiceLocator())->getPredictionsCount($season->getId());
        $numberOfFinishedMatches = MatchDAO::getInstance($this->getServiceLocator())->getBlockedFinishedMatchesInTheSeasonNumber($season);
        $numberOfLiveMatches = MatchDAO::getInstance($this->getServiceLocator())->getLiveMatchesNumber(new \DateTime(), $season);
        $numberOfPredictableMatches = SettingsManager::getInstance($this->getServiceLocator())->getSetting(SettingsManager::AHEAD_PREDICTIONS_DAYS);
        $avgNumberOfPrediction = $numberOfPredictions / ($numberOfFinishedMatches + $numberOfLiveMatches + $numberOfPredictableMatches);
//        $avgNumberOfPrediction = PredictionDAO::getInstance($this->getServiceLocator())->getAvgNumberOfPredictions($season);
        $avgNumberOfPrediction = number_format(ceil($avgNumberOfPrediction * 100) / 100, 2);
        return $avgNumberOfPrediction;
    }

    /**
     * @param $offset
     * @param \Application\Model\Entities\User $user
     * @param \Application\Model\Entities\Season $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getNearestMatchWithPrediction($offset, $user, $season, $hydrate = false, $skipCache = false) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        $teamDAO = TeamDAO::getInstance($this->getServiceLocator());
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $matchData = $matchDAO->getNearestMatch($offset, $season, $skipCache);
        if (!empty($matchData)) {
            $match = $matchDAO->getMatchInfo($matchData['matchId'], $hydrate, $skipCache);
            $match['localStartTime'] = ApplicationManager::getInstance($this->getServiceLocator())->getLocalTime($match['startTime'], $match['timezone']);
            $homeSquad = $this->getTeamSquad($match['hasLineUp'], $matchData['matchId'], $match['homeId'], $matchData['competitionId'], $hydrate, $skipCache);
            $match['homeSquad'] = $this->preparePlayers($homeSquad);
            $awaySquad = $this->getTeamSquad($match['hasLineUp'], $matchData['matchId'], $match['awayId'], $matchData['competitionId'], $hydrate, $skipCache);
            $match['awaySquad'] = $this->preparePlayers($awaySquad);
            $match['prediction'] = $predictionDAO->getUserPrediction($matchData['matchId'], $user->getId(), true, $skipCache);
            return $match;
        } else
            return null;
    }

    private function getTeamSquad($hasLineUp, $matchId, $teamId, $competitionId, $hydrate, $skipCache) {
        if ($hasLineUp)
            $squad = MatchDAO::getInstance($this->getServiceLocator())->getMatchTeamSquad($matchId, $teamId, $hydrate, $skipCache);
        else {
            $squad = TeamDAO::getInstance($this->getServiceLocator())->getTeamSquadInCompetition($teamId, $competitionId, $hydrate, $skipCache);
            if (empty($squad))
                $squad = TeamDAO::getInstance($this->getServiceLocator())->getTeamSquad($teamId, $hydrate, $skipCache);
        }
        return $squad;
    }

    /**
     * @param $offset
     * @param \Application\Model\Entities\User $user
     * @param \Application\Model\Entities\Season $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getLastMatchWithPrediction($offset, $user, $season, $hydrate = false, $skipCache = false) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $matchData = $matchDAO->getLastMatch($offset, $user, $season, $skipCache);
        if (!empty($matchData)) {
            $match = $matchDAO->getMatchInfo($matchData['matchId'], $hydrate, $skipCache);
            $match['goals'] = $matchDAO->getMatchGoals($matchData['matchId'], $hydrate, $skipCache);
            $match['localStartTime'] = ApplicationManager::getInstance($this->getServiceLocator())->getLocalTime($match['startTime'], $match['timezone']);
            $match['prediction'] = $predictionDAO->getUserPrediction($matchData['matchId'], $user->getId(), true, $skipCache);
            return $match;
        } else
            return null;
    }

    public static $positionsOrder = array('Goalkeeper', 'Defender', 'Midfielder', 'Forward');
    public static $positionsAbbreviation = array('GK', 'DF', 'MF', 'FW');

    /**
     * @param $players
     * @return array
     */
    private function preparePlayers($players) {
        usort($players, function($p1, $p2) {
            $pos1 = array_search($p1['position'], PredictionManager::$positionsOrder);
            $pos2 = array_search($p2['position'], PredictionManager::$positionsOrder);
            $isStart1 = array_key_exists('isStart', $p1) ? $p1['isStart'] : 0;
            $isStart2 = array_key_exists('isStart', $p2) ? $p2['isStart'] : 0;
            return $isStart1 != $isStart2 ? $isStart2 - $isStart1 :
                ($pos1 != $pos2 ? $pos1 - $pos2 : $p1['shirtNumber'] - $p2['shirtNumber']);
        });
        array_walk($players, function(&$p) {
            $p['position'] = PredictionManager::$positionsAbbreviation[array_search($p['position'], PredictionManager::$positionsOrder)];
        });
        return $players;
    }

    /**
     * @param array $predictionIds
     * @param int $limit
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTopScorers(array $predictionIds, $limit = 5, $hydrate = false, $skipCache = false)
    {
        $scorers = array();
        if (!empty($predictionIds)){
            $scorers = PredictionDAO::getInstance($this->getServiceLocator())->getTopScorers($predictionIds, $limit, $hydrate, $skipCache);
        }
        return $scorers;
    }

    /**
     * @param array $predictionIds
     * @param int $limit
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTopScores(array $predictionIds, $limit = 5, $hydrate = false, $skipCache = false)
    {
        $scores = array();
        if (!empty($predictionIds)){
            $scores = PredictionDAO::getInstance($this->getServiceLocator())->getTopScores($predictionIds, $limit, $hydrate, $skipCache);
        }
        return $scores;
    }

    /**
     * @param array $predictionIds
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getUsersCountWithCorrectResult(array $predictionIds, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getUsersCountWithCorrectResult($predictionIds, $skipCache);
    }

    /**
     * @param array $predictionIds
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionsCorrectScoreCount(array $predictionIds, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getPredictionsCorrectScoreCount($predictionIds, $skipCache);
    }

    /**
     * @param array $predictionIds
     * @return mixed
     */
    public function getUsersWithPerfectResult(array $predictionIds)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getUsersWithPerfectResult($predictionIds);
    }

    /**
     * @param array $predictionIds
     * @param int $hoursFromNow
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getNumberOfPredictionsPerHour(array $predictionIds, $hoursFromNow = 12)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getNumberOfPredictionsPerHour($predictionIds, $hoursFromNow);
    }

    /**
     * @param array $predictionIds
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionPlayersCount(array $predictionIds, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getPredictionPlayersCount($predictionIds, $skipCache);
    }

    /**
     * @param array $predictionIds
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionCorrectScorersSum(array $predictionIds, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getPredictionCorrectScorersSum($predictionIds, $skipCache);
    }

    /**
     * @param array $predictionIds
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionCorrectScorersOrderSum(array $predictionIds, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getPredictionCorrectScorersOrderSum($predictionIds, $skipCache);
    }

    /**
     * @return int|mixed
     */
    public function getPredictableCount() {
        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season == null) return 0;
        $maxAhead = SettingsManager::getInstance($this->getServiceLocator())->getSetting(SettingsManager::AHEAD_PREDICTIONS_DAYS);
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        return $predictionDAO->getPredictableCount($season->getId(), $user->getId(), $maxAhead);
    }

    /**
     * @param $matchId
     * @param $userId
     */
    public function makeResultViewed($matchId, $userId) {
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $prediction = $predictionDAO->getUserPrediction($matchId, $userId);
        $prediction->setWasViewed(true);
        $predictionDAO->save($prediction);
    }

    public function getCorrectScorersPredictionsCount(array $predictionIds, array $scorersIds, $hydrate = true, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getCorrectScorersPredictionsCount($predictionIds, $scorersIds,$hydrate ,$skipCache);
    }

    public function getUserPredictionsNumber($season, $user) {
        return PredictionDAO::getInstance($this->getServiceLocator())->getUserPredictionsNumber($season, $user);
    }

    public function getUserCorrectScorerPredictionsNumber($season, $user, $beforeTime) {
        $number = PredictionDAO::getInstance($this->getServiceLocator())->getUserCorrectScorerPredictionsNumber($season, $user, $beforeTime);
        return $number == null ? 0 : $number;
    }

    public function hasUserCorrectResults($season, $user, $beforeTime) {
        $number = PredictionDAO::getInstance($this->getServiceLocator())->hasUserCorrectResults($season, $user, $beforeTime);
        return $number == null ? false : true;
    }

    public function getUserPrediction($matchId, $userId, $hydrate = false, $skipCache = false) {
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        return $predictionDAO->getUserPrediction($matchId, $userId, $hydrate, $skipCache);
    }

    public function getMatchPredictorsIds($matchId, $hydrate = false, $skipCache = false) {
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        return $predictionDAO->getMatchPredictorsIds($matchId, $hydrate, $skipCache);
    }

}