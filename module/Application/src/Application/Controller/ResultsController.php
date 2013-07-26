<?php

namespace Application\Controller;

use \Neoco\Exception\OutOfSeasonException;
use \Neoco\Exception\InfoException;
use \Application\Model\Entities\AchievementBlock;
use \Application\Manager\ShareManager;
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
use Zend\View\Model\ViewModel;

class ResultsController extends AbstractActionController {

    const RESULTS_ROUTE = 'results';

    public function indexAction() {

        try {
            $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
            $matchManager = MatchManager::getInstance($this->getServiceLocator());
            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

            $firstView = false;
            $facebookShareCopy = $twitterShareCopy = '';
            $achievementBlock = null;

            $back = (int) $this->params()->fromRoute('back', 0);

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();

            if ($season == null)
                throw new OutOfSeasonException();

            $playedMatches = $matchManager->getFinishedMatchesInTheSeasonNumber($user, $season);

            if ($playedMatches == 0)
                throw new InfoException(MessagesConstants::ERROR_NO_FINISHED_MATCHES_IN_THE_SEASON);

            if ($back > $playedMatches - 1)
                return $this->notFoundAction();

            $currentMatch = $predictionManager->getLastMatchWithPrediction($back, $user, $season);

            if ($currentMatch == null)
                return $this->notFoundAction();

            //Match report
            $userLanguage = $applicationManager->getUserLanguage($user);
            $matchReport = $matchManager->getPostMatchLanguageReport($currentMatch['id'], $userLanguage->getId());

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
                $resultKey = $this->getTranslator()->translate(MessagesConstants::INFO_YOU_PREDICTED_THE_DRAW);
            else
                $resultKey = sprintf($this->getTranslator()->translate(MessagesConstants::INFO_YOU_PREDICTED_THE_WINNER), '<b>' . ($homeTeamScore > $awayTeamScore ? $currentMatch['homeName'] : $currentMatch['awayName']) . '</b>');
            $resultPoints = $currentMatch['prediction']['isCorrectResult'] ? ScoringManager::MATCH_RESULT_POINTS : 0;
            $breakpoints[$resultKey] = '+' . $resultPoints;
            $scoreKey = sprintf($this->getTranslator()->translate(MessagesConstants::INFO_YOU_PREDICTED_THE_SCORE), '<b>' . $homeTeamScore . '-'. $awayTeamScore . '</b>');
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
                            if (!array_key_exists($scorerId, $goalScorers))
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
                    $scorersKey = sprintf($this->getTranslator()->translate(MessagesConstants::INFO_YOU_PREDICTED_THE_SCORERS), '<b>' . implode(', ', $scorers) . '</b>');
                    $scorersPoints = ScoringManager::GOAL_SCORER_POINTS * $currentMatch['prediction']['correctScorers'];
                    $breakpoints[$scorersKey] = '+' . $scorersPoints;
                }
                if (count($correctScorersOrder) > 0) {
                    foreach ($correctScorersOrder as $k => $v) {
                        $scorerName = $v;
                        $order = array_shift(explode('-', $k));
                        $teamSide = array_pop(explode('-', $k));
                        $teamName = array_search($teamSide, $sideKeys) == 0 ? $currentMatch['homeName'] : $currentMatch['awayName'];
                        $scorersKey = sprintf($this->getTranslator()->translate(MessagesConstants::INFO_YOU_PREDICTED_SCORER_ORDER), '<b>' . $scorerName . '</b>', '<b>' . $order . '</b>', '<b>' . $teamName . '</b>');
                        $scorersPoints = ScoringManager::GOAL_SCORER_ORDER_POINTS;
                        $breakpoints[$scorersKey] = '+' . $scorersPoints;
                    }
                }
            }

            if ($currentMatch['isDoublePoints']) {
                $doubleKey = $this->getTranslator()->translate(MessagesConstants::INFO_THIS_IS_DOUBLE_POINTS_MATCH);
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
            $accuracyKey = sprintf($this->getTranslator()->translate(MessagesConstants::INFO_YOUR_ACCURACY), '<b>' . $accuracy . '%</b>');
            $breakpoints[$accuracyKey] = '';

            $correctScorerPredictionsBeforeThisMatch = $predictionManager->getUserCorrectScorerPredictionsNumber($season, $user, $currentMatch['startTime']);
            $firstCorrectScorer = ($correctScorerPredictionsBeforeThisMatch == 0 && $currentMatch['prediction']['correctScorers'] > 0);
            $hasUserCorrectResultsBeforeThisMatch = $predictionManager->hasUserCorrectResults($season, $user, $currentMatch['startTime']);
            $firstCorrectResult = ($hasUserCorrectResultsBeforeThisMatch === false && $currentMatch['prediction']['isCorrectResult']);

            if ($firstCorrectResult && $firstCorrectScorer) {
                $bool = rand(0, 1) == 1;
                $firstCorrectResult = $bool;
                $firstCorrectScorer = !$bool;
            }

            if ($firstCorrectResult || $firstCorrectScorer) {
                $shareManager = ShareManager::getInstance($this->getServiceLocator());
                if ($firstCorrectResult)
                    $achievementBlock = $shareManager->getAchievementBlockByType(AchievementBlock::CORRECT_RESULT_TYPE);
                if ($firstCorrectScorer)
                    $achievementBlock = $shareManager->getAchievementBlockByType(AchievementBlock::CORRECT_SCORER_TYPE);
                $facebookShareCopy = $achievementBlock->getFacebookShareCopy()->getCopy();
                $twitterShareCopy = $achievementBlock->getTwitterShareCopy()->getCopy();
            }

            return array(
                'current' => $currentMatch,
                'back' => $back,
                'matchReport' => $matchReport,
                'maxBack' => $playedMatches - 1,
                'breakpoints' => $breakpoints,
                'firstResultView' => $firstView,
                'matchCode' => $this->encodeInt($currentMatch['id']),
                'achievementBlock' => $achievementBlock,
                'facebookShareCopy' => $facebookShareCopy,
                'twitterShareCopy' => $twitterShareCopy,
            );

        } catch (InfoException $e) {
            return $this->infoAction($e);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

    public function getScorers(array $data, $currentMatch) {
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