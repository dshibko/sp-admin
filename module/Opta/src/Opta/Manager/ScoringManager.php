<?php

namespace Opta\Manager;

use \Zend\Log\Logger;
use \Application\Model\Entities\PredictionPlayer;
use \Application\Model\Entities\MatchGoal;
use \Application\Model\DAOs\PredictionDAO;
use \Application\Manager\ExceptionManager;
use \Neoco\Manager\BasicManager;
use \Zend\ServiceManager\ServiceLocatorInterface;

class ScoringManager extends BasicManager {

    const MATCH_RESULT_POINTS = 1;
    const MATCH_SCORE_POINTS = 5;
    const GOAL_SCORER_POINTS = 1;
    const GOAL_SCORER_ORDER_POINTS = 3;

    /**
     * @var ScoringManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ScoringManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ScoringManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    public function calculateMatchScores($match) {

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $predictions = $predictionDAO->getMatchPredictions($match->getId());
        $predictionsArr = array();
        $predictionDAO->beginPredictionsUpdate();

        foreach ($predictions as $prediction) {
            $points = 0;

            $homeTeamScore = (int)$prediction['home_team_score'];
            $awayTeamScore = (int)$prediction['away_team_score'];
            $playersArr = explode(",", $prediction['players']);
            $teamsArr = explode(",", $prediction['teams']);
            $ordersArr = explode(",", $prediction['orders']);
            $predictionsGoals = array();
            $predictionsPlayersCount = 0;
            foreach ($playersArr as $order => $playerId) {
                $predictionsGoals[] = array(
                    'player_id' => !empty($playerId) ? (int)$playerId : null,
                    'team_id' => (int)$teamsArr[$order],
                    'order' => (int)$ordersArr[$order],
                );
                if (!empty($playerId)) $predictionsPlayersCount++;
            }

            $isCorrectResult = $this->getMatchResult($homeTeamScore, $awayTeamScore) ==
                $this->getMatchResult($match->getHomeTeamFullTimeScore(), $match->getAwayTeamFullTimeScore());
            if ($isCorrectResult)
                $points += self::MATCH_RESULT_POINTS;

            $isCorrectScore = $homeTeamScore == $match->getHomeTeamFullTimeScore() &&
                $awayTeamScore == $match->getAwayTeamFullTimeScore();
            if ($isCorrectScore)
                $points += self::MATCH_SCORE_POINTS;

            $correctScorers = $correctScorersOrder = 0;
            $goals = $match->getMatchGoals();
            $goalScorers = array();
            foreach ($goals as $goal)
                if ($goal->getPlayer() != null && $goal->getType() != MatchGoal::OWN_TYPE) {
                    $scorerId = $goal->getPlayer()->getId();
                    if (!array_key_exists($scorerId, $goalScorers))
                        $goalScorers[$scorerId] = 0;
                    $playerGoals = array();
                    foreach ($predictionsGoals as $predictionGoal) {
                        if ($predictionGoal['team_id'] == $goal->getTeam()->getId() &&
                            ($predictionGoal['player_id'] !== null && $predictionGoal['player_id'] == $goal->getPlayer()->getId()))
                            $playerGoals[] = $predictionGoal;
                    }
                    if ($goalScorers[$scorerId]++ < count($playerGoals)) {
                        $correctScorers++;
                        foreach ($playerGoals as $playerGoal)
                            if ($playerGoal['order'] == $goal->getOrder()) {
                                $correctScorersOrder++;
                                break;
                            }
                    }
                }

            $points += $correctScorers * self::GOAL_SCORER_POINTS;
            $points += $correctScorersOrder * self::GOAL_SCORER_ORDER_POINTS;

            if ($match->getIsDoublePoints())
                $points *= 2;

            $prediction = array (
                'id' => (int)$prediction['id'],
                'user_id' => (int)$prediction['user_id'],
                'is_correct_result' => (int)$isCorrectResult,
                'is_correct_score' => (int)$isCorrectScore,
                'correct_scorers' => $correctScorers,
                'correct_scorers_order' => $correctScorersOrder,
                'predictions_players_count' => $predictionsPlayersCount,
                'points' => $points,
            );

            $predictionsArr [$prediction['user_id']] = $prediction;

            $predictionDAO->appendPredictionsUpdate($prediction);

        }

        $predictionDAO->commitPredictionsUpdate();
        $predictionDAO->clearCache();

        \Application\Manager\LeagueManager::getInstance($this->getServiceLocator())->recalculateLeaguesTables($match, $predictionsArr);

    }

    private function getMatchResult($homeScore, $awayScore) {
        return $homeScore != $awayScore ?  (($homeScore - $awayScore) / abs($homeScore - $awayScore)) : 0;
    }

}
