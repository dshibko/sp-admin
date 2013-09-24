<?php

namespace Application\Controller;

use Application\Manager\LeagueManager;
use \Neoco\Exception\InfoException;
use \Neoco\Exception\OutOfSeasonException;
use \Application\Manager\ShareManager;
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
use \Application\Manager\LanguageManager;
use \Application\Manager\UserManager;

class PredictController extends AbstractActionController {

    const PREDICT_ROUTE = 'predict';

    public function indexAction() {

        try {

            $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
            $matchManager = MatchManager::getInstance($this->getServiceLocator());
            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
            $settingsManager = SettingsManager::getInstance($this->getServiceLocator());
            $userManager = UserManager::getInstance($this->getServiceLocator());

            $maxAhead = $settingsManager->getSetting(SettingsManager::AHEAD_PREDICTIONS_DAYS);

            $ahead = (int) $this->params()->fromRoute('ahead', 0);

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();
            if ($season == null)
                throw new OutOfSeasonException();

            $facebookShareCopy = $twitterShareCopy = $securityKey = '';

            //Get setup form
            if (!$userManager->getIsUserActive($user)) {
                $setUpForm = $this->getServiceLocator()->get('Application\Form\SetUpForm');
                $country = $user->getCountry() == null ? $userManager->getUserGeoIpCountry() : $user->getCountry();
                $language = $user->getLanguage() == null ? $userManager->getUserLanguage() : $user->getLanguage();
                $setUpForm ->get('region')->setValue($country->getId());
                $setUpForm ->get('language')->setValue($language->getId());
            }

            $matchesLeft = $matchManager->getMatchesLeftInTheSeasonNumber(new \DateTime(), $season);

            if ($matchesLeft == 0)
                throw new InfoException(MessagesConstants::ERROR_NO_MORE_MATCHES_IN_THE_SEASON);

            $liveMatches = $matchManager->getLiveMatchesNumber(new \DateTime(), $season);

            if ($ahead > $maxAhead + $liveMatches - 1)
                throw new InfoException(sprintf(MessagesConstants::ERROR_PREDICT_THIS_MATCH_NOT_ALLOWED, $maxAhead));

            if ($ahead > $matchesLeft + $liveMatches - 1)
                return $this->notFoundAction();

            $currentMatch = $predictionManager->getNearestMatchWithPrediction($ahead, $user, $season);

            if ($currentMatch == null)
                return $this->notFoundAction();

            //Match report
            $userLanguage = $user->getLanguage();
            $matchReport = $matchManager->getPreMatchLanguageReport($currentMatch['id'], $userLanguage->getId());

            $matchPredictionsCount = $predictionManager->getMatchPredictionsCount($currentMatch['id']);
            if ($matchPredictionsCount > 0) {
                $clubVictoryPredictions = $predictionManager->getClubWinPredictionsCount($currentMatch['id'], $currentMatch['homeId'], $applicationManager->getAppClub()->getId());
                $matchReport['clubVictoryPredictionsPercentage'] = round( ($clubVictoryPredictions / $matchPredictionsCount) * 100 );
                $matchReport['currentClub'] = $applicationManager->getAppClub()->getDisplayName();
            }

            if ($currentMatch['status'] == Match::PRE_MATCH_STATUS) {

                $securityKey = $this->generateSecurityKey(array($currentMatch['id'], $currentMatch['homeId'], $currentMatch['awayId']));

                $request = $this->getRequest();
                if ($request->isPost()) {
                    $post = $request->getPost()->toArray();
                    $this->checkSecurityKey(array($currentMatch['id'], $currentMatch['homeId'], $currentMatch['awayId']), $post);
                    if (array_key_exists('home-team-score', $post) && is_numeric($post['home-team-score']) &&
                        array_key_exists('away-team-score', $post) && is_numeric($post['away-team-score'])) {
                        $homeTeamScore = $post['home-team-score'];
                        $awayTeamScore = $post['away-team-score'];
                        $homeTeamScore = (int)$homeTeamScore;
                        $awayTeamScore = (int)$awayTeamScore;
                        $scoresData = array();
                        $sidesArray = array(
                            'home' => $homeTeamScore,
                            'away' => $awayTeamScore,
                        );
                        foreach ($sidesArray as $side => $score)
                            for ($i = 0; $i < $score; $i++) {
                                $scorerKey = $side . '-team-scorer-' . ($i + 1);
                                $scorer = array_key_exists($scorerKey, $post) && is_numeric($post[$scorerKey]) ? $post[$scorerKey] : null;
                                $scoresData [] = array(
                                    'side' => $side,
                                    'scorer' => $scorer != -1 ? $scorer : null,
                                    'order' => $i + 1,
                                );
                            }

                        $predictionManager->predict($currentMatch['id'], $user, $homeTeamScore, $awayTeamScore, $scoresData);

                        return $this->redirect()->toRoute(self::PREDICT_ROUTE, array('ahead' => $ahead > 0 ? $ahead : null));
                    }
                }
            }

            if (!empty($currentMatch['prediction'])) {
                $predictionPlayers = $currentMatch['prediction']['predictionPlayers'];
                $homeScorers = array();
                $awayScorers = array();
                foreach ($predictionPlayers as $predictionPlayer) {
                    if ($predictionPlayer['teamId'] == $currentMatch['homeId']) {
                        if ($currentMatch['status'] == Match::LIVE_STATUS) {
                            $playerName = '';
                            if ($predictionPlayer['playerId'] != null)
                                foreach ($currentMatch['homeSquad'] as $player)
                                    if ($player['id'] == $predictionPlayer['playerId']) {
                                        $playerName = $player['displayName'];
                                        break;
                                    }
                            $predictionPlayer['playerName'] = $playerName;
                        }
                        $homeScorers[$predictionPlayer['order']] = $predictionPlayer;
                    }
                    else if ($predictionPlayer['teamId'] == $currentMatch['awayId']) {
                        if ($currentMatch['status'] == Match::LIVE_STATUS) {
                            $playerName = '';
                            if ($predictionPlayer['playerId'] != null)
                                foreach ($currentMatch['awaySquad'] as $player)
                                    if ($player['id'] == $predictionPlayer['playerId']) {
                                        $playerName = $player['displayName'];
                                        break;
                                    }
                            $predictionPlayer['playerName'] = $playerName;
                        }
                        $awayScorers[$predictionPlayer['order']] = $predictionPlayer;
                    }
                }
                ksort($homeScorers);
                ksort($awayScorers);
                $currentMatch['prediction']['homeScorers'] = $homeScorers;
                $currentMatch['prediction']['awayScorers'] = $awayScorers;
                unset($currentMatch['prediction']['predictionPlayers']);

                // settings of share copy
                $shareManager = ShareManager::getInstance($this->getServiceLocator());
                list($facebookShareCopy, $twitterShareCopy) = $shareManager->getPredictionCopy($user);
                list($facebookPredictionShareCopy, $twitterPredictionShareCopy) = !empty($currentMatch['prediction']) ? $shareManager->getSharingPredictionCopy() : array();
            }

            if ($maxAhead > $matchesLeft)
                $maxAhead = $matchesLeft;

            $params = array(
                'current' => $currentMatch,
                'ahead' => $ahead,
                'maxAhead' => $maxAhead,
                'liveMatches' => $liveMatches,
                'securityKey' => $securityKey,
                'matchReport' => $matchReport,
                'matchCode' => $this->encodeInt($currentMatch['id']),
                'predictionCode' => $this->encodeInt($currentMatch['prediction']['id']),
                'facebookShareCopy' => $facebookShareCopy,
                'twitterShareCopy' => $twitterShareCopy,
                'facebookPredictionShareCopy' => $facebookPredictionShareCopy,
                'twitterPredictionShareCopy' => $twitterPredictionShareCopy,
            );
            if (!empty($setUpForm)){
                $params['setUpForm'] = $setUpForm;
            }
            return $params;

        } catch (InfoException $e) {
            return $this->infoAction($e);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

    public function sharedPredictionAction()
    {
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $matchManager = MatchManager::getInstance($this->getServiceLocator());

        $user = $applicationManager->getCurrentUser();
        $predictionCode = (int)$this->params()->fromRoute('predictionCode', '');
        if (empty($predictionCode))
            return $this->notFoundAction();
        $predictionId = $this->decodeInt($predictionCode);

        $season = $applicationManager->getCurrentSeason();
        if ($season == null)
            throw new OutOfSeasonException();

        $currentPrediction = $predictionManager->getMatchPrediction($predictionId, $season, true);
        if ($currentPrediction == null)
            return $this->notFoundAction();

        $currentPrediction['match']['localStartTime'] = $currentPrediction['match']['localStartTime']->format('D d M h.i a');

        $predictionPlayers = $currentPrediction['predictionPlayers'];
        $homeScorers = array();
        $awayScorers = array();
        foreach ($predictionPlayers as $predictionPlayer) {
            if ($predictionPlayer['teamId'] == $currentPrediction['match']['homeId']) {
                if ($currentPrediction['match']['status'] == Match::LIVE_STATUS) {
                    $playerName = '';
                    if ($predictionPlayer['playerId'] != null)
                        foreach ($currentPrediction['match']['homeSquad'] as $player)
                            if ($player['id'] == $predictionPlayer['playerId']) {
                                $playerName = $player['displayName'];
                                break;
                            }
                    $predictionPlayer['playerName'] = $playerName;
                }
                $homeScorers[$predictionPlayer['order']] = $predictionPlayer;
            } else if ($predictionPlayer['teamId'] == $currentPrediction['match']['awayId']) {
                if ($currentPrediction['status'] == Match::LIVE_STATUS) {
                    $playerName = '';
                    if ($predictionPlayer['playerId'] != null)
                        foreach ($currentPrediction['awaySquad'] as $player)
                            if ($player['id'] == $predictionPlayer['playerId']) {
                                $playerName = $player['displayName'];
                                break;
                            }
                    $predictionPlayer['playerName'] = $playerName;
                }
                $awayScorers[$predictionPlayer['order']] = $predictionPlayer;
            }
        }
        ksort($homeScorers);
        ksort($awayScorers);
        $currentPrediction['homeScorers'] = $homeScorers;
        $currentPrediction['awayScorers'] = $awayScorers;
        unset($currentPrediction['predictionPlayers']);


        $userLanguage = $user != null ? $user->getLanguage() : LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
        $matchReport = $matchManager->getPreMatchLanguageReport($currentPrediction['match']['id'], $userLanguage->getId());

        $matchPredictionsCount = $predictionManager->getMatchPredictionsCount($currentPrediction['match']['id']);
        if ($matchPredictionsCount > 0) {
            $clubVictoryPredictions = $predictionManager->getClubWinPredictionsCount($currentPrediction['match']['id'], $currentPrediction['match']['homeId'], $applicationManager->getAppClub()->getId());
            $matchReport['clubVictoryPredictionsPercentage'] = round(($clubVictoryPredictions / $matchPredictionsCount) * 100);
            $matchReport['currentClub'] = $applicationManager->getAppClub()->getDisplayName();
        }

        $shareManager = ShareManager::getInstance($this->getServiceLocator());
        list($facebookShareCopy, $twitterShareCopy) = $user == null ? array() : $shareManager->getPredictionCopy($user);

        $params = array(
            'current' => $currentPrediction,
            'matchReport' => $matchReport,
            'predictionCode' => $this->encodeInt($currentPrediction['id']),
            'matchCode' => $this->encodeInt($currentPrediction['match']['id']),
            'facebookShareCopy' => $facebookShareCopy,
            'twitterShareCopy' => $twitterShareCopy,
        );

        return $params;
    }

}