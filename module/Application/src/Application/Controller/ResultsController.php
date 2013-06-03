<?php

namespace Application\Controller;

use \Application\Model\Entities\MatchGoal;
use \Opta\Manager\ScoringManager;
use \Application\Manager\MatchManager;
use \Application\Model\Entities\Match;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\SettingsManager;
use \Application\Manager\ExceptionManager;
use \Application\Manager\PredictionManager;
use \Application\Manager\RegistrationManager;
use \Neoco\Controller\AbstractActionController;
use \Application\Manager\ApplicationManager;
use \Application\Manager\ContentManager;
use \Application\Manager\RegionManager;
use Zend\View\Model\ViewModel;

class ResultsController extends AbstractActionController {

    const RESULTS_ROUTE = 'results';

    public function indexAction() {

        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $matchManager = MatchManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $translator = $this->getServiceLocator()->get('translator');

        $currentMatch = $breakpoints = array();
        $back = $playedMatches = 0;
        $firstView = false;

        try {

            $back = (int) $this->params()->fromRoute('back', 0);

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();

            if ($season == null)
                throw new \Exception($translator->translate(MessagesConstants::INFO_OUT_OF_SEASON));

            $playedMatches = $matchManager->getFinishedMatchesInTheSeasonNumber($user, $season);

            if ($playedMatches == 0)
                throw new \Exception($translator->translate(MessagesConstants::ERROR_NO_FINISHED_MATCHES_IN_THE_SEASON));

            if ($back > $playedMatches - 1)
                throw new \Exception($translator->translate(MessagesConstants::ERROR_MATCH_NOT_FOUND));

            $currentMatch = $predictionManager->getLastMatchWithPrediction($back, $user, $season);

            if ($currentMatch == null)
                throw new \Exception($translator->translate(MessagesConstants::ERROR_MATCH_NOT_FOUND));

            $predictionPlayers = $currentMatch['prediction']['predictionPlayers'];
            $predictionPlayersCount = 0;
            foreach ($predictionPlayers as $predictionPlayer)
                if ($predictionPlayer['playerId'] != null)
                    $predictionPlayersCount++;
            $currentMatch['prediction'] = array_merge($currentMatch['prediction'], $this->getScorers($predictionPlayers, $currentMatch));
            unset($currentMatch['prediction']['predictionPlayers']);

            if (!$currentMatch['prediction']['wasViewed']) {
                $firstView = true;
                $predictionManager->makeResultViewed($currentMatch['id'], $user->getId());
            }

            $goals = $currentMatch['goals'];
            $currentMatch['goals'] = $this->getScorers($goals, $currentMatch);

            $breakpoints = array();
            $homeTeamScore = $currentMatch['prediction']['homeTeamScore'];
            $awayTeamScore = $currentMatch['prediction']['awayTeamScore'];
            if ($homeTeamScore == $awayTeamScore)
                $resultKey = $translator->translate(MessagesConstants::INFO_YOU_PREDICTED_THE_DRAW);
            else
                $resultKey = sprintf($translator->translate(MessagesConstants::INFO_YOU_PREDICTED_THE_WINNER), '<b>' . ($homeTeamScore > $awayTeamScore ? $currentMatch['homeName'] : $currentMatch['awayName']) . '</b>');
            $resultPoints = $currentMatch['prediction']['isCorrectResult'] ? ScoringManager::MATCH_RESULT_POINTS : 0;
            $breakpoints[$resultKey] = '+' . $resultPoints;
            $scoreKey = sprintf($translator->translate(MessagesConstants::INFO_YOU_PREDICTED_THE_SCORE), '<b>' . $homeTeamScore . '-'. $awayTeamScore . '</b>');
            $scorePoints = $currentMatch['prediction']['isCorrectScore'] ? ScoringManager::MATCH_SCORE_POINTS : 0;
            $breakpoints[$scoreKey] = '+' . $scorePoints;
            if ($currentMatch['prediction']['correctScorers'] > 0 || $currentMatch['prediction']['correctScorersOrder'] > 0) {
                $goalScorers = array();
                $correctScorers = array();
                $correctScorersOrder = array();
                $sideKeys = array('homeScorers', 'awayScorers');
                foreach ($sideKeys as $side)
                    foreach ($currentMatch['goals'][$side] as $goal)
                        if ($goal['type'] != MatchGoal::OWN_TYPE) {
                            $scorerId = $goal['playerId'];
                            if (array_key_exists($scorerId, $goalScorers))
                                $goalScorers[$scorerId] = 0;
                            $predictGoals = array();
                            foreach ($currentMatch['prediction'][$side] as $predictGoal)
                                if ($predictGoal['playerId'] != null && $scorerId == $predictGoal['playerId'])
                                    $predictGoals [] = $predictGoal;

                            if ($goalScorers[$scorerId]++ < count($predictGoals)) {
                                $correctScorers[$scorerId . '-' . $goal['displayName']] = $goalScorers[$scorerId];
                                foreach ($predictGoals as $predictGoal)
                                    if ($predictGoal['order'] == $goal['order']) {
                                        $correctScorersOrder[$goal['order'] . '-' . $side] = $goal['displayName'];
                                        break;
                                    }
                            }
                        }
                if (count($correctScorers) > 0) {
                    $scorers = array();
                    foreach ($correctScorers as $k => $v) {
                        $scorerName = array_pop(explode('-', $k));
                        if ($v > 1)
                            $scorerName .= '(' . $v . ')';
                        $scorers [] = $scorerName;
                    }
                    $scorersKey = sprintf($translator->translate(MessagesConstants::INFO_YOU_PREDICTED_THE_SCORERS), '<b>' . implode(', ', $scorers) . '</b>');
                    $scorersPoints = ScoringManager::GOAL_SCORER_POINTS * $currentMatch['prediction']['correctScorers'];
                    $breakpoints[$scorersKey] = '+' . $scorersPoints;
                }
                if (count($correctScorersOrder) > 0) {
                    foreach ($correctScorersOrder as $k => $v) {
                        $scorerName = $v;
                        $order = array_shift(explode('-', $k));
                        $teamSide = array_pop(explode('-', $k));
                        $teamName = array_search($teamSide, $sideKeys) == 0 ? $currentMatch['homeName'] : $currentMatch['awayName'];
                        $scorersKey = sprintf($translator->translate(MessagesConstants::INFO_YOU_PREDICTED_SCORER_ORDER), '<b>' . $scorerName . '</b>', '<b>' . $order . '</b>', '<b>' . $teamName . '</b>');
                        $scorersPoints = ScoringManager::GOAL_SCORER_ORDER_POINTS;
                        $breakpoints[$scorersKey] = '+' . $scorersPoints;
                    }
                }
            }

            if ($currentMatch['isDoublePoints']) {
                $doubleKey = $translator->translate(MessagesConstants::INFO_THIS_IS_DOUBLE_POINTS_MATCH);
                $breakpoints[$doubleKey] = 'X2';
            }

            $accuracy = $currentMatch['prediction']['isCorrectResult']  + $currentMatch['prediction']['isCorrectScore'];
            $divider = 2;
            if ($predictionPlayersCount > 0) {
                $accuracy += $currentMatch['prediction']['correctScorers'] / $predictionPlayersCount + $currentMatch['prediction']['correctScorersOrder'] / $predictionPlayersCount;
                $divider = 4;
            }
            $accuracy /= $divider;
            $accuracy = floor(100 * $accuracy);
            $accuracyKey = sprintf($translator->translate(MessagesConstants::INFO_YOUR_ACCURACY), '<b>' . $accuracy . '%</b>');
            $breakpoints[$accuracyKey] = '';

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'current' => $currentMatch,
            'back' => $back,
            'maxBack' => $playedMatches - 1,
            'breakpoints' => $breakpoints,
            'firstResultView' => $firstView,
        );

    }

    private function getScorers(array $data, $currentMatch) {
        $homeScorers = array();
        $awayScorers = array();
        foreach ($data as $player)
            if ($player['teamId'] == $currentMatch['homeId'])
                $homeScorers[$player['order']] = $player;
            else if ($player['teamId'] == $currentMatch['awayId'])
                $awayScorers[$player['order']] = $player;
        ksort($homeScorers);
        ksort($awayScorers);
        return array(
            'homeScorers' => $homeScorers,
            'awayScorers' => $awayScorers,
        );
    }

}
