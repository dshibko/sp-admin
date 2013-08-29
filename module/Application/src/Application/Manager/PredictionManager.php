<?php

namespace Application\Manager;

use \Application\Model\DAOs\PredictionPlayerDAO;
use \Application\Model\Entities\Match;
use \Application\Model\DAOs\TeamDAO;
use \Application\Model\DAOs\PlayerDAO;
use \Application\Model\Entities\PredictionPlayer;
use Application\Model\Entities\Season;
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
     * @param Season $season
     * @return int|string
     */
    public function getAvgNumberOfPredictions($season) {
        $numberOfPredictions = PredictionDAO::getInstance($this->getServiceLocator())->getPredictionsCount($season->getId());
        $numberOfFinishedMatches = MatchDAO::getInstance($this->getServiceLocator())->getBlockedFinishedMatchesInTheSeasonNumber($season);
        $numberOfLiveMatches = MatchDAO::getInstance($this->getServiceLocator())->getLiveMatchesNumber(new \DateTime(), $season);
        $numberOfMatchesLeftThisSeason = MatchDAO::getInstance($this->getServiceLocator())->getMatchesLeftInTheSeasonNumber(new \DateTime(), $season);
        $numberOfPredictableMatches = SettingsManager::getInstance($this->getServiceLocator())->getSetting(SettingsManager::AHEAD_PREDICTIONS_DAYS);
        $avgNumberOfPrediction = $numberOfPredictions / ($numberOfFinishedMatches + $numberOfLiveMatches + min($numberOfMatchesLeftThisSeason, $numberOfPredictableMatches));
        $avgNumberOfPrediction = number_format(round($avgNumberOfPrediction * 100) / 100, 2);
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
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $matchData = $matchDAO->getNearestMatch($offset, $season, $skipCache);
        if (!empty($matchData)) {
            $match = $matchDAO->getMatchInfo($matchData['matchId'], $hydrate, $skipCache);
            $utcTime = new \DateTime();
            $startUtcTime = $match['startTime'];
            if ($startUtcTime < $utcTime)
                $match['status'] = Match::LIVE_STATUS;
            $match['localStartTime'] = ApplicationManager::getInstance($this->getServiceLocator())->getLocalTime($match['startTime'], $match['timezone']);
            $homeSquad = $this->getTeamSquad($match['status'] == Match::PRE_MATCH_STATUS && $match['hasLineUp'], $matchData['matchId'], $match['homeId'], $matchData['competitionId'], $season->getId(), $hydrate, $skipCache);
            $match['homeSquad'] = $this->preparePlayers($homeSquad);
            $awaySquad = $this->getTeamSquad($match['status'] == Match::PRE_MATCH_STATUS && $match['hasLineUp'], $matchData['matchId'], $match['awayId'], $matchData['competitionId'], $season->getId(), $hydrate, $skipCache);
            $match['awaySquad'] = $this->preparePlayers($awaySquad);
            $match['prediction'] = $predictionDAO->getUserPrediction($matchData['matchId'], $user->getId(), true, $skipCache);
            return $match;
        } else
            return null;
    }

    private function getTeamSquad($hasLineUp, $matchId, $teamId, $competitionId, $seasonId, $hydrate, $skipCache) {
        if ($hasLineUp)
            $squad = MatchDAO::getInstance($this->getServiceLocator())->getMatchTeamSquad($matchId, $teamId, $hydrate, $skipCache);
        else {
            $squad = TeamDAO::getInstance($this->getServiceLocator())->getTeamSquadInCompetition($teamId, $competitionId, $seasonId, $hydrate, $skipCache);
            if (empty($squad))
                $squad = TeamDAO::getInstance($this->getServiceLocator())->getTeamSquad($teamId, $seasonId, $hydrate, $skipCache);
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

    public static $positionsOrder = array('Forward', 'Midfielder', 'Defender', 'Goalkeeper');
    public static $positionsAbbreviation = array('FW', 'MF', 'DF', 'GK');

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
     * @param int $matchId
     * @param int $limit
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTopScorers($matchId, $limit = 5, $hydrate = false, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getTopScorers($matchId, $limit, $hydrate, $skipCache);
    }

    /**
     * @param  $match
     * @param $teamId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return int
     */
    public function getClubWinPredictionsCount($matchId, $matchHomeTeamId, $teamId, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getClubWinPredictionsCount($matchId, $matchHomeTeamId == $teamId, $skipCache);
    }

    /**
     * @param $matchId
     * @param $prediction
     * @param bool $hydrate
     * @param bool $skipCache
     * @return int
     */
    public function getSameScorelinePredictionsCount($matchId, $homeTeamScore, $awayTeamScore, $userId, $skipCache = false) {
        return PredictionDAO::getInstance($this->getServiceLocator())->getSameScorelinePredictionsCount($matchId, $homeTeamScore, $awayTeamScore, $userId, $skipCache);
    }

    /**
     * @param $matchId
     * @param int $limit
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTopScores($matchId, $limit = 5, $hydrate = false, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getTopScores($matchId, $limit, $hydrate, $skipCache);
    }

    /**
     * @param $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getUsersCountWithCorrectResult($matchId, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getUsersCountWithCorrectResult($matchId, $skipCache);
    }

    /**
     * @param $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionsCorrectScoreCount($matchId, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getPredictionsCorrectScoreCount($matchId, $skipCache);
    }

    /**
     * @param $matchId
     * @return mixed
     */
    public function getUsersWithPerfectResult($matchId)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getUsersWithPerfectResult($matchId);
    }

    /**
     * @param int $matchId
     * @param int $hoursFromNow
     * @return array
     */
    public function getNumberOfPredictionsPerHour($matchId, $hoursFromNow = 12)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getNumberOfPredictionsPerHour($matchId, $hoursFromNow);
    }

    /**
     * @param $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionPlayersCount($matchId, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getPredictionPlayersCount($matchId, $skipCache);
    }

    /**
     * @param $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionCorrectScorersSum($matchId, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getPredictionCorrectScorersSum($matchId, $skipCache);
    }

    /**
     * @param $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionCorrectScorersOrderSum($matchId, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getPredictionCorrectScorersOrderSum($matchId, $skipCache);
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


    /**
     * @param $matchesIds
     * @param $userId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getPredictedMatchesIdsByUser($matchesIds, $userId, $hydrate = false, $skipCache = false) {
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $matchesIdsArr = $predictionDAO->getPredictedMatchesIdsByUser($matchesIds, $userId, $hydrate, $skipCache);
        $matchesIds = array();
        if (is_array($matchesIdsArr)) {
            foreach ($matchesIdsArr as $matchesIdArr) {
                $matchesIds[] = $matchesIdArr[1];
            }
        }

        return $matchesIds;
    }

    /**
     * @param $matchId
     * @param array $scorersIds
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getCorrectScorersPredictionsCount($matchId, array $scorersIds, $hydrate = true, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getCorrectScorersPredictionsCount($matchId, $scorersIds,$hydrate ,$skipCache);
    }

    public function getAllUserPredictionsNumber($user) {
        return PredictionDAO::getInstance($this->getServiceLocator())->getAllUserPredictionsNumber($user);
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

    public function getConsecutiveWinsInSeason($season, $user, $beforeTime) {
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $lastWrongResultMatchThisSeason = $predictionDAO->getLastWrongResultMatchThisSeason($season, $user, $beforeTime);
        $fromTime = $lastWrongResultMatchThisSeason != null ? $lastWrongResultMatchThisSeason->getMatch()->getStartTime() : null;
        return $predictionDAO->getConsecutiveWinsInSeason($season, $user, $beforeTime, $fromTime);
    }

    public function getUserPrediction($matchId, $userId, $hydrate = false, $skipCache = false) {
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        return $predictionDAO->getUserPrediction($matchId, $userId, $hydrate, $skipCache);
    }

    public function getMatchPredictionsCount($matchId, $skipCache = false)
    {
        return PredictionDAO::getInstance($this->getServiceLocator())->getMatchPredictionsCount($matchId, $skipCache);
    }
}