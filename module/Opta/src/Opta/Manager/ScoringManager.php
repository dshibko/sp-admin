<?php

namespace Opta\Manager;

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

        try {

            $predictions = $match->getPredictions();
            $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());

            foreach ($predictions as $prediction) {
                $points = 0;

                $isCorrectResult = $this->getMatchResult($prediction->getHomeTeamScore(), $prediction->getAwayTeamScore()) ==
                    $this->getMatchResult($match->getHomeTeamFullTimeScore(), $match->getAwayTeamFullTimeScore());
                if ($isCorrectResult)
                    $points += self::MATCH_RESULT_POINTS;

                $isCorrectScore = $prediction->getHomeTeamScore() == $match->getHomeTeamFullTimeScore() &&
                    $prediction->getAwayTeamScore() == $match->getAwayTeamFullTimeScore();
                if ($isCorrectScore)
                    $points += self::MATCH_SCORE_POINTS;

                $correctScorers = $correctScorersOrder = 0;
                $goals = $match->getMatchGoals();
                $goalScorers = array();
                foreach ($goals as $goal)
                    if ($goal->getType() != MatchGoal::OWN_TYPE) {
                        $scorerId = $goal->getPlayer()->getId();
                        if (array_key_exists($scorerId, $goalScorers))
                            $goalScorers[$scorerId] = 0;
                        $playerGoals = $prediction->getPredictionPlayers()->filter(function(PredictionPlayer $predictionPlayer) use ($goal) {
                            return $predictionPlayer->getTeam()->getId() == $goal->getTeam()->getId() &&
                                $predictionPlayer->getPlayer() != null && $predictionPlayer->getPlayer()->getId() == $goal->getPlayer()->getId();
                        });
                        if ($goalScorers[$scorerId]++ < count($playerGoals)) {
                            $correctScorers++;
                            foreach ($playerGoals as $playerGoal)
                                if ($playerGoal->getOrder() == $goal->getOrder()) {
                                    $correctScorersOrder++;
                                    break;
                                }
                        }
                    }

                $points += $correctScorers * self::GOAL_SCORER_POINTS;
                $points += $correctScorersOrder * self::GOAL_SCORER_ORDER_POINTS;

                $prediction->setIsCorrectResult($isCorrectResult);
                $prediction->setIsCorrectScore($isCorrectScore);
                $prediction->setCorrectScorers($correctScorers);
                $prediction->setCorrectScorersOrder($correctScorersOrder);
                $prediction->setPoints($points);

                $predictionDAO->save($prediction);
            }

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e);
        }

    }

    private function getMatchResult($homeScore, $awayScore) {
        return $homeScore != $awayScore ?  (($homeScore - $awayScore) / abs($homeScore - $awayScore)) : 0;
    }

}
