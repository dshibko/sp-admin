<?php

namespace Application\Controller;

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
use Application\Manager\UserManager;

class PredictController extends AbstractActionController {

    const PREDICT_ROUTE = 'predict';

    public function indexAction() {

        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $matchManager = MatchManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $settingsManager = SettingsManager::getInstance($this->getServiceLocator());

        $currentMatch = array();
        $ahead = $maxAhead = $liveMatches = 0;
        $securityKey = '';
        $matchReport = array();
        try {

            $maxAhead = $settingsManager->getSetting(SettingsManager::AHEAD_PREDICTIONS_DAYS);

            $ahead = (int) $this->params()->fromRoute('ahead', 0);

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();

            //Get setup form
            if (!$user->getIsActive()){
                $userManager = UserManager::getInstance($this->getServiceLocator());
                $setUpForm = $this->getServiceLocator()->get('Application\Form\SetUpForm');
                $country = $userManager->getUserGeoIpCountry();
                $language = $userManager->getUserLanguage();
                $setUpForm ->get('region')->setValue($country->getId());
                $setUpForm ->get('language')->setValue($language->getId());
            }
            if ($season == null)
                throw new \Exception(MessagesConstants::INFO_OUT_OF_SEASON);

            $matchesLeft = $matchManager->getMatchesLeftInTheSeasonNumber(new \DateTime(), $season);

            if ($matchesLeft == 0)
                throw new \Exception(MessagesConstants::ERROR_NO_MORE_MATCHES_IN_THE_SEASON);

            $liveMatches = $matchManager->getLiveMatchesNumber(new \DateTime(), $season);

            if ($ahead > $maxAhead + $liveMatches - 1)
                throw new \Exception(sprintf(MessagesConstants::ERROR_PREDICT_THIS_MATCH_NOT_ALLOWED, $maxAhead));

            if ($ahead > $matchesLeft + $liveMatches - 1)
                throw new \Exception(MessagesConstants::ERROR_MATCH_NOT_FOUND);

            $currentMatch = $predictionManager->getNearestMatchWithPrediction(new \DateTime(), $ahead, $user, $season);

            if ($currentMatch == null)
                throw new \Exception(MessagesConstants::ERROR_MATCH_NOT_FOUND);

            //Match report
            $region = $applicationManager->getUserRegion($user);
            $matchReport = $matchManager->getMatchRegionReport($currentMatch['id'], $region->getId());
            $utcTime = new \DateTime();
            $startUtcTime = $currentMatch['startTime'];
            if ($startUtcTime < $utcTime)
                $currentMatch['status'] = Match::LIVE_STATUS;

            if ($currentMatch['status'] == Match::PRE_MATCH_STATUS) {

                $securityKey = $this->generateSecurityKey(array($currentMatch['id'], $currentMatch['homeId'], $currentMatch['awayId']));

                $request = $this->getRequest();
                if ($request->isPost()) {
                    $post = $request->getPost()->toArray();
                    $this->checkSecurityKey(array($currentMatch['id'], $currentMatch['homeId'], $currentMatch['awayId']), $post);
                    if (array_key_exists('home-team-score', $post) && array_key_exists('away-team-score', $post)) {
                        $homeTeamScore = $post['home-team-score'];
                        $awayTeamScore = $post['away-team-score'];
                        $homeTeamScore = is_numeric($homeTeamScore) ? (int)$homeTeamScore : 0;
                        $awayTeamScore = is_numeric($awayTeamScore) ? (int)$awayTeamScore : 0;
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
            }

            if ($maxAhead > $matchesLeft)
                $maxAhead = $matchesLeft;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        $params = array(
            'current' => $currentMatch,
            'ahead' => $ahead,
            'maxAhead' => $maxAhead,
            'liveMatches' => $liveMatches,
            'securityKey' => $securityKey,
            'matchReport' => $matchReport
        );
        if (!empty($setUpForm)){
            $params['setUpForm'] = $setUpForm;
        }
        return $params;

    }

}
