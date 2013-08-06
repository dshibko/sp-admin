<?php

namespace Application\Manager;

use \Application\Model\Entities\DefaultReportContent;
use \Application\Model\DAOs\LeagueUserPlaceDAO;
use \Application\Model\Entities\Match;
use \Application\Model\DAOs\MatchDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use \Application\Manager\UserManager;
use \Application\Manager\ContentManager;
use \Application\Manager\LanguageManager;
use \Application\Manager\PredictionManager;
use \Application\Model\DAOs\MatchLanguageDAO;
use \Application\Model\Entities\FeaturedPlayer;
use \Application\Model\DAOs\FeaturedGoalkeeperDAO;
use \Application\Model\DAOs\FeaturedPlayerDAO;
use \Application\Model\DAOs\FeaturedPredictionDAO;
use \Application\Model\DAOs\MatchGoalDAO;
use \Application\Model\DAOs\LeagueUserDAO;
use \Application\Model\Entities\League;

class MatchManager extends BasicManager
{
    const TOP_SCORERS_NUMBER = 5;
    const HOURS_FROM_NOW = 12;
    const MATCH_REPORT_TOP_SCORERS_NUMBER = 3;
    const TOP_SCORES_NUMBER = 5;
    const POST_MATCH_REPORT_TOP_SCORES_NUMBER = 1;
    const POST_MATCH_REPORT_CORRECT_SCORERS_NUMBER = 3;
    const ALL_SCORERS = -1;
    /**
     * @var MatchManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return MatchManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new MatchManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    private function sortScorers($a, $b)
    {
        if ($a['percentage'] == $b['percentage']){
            if (($a['isUserClub'] && $b['isUserClub']) || (!$a['isUserClub'] && !$b['isUserClub'])){
                return strcmp($a['playerName'], $b['playerName']);
            }
            return $b['isUserClub'] - $a['isUserClub'];
        }
        return $a['percentage'] < $b['percentage'] ? 1 : -1;
    }

    /**
     * @param array $leagueUser
     * @param int $matchId
     * @return array
     */
    private function getLeagueUserMovement(array $leagueUser, $matchId)
    {
        $leagueUserPlaceDAO = LeagueUserPlaceDAO::getInstance($this->getServiceLocator());
        $leagueUserPlace = $leagueUserPlaceDAO->getLeagueUserPlace($leagueUser['id'], $matchId, true);
        $movement = array();
        if (isset($leagueUserPlace['previousPlace']) && isset($leagueUserPlace['place'])){

            $movementPlaces = $leagueUserPlace['previousPlace'] - $leagueUserPlace['place'];
            $direction = LeagueManager::USER_LEAGUE_MOVEMENT_SAME;
            if ($movementPlaces > 0){
                $direction = LeagueManager::USER_LEAGUE_MOVEMENT_UP;
            }elseif ($movementPlaces < 0){
                $direction = LeagueManager::USER_LEAGUE_MOVEMENT_DOWN;
            }
            $movement = array(
                'places' => abs($movementPlaces),
                'direction' => $direction
            );
        }
        return $movement;
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Match
     */
    public function getNextMatch($hydrate = false, $skipCache = false)
    {
        return MatchDAO::getInstance($this->getServiceLocator())->getNextMatch($hydrate, $skipCache);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Match
     */
    public function getPrevMatch($hydrate = false, $skipCache = false)
    {
        return MatchDAO::getInstance($this->getServiceLocator())->getPrevMatch($hydrate, $skipCache);
    }

    /**
     * @param $fromTime
     * @param $season
     * @param bool $skipCache
     * @return int
     */
    public function getMatchesLeftInTheSeasonNumber($fromTime, $season, $skipCache = false)
    {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getMatchesLeftInTheSeasonNumber($fromTime, $season, $skipCache);
    }

    /**
     * @param $user
     * @param $season
     * @param bool $skipCache
     * @return int
     */
    public function getFinishedMatchesInTheSeasonNumber($user, $season, $skipCache = false)
    {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getFinishedMatchesInTheSeasonNumber($user, $season, $skipCache);
    }

    /**
     * @param bool $skipCache
     * @return int
     */
    public function getFinishedNotViewedMatchesInTheSeasonNumber($skipCache = false)
    {
        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season == null) return 0;
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getFinishedNotViewedMatchesInTheSeasonNumber($user, $season, $skipCache);
    }

    /**
     * @param $fromTime
     * @param $season
     * @param bool $skipCache
     * @return int
     */
    public function getLiveMatchesNumber($fromTime, $season, $skipCache = false)
    {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getLiveMatchesNumber($fromTime, $season, $skipCache);
    }

    /**
     * @param $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getMatchesLeftInTheSeason($season, $hydrate = false, $skipCache = false)
    {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        $matches = $matchDAO->getMatchesLeftInTheSeason($season, $hydrate, $skipCache);
        if ($hydrate)
            foreach ($matches as &$match) {
                $match['localStartTime'] = ApplicationManager::getInstance($this->getServiceLocator())->getLocalTime($match['startTime'], $match['timezone']);
                $match['isLive'] = $match['status'] == Match::LIVE_STATUS || ($match['status'] == Match::PRE_MATCH_STATUS && $match['startTime'] < new \DateTime());
            }
        return $matches;
    }

    /**
     * @return array
     */
    public function getAllMatches()
    {
        return MatchDAO::getInstance($this->getServiceLocator())->getAllMatches();
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Match
     */
    public function getMatchById($id, $hydrate = false, $skipCache = false)
    {
        return MatchDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    /**
     * @param \Application\Model\Entities\Match $match
     * @param array $languagesData
     * @return \Application\Model\Entities\Match
     */
    public function save(\Application\Model\Entities\Match $match, array $languagesData = array())
    {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        $matchDAO->save($match, false, false);

        $matchLanguageDAO = \Application\Model\DAOs\MatchLanguageDAO::getInstance($this->getServiceLocator());
        $featuredPlayerDAO = FeaturedPlayerDAO::getInstance($this->getServiceLocator());
        $featuredGoalkeeperDAO = FeaturedGoalkeeperDAO::getInstance($this->getServiceLocator());
        $featuredPredictionDAO = FeaturedPredictionDAO::getInstance($this->getServiceLocator());

        $imageManager = ImageManager::getInstance($this->getServiceLocator());

        if (!empty($languagesData)) {
            foreach ($languagesData as $id => $languageRow) {
                $language = LanguageManager::getInstance($this->getServiceLocator())->getLanguageById($id);
                if (!$language) {
                    continue;
                }
                $matchLanguages = $match->getMatchLanguages();
                $languageid = $language->getId();
                $reportKey = null;

                //Check if language has already exist
                if (!$matchLanguages->exists(
                    function ($key, $element) use ($languageid, &$reportKey) {
                        if ($element->getLanguage()->getId() == $languageid) {
                            $reportKey = $key;
                            return true;
                        }
                        return false;
                    })
                ) {
                    $matchLanguage = new \Application\Model\Entities\MatchLanguage();
                    $matchLanguage->setMatch($match)
                        ->setLanguage($language);

                } else {
                    $matchLanguage = $matchLanguages->get($reportKey);
                }

                //Set pre match report
                if (!empty($languageRow['pre_match_report'])){
                    $matchLanguage->setPreMatchReportTitle($languageRow['pre_match_report']['title'])
                        ->setPreMatchReportIntro($languageRow['pre_match_report']['intro']);
                    //Set header image
                    if (!empty($languageRow['pre_match_report']['header_image_path'])) {
                        $imageManager->deleteImage($matchLanguage->getPreMatchReportHeaderImagePath());
                        $matchLanguage->setPreMatchReportHeaderImagePath($languageRow['pre_match_report']['header_image_path']);
                    }
                }

                //Set post match report
                if (!empty($languageRow['post_match_report'])){
                    $matchLanguage->setPostMatchReportTitle($languageRow['post_match_report']['title'])
                        ->setPostMatchReportIntro($languageRow['post_match_report']['intro']);
                    //Set header image
                    if (!empty($languageRow['post_match_report']['header_image_path'])) {
                        $imageManager->deleteImage($matchLanguage->getPostMatchReportHeaderImagePath());
                        $matchLanguage->setPostMatchReportHeaderImagePath($languageRow['post_match_report']['header_image_path']);
                    }
                }

                //Set Featured Player
                if (!empty($languageRow['featured_player'])) {
                    $featuredPlayer = $matchLanguage->getFeaturedPlayer();
                    if (is_null($featuredPlayer)) {
                        $featuredPlayer = new \Application\Model\Entities\FeaturedPlayer();
                    }

                    $player = PlayerManager::getInstance($this->getServiceLocator())->getPlayerById($languageRow['featured_player']['id']);

                    $featuredPlayer->setPlayer($player)
                        ->setMatchesPlayed((int)$languageRow['featured_player']['matches_played'])
                        ->setGoals((int)$languageRow['featured_player']['goals'])
                        ->setMatchStarts((int)$languageRow['featured_player']['match_starts'])
                        ->setMinutesPlayed((int)$languageRow['featured_player']['minutes_played']);
                    $featuredPlayerDAO->save($featuredPlayer, false, false);
                    $matchLanguage->setFeaturedPlayer($featuredPlayer);
                }

                //Set Featured Goalkeeper
                if (!empty($languageRow['featured_goalkeeper'])) {
                    //$featuredGoalkeeperDAO->get
                    $featuredGoalkeeper = $matchLanguage->getFeaturedGoalKeeper();
                    if (is_null($featuredGoalkeeper)) {
                        $featuredGoalkeeper = new \Application\Model\Entities\FeaturedGoalkeeper();
                    }
                    $player = PlayerManager::getInstance($this->getServiceLocator())->getPlayerById($languageRow['featured_goalkeeper']['id']);
                    $featuredGoalkeeper->setPlayer($player)
                        ->setSaves((int)$languageRow['featured_goalkeeper']['saves'])
                        ->setMatchesPlayed((int)$languageRow['featured_goalkeeper']['matches_played'])
                        ->setPenaltySaves((int)$languageRow['featured_goalkeeper']['penalty_saves'])
                        ->setCleanSheets((int)$languageRow['featured_goalkeeper']['clean_sheets']);
                    $featuredGoalkeeperDAO->save($featuredGoalkeeper, false, false);

                    $matchLanguage->setFeaturedGoalKeeper($featuredGoalkeeper);

                }

                //Set Featured Prediction
                if (!empty($languageRow['featured_prediction'])) {
                    $featuredPrediction = $matchLanguage->getFeaturedPrediction();
                    if (is_null($featuredPrediction)) {
                        $featuredPrediction = new \Application\Model\Entities\FeaturedPrediction();
                    }
                    $featuredPrediction->setName($languageRow['featured_prediction']['name'])
                        ->setCopy($languageRow['featured_prediction']['copy']);
                    if (!empty($languageRow['featured_prediction']['image'])) {
                        $imageManager->deleteImage($featuredPrediction->getImagePath());
                        $featuredPrediction->setImagePath($languageRow['featured_prediction']['image']);
                    }
                    $featuredPredictionDAO->save($featuredPrediction, false, false);
                    $matchLanguage->setFeaturedPrediction($featuredPrediction);
                }
                $matchLanguageDAO->save($matchLanguage, false, false);
            }

        }

        $matchDAO->flush();
        $matchDAO->clearCache();
        $matchLanguageDAO->clearCache();
        $featuredPredictionDAO->clearCache();
        $featuredPlayerDAO->clearCache();
        $featuredPredictionDAO->clearCache();

        return $match;
    }

    /**
     * @param \Application\Model\Entities\Match $match
     * @return array
     */
    public function getMatchAnalytics(\Application\Model\Entities\Match $match)
    {
        $userManager = UserManager::getInstance($this->getServiceLocator());
        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $analytics = array(
            'predictions_number' => 0, //Total number of predictions
            'number_of_predictions_per_hour' => array(), //number of predictions each hour over time
            'made_prediction' => 0, // Percentage of registered users that made a prediction
            'top_scores' => array(), // Top most popular scores
            'top_scorers' => array(), // Top most popular scorers
            'correct_result' => 0, // Percentage of users with correct result
            'correct_score' => 0, //Percentage of users with correct score
            'correct_scorers' => 0, //Percentage of users with correct scorers
            'correct_scorers_order' => 0, //Percentage of users with correct score order,
            'perfect_result' => array(
                'percentage' => 0,
                'users' => array()
            ) //Percentage of users with prefect result (list of these users)
        );
        $totalNumberOfPredictions = $predictionManager->getMatchPredictionsCount($match->getId());

        if ($totalNumberOfPredictions) {
            $registeredUsersCount = $userManager->getRegisteredUsersNumber();
            $topScorers = $predictionManager->getTopScorers($match->getId(), self::TOP_SCORERS_NUMBER, true);
            $topScores = $predictionManager->getTopScores($match->getId(), self::TOP_SCORES_NUMBER, true);
            $numberOfPredictionsPerHour = $predictionManager->getNumberOfPredictionsPerHour($match->getId(), self::HOURS_FROM_NOW);

            //Match full time analytics
            if ($match->getStatus() == Match::FULL_TIME_STATUS) {
                $correctResultCount = $predictionManager->getUsersCountWithCorrectResult($match->getId());
                $correctScoreCount = $predictionManager->getPredictionsCorrectScoreCount($match->getId());
                $matchPredictionPlayersCount = $predictionManager->getPredictionPlayersCount($match->getId());
                $scorersSum = $predictionManager->getPredictionCorrectScorersSum($match->getId());
                $scorersOrderSum = $predictionManager->getPredictionCorrectScorersOrderSum($match->getId(), true);
                $usersWithPerfectResult = $predictionManager->getUsersWithPerfectResult($match->getId());

                if ($totalNumberOfPredictions) {
                    //Percentage of users with correct score
                    $analytics['correct_score'] = round(($correctScoreCount / $totalNumberOfPredictions) * 100);
                    // Percentage of users with correct result
                    $analytics['correct_result'] = round(($correctResultCount / $totalNumberOfPredictions) * 100);
                }
                if ($matchPredictionPlayersCount) {
                    //Percentage of users with correct scorers
                    $analytics['correct_scorers'] = round(($scorersSum / $matchPredictionPlayersCount) * 100);
                    //Percentage of users with correct score order
                    $analytics['correct_scorers_order'] = round(($scorersOrderSum / $matchPredictionPlayersCount) * 100);
                }

                //Percentage of users with prefect result (list of these users)

                $analytics['perfect_result'] = array(
                    'percentage' => round((count($usersWithPerfectResult) / $registeredUsersCount) * 100),
                    'users' => $usersWithPerfectResult
                );
            }
            //Total number of predictions
            $analytics['predictions_number'] = $totalNumberOfPredictions;
            //Number of predictions each hour
            $analytics['number_of_predictions_per_hour'] = $numberOfPredictionsPerHour;
            //Percentage of users that made prediction
            $analytics['made_prediction'] = round(($totalNumberOfPredictions / $registeredUsersCount) * 100);
            //Top most popular scores
            $analytics['top_scores'] = $topScores;
            //Top most popular scores
            $analytics['top_scorers'] = $topScorers;

        }

        return $analytics;
    }

    /**
     * @param $matchId
     * @param $languageId
     * @return array
     */
    public function getPreMatchLanguageReport($matchId, $languageId)
    {
        $report = array();
        $defaultLanguage = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
        $matchLanguageDAO = MatchLanguageDAO::getInstance($this->getServiceLocator());
        $matchLanguage = $matchLanguageDAO->getMatchLanguageByMatchIdAndLanguageId($matchId, $languageId);
        $defaultMatchLanguage = $matchLanguageDAO->getMatchLanguageByMatchIdAndLanguageId($matchId, $defaultLanguage->getId());

        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());

        $matchLanguage = !is_null($matchLanguage) ? $matchLanguage : $defaultMatchLanguage;

        $match = null;
        if (!is_null($matchLanguage)) {
            $preMatchData = $defaultMatchLanguage->getId() != $matchLanguage->getId() ?
                $contentManager->extendContent($defaultMatchLanguage->getArrayCopy(), $matchLanguage->getArrayCopy()) :
                $defaultMatchLanguage->getArrayCopy();

            //Match report title
            $title = $preMatchData['preMatchReportTitle'];
            $report['title'] = !empty($preMatchData['preMatchReportTitle']) ? $title : '';

            //Match report intro
            $intro = $preMatchData['preMatchReportIntro'];
            $report['intro'] = !empty($intro) ? $intro : '';

            //Match report header image
            $headerImage =  $preMatchData['preMatchReportHeaderImagePath'];
            $report['headerImage'] = !empty($headerImage) ? $headerImage : '';

            //Match report featured player
            $featuredPlayer = $matchLanguage->getFeaturedPlayer();
            if (empty($featuredPlayer) || !$featuredPlayer->getPlayer()){
                $featuredPlayer = $defaultMatchLanguage->getFeaturedPlayer();
            }
            if (!is_null($featuredPlayer) && $featuredPlayer->getPlayer()){
                $report['featuredPlayer'] = array(
                    'displayName' => $featuredPlayer->getPlayer()->getDisplayName(),
                    'goals' => (int)$featuredPlayer->getGoals(),
                    'matchesPlayed' => (int)$featuredPlayer->getMatchesPlayed(),
                    'matchStarts' => (int)$featuredPlayer->getMatchStarts(),
                    'minutesPlayed' => (int)$featuredPlayer->getMinutesPlayed(),
                    'backgroundImage' => $featuredPlayer->getPlayer()->getBackgroundImagePath(),
                );
            }

            //Match report featured goalkeeper
            $featuredGoalkeeper = $matchLanguage->getFeaturedGoalKeeper();
            if (empty($featuredGoalkeeper) || !$featuredGoalkeeper->getPlayer()){
                $featuredGoalkeeper = $defaultMatchLanguage->getFeaturedGoalKeeper();
            }
            if (!is_null($featuredGoalkeeper) && $featuredGoalkeeper->getPlayer()){
                $report['featuredGoalkeeper'] = array(
                    'displayName' => $featuredGoalkeeper->getPlayer()->getDisplayName(),
                    'saves' => (int)$featuredGoalkeeper->getSaves(),
                    'matchesPlayed' => (int)$featuredGoalkeeper->getMatchesPlayed(),
                    'penaltySaves' => (int)$featuredGoalkeeper->getPenaltySaves(),
                    'cleanSheets' => (int)$featuredGoalkeeper->getCleanSheets(),
                    'backgroundImage' => $featuredGoalkeeper->getPlayer()->getBackgroundImagePath(),
                );
            }

            //Match report featured prediction
            $featuredPrediction = $matchLanguage->getFeaturedPrediction();
            if (is_null($featuredPrediction)){
                $featuredPrediction = $defaultMatchLanguage->getFeaturedPrediction();
            }
            if (!is_null($featuredPrediction)){
                $report['featuredPrediction'] = array(
                    'name' => $featuredPrediction->getName(),
                    'copy' => $featuredPrediction->getCopy(),
                    'backgroundImage' => $featuredPrediction->getImagePath()
                );
             }

            $match = $matchLanguage->getMatch();

            //Check featured player or goalkeeper to display
            if (is_null($matchLanguage->getDisplayFeaturedPlayer())){
                if (!empty($report['featuredGoalkeeper']) && !empty($report['featuredPlayer'])){
                    $displayFeaturedPlayer = rand(0,1);
                    $matchLanguages = $match->getMatchLanguages();
                    foreach($matchLanguages as $mLanguage){
                        $mLanguage->setDisplayFeaturedPlayer($displayFeaturedPlayer);
                        $matchLanguageDAO->save($mLanguage, false,false);
                    }
                    $matchLanguageDAO->flush();
                    $matchLanguageDAO->clearCache();
                }
            }
            //Display only featured player or featured goalkeeper
            if ($matchLanguage->getDisplayFeaturedPlayer()){
                  if (isset($report['featuredGoalkeeper'])){
                      unset($report['featuredGoalkeeper']);
                  }
            }elseif (!is_null($matchLanguage->getDisplayFeaturedPlayer())){
                if (isset($report['featuredPlayer'])){
                    unset($report['featuredPlayer']);
                }
            }
        }

        $defaultReportContent = $contentManager->getDefaultReportContentByTypeAndLanguage($languageId, DefaultReportContent::PRE_MATCH_TYPE, true);
        $defaultLanguageReportContent = $contentManager->getDefaultReportContentByTypeAndLanguage($defaultLanguage->getId(), DefaultReportContent::PRE_MATCH_TYPE, true);

        $defaultReportContent = $contentManager->extendContent($defaultLanguageReportContent, $defaultReportContent);
        if (!empty($defaultReportContent)) {
            $report = $contentManager->extendContent($defaultReportContent, $report);
        }

        if (is_null($match)) {
            $match = MatchManager::getInstance($this->getServiceLocator())->getMatchById($matchId);
        }
        $totalNumberOfPredictions = $predictionManager->getMatchPredictionsCount($match->getId());
        if ($totalNumberOfPredictions) {
            if ($applicationManager->getAppEdition() == ApplicationManager::CLUB_EDITION) {
                //Match report top predicted scorers
                $topScorers = $predictionManager->getTopScorers($match->getId(), self::ALL_SCORERS, true);
                $matchPredictionPlayersCount = $predictionManager->getPredictionPlayersCount($match->getId());
                if (!empty($topScorers) && $matchPredictionPlayersCount){
                    $appClub = $applicationManager->getAppClub();
                    $scorers = array();
                    foreach($topScorers as $scorer){
                        $scorers[] = array(
                            'playerName' => $scorer['player_name'],
                            'percentage' => round( ($scorer['scorers_count'] / $matchPredictionPlayersCount) * 100),
                            'isUserClub' => ($scorer['team_id'] == $appClub->getId()),
                            'backgroundImage' => $scorer['backgroundImagePath']
                        );
                    }
                    usort($scorers,array($this, 'sortScorers'));
                    $scorers = array_slice($scorers, 0, self::MATCH_REPORT_TOP_SCORERS_NUMBER);
                    $report['topScorers'] = array(
                        'scorers' => $scorers
                    );
                    //Get User Club player background image
                    if (!empty($scorers)){
                        foreach($scorers as $scorer){
                            if ($scorer['isUserClub']){
                                $report['topScorers']['backgroundImage'] = $scorer['backgroundImage'];
                                break;
                            }
                        }
                    }
                }
            }
            //Match report top predicted scores
            $topScores = $predictionManager->getTopScores($match->getId(), self::TOP_SCORES_NUMBER, true);
            if (!empty($topScores)) {
                $scores = array();
                foreach($topScores as $score) {
                    $scores[$score['home_team_score'] . '-' . $score['away_team_score']]  = round( ($score['scores_count'] / $totalNumberOfPredictions) * 100 );
                }
                $report['topScores'] = $scores;
            }
        }


        return $report;
    }

    /**
     * @param array $fieldsets
     * @param array $teamIds
     * @param array $positions
     * @param $fieldName
     * @return array
     */
    public function getFieldsetWithPlayers(array $fieldsets, array $teamIds, array $positions, $fieldName)
    {
        $playerManager = PlayerManager::getInstance($this->getServiceLocator());
        $players = $playerManager->getInstance($this->getServiceLocator())->getPlayersByPositionsFromTeams($positions, $teamIds, true);
        $playersOptions = $playerManager->getPlayersSelectOptions($players);
        foreach($fieldsets as &$fieldset){
            if ($fieldset->has($fieldName)){
                $fieldset->get($fieldName)->setValueOptions($playersOptions);
            }
        }
        unset($fieldset);
        return $fieldsets;
    }

    /**
     * @param $matchId
     * @param $languageId
     * @return array
     */
    public function getPostMatchLanguageReport($matchId, $languageId)
    {
        $report = array();
        $defaultLanguage = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
        $matchLanguageDAO = MatchLanguageDAO::getInstance($this->getServiceLocator());
        $matchLanguage = $matchLanguageDAO->getMatchLanguageByMatchIdAndLanguageId($matchId, $languageId);
        $defaultMatchLanguage = $matchLanguageDAO->getMatchLanguageByMatchIdAndLanguageId($matchId, $defaultLanguage->getId());

        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());

        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());

        $matchLanguage = !is_null($matchLanguage) ? $matchLanguage : $defaultMatchLanguage;

        $match = null;
        if (!is_null($matchLanguage)) {
            $preMatchData = $defaultMatchLanguage->getId() != $matchLanguage->getId() ?
                $contentManager->extendContent($defaultMatchLanguage->getArrayCopy(), $matchLanguage->getArrayCopy()) :
                $defaultMatchLanguage->getArrayCopy();

            //Match report title
            $title = $preMatchData['postMatchReportTitle'];
            $report['title'] = !empty($preMatchData['postMatchReportTitle']) ? $title : '';

            //Match report intro
            $intro = $preMatchData['postMatchReportIntro'];
            $report['intro'] = !empty($intro) ? $intro : '';

            //Match report header image
            $headerImage =  $preMatchData['postMatchReportHeaderImagePath'];
            $report['headerImage'] = !empty($headerImage) ? $headerImage : '';

            $match = $matchLanguage->getMatch();
        }

        if (is_null($match)){
            $match = MatchManager::getInstance($this->getServiceLocator())->getMatchById($matchId);
        }

        $totalNumberOfPredictions = $predictionManager->getMatchPredictionsCount($match->getId());

        if ($totalNumberOfPredictions){
            //Match report top predicted scores
            $topScores = $predictionManager->getTopScores($match->getId(), self::POST_MATCH_REPORT_TOP_SCORES_NUMBER);
            if (!empty($topScores[0])){//Get top predicted score
                $report['topScore'] = $topScores[0];
            }

            //Match report correct scorers percentage
            if ($applicationManager->getAppEdition() == ApplicationManager::CLUB_EDITION) {
                $correctScorers = $this->getMatchCorrectScorers($match);

                if (!empty($correctScorers)){
                    $correctScorersIds = array();
                    foreach($correctScorers as $correctScorer){
                        $correctScorersIds[] = $correctScorer->getPlayer()->getId();
                    }
                    if (!empty($correctScorersIds)){
                        $appClub = $applicationManager->getAppClub();
                        $scorersPredictionsCount = $predictionManager->getCorrectScorersPredictionsCount($match->getId(), $correctScorersIds, true);
                        $matchPredictionPlayersCount = $predictionManager->getPredictionPlayersCount($match->getId());

                        if (!empty($scorersPredictionsCount)){
                            $scorers = array();
                            foreach($scorersPredictionsCount as $scorerCount){
                                $scorers[] = array(
                                    'playerName' => $scorerCount['player_name'],
                                    'percentage' => round( ($scorerCount['predictions_count'] / $matchPredictionPlayersCount) * 100),
                                    'isUserClub' =>  ($scorerCount['teamId'] == $appClub->getId()),
                                    'backgroundImage' => $scorerCount['backgroundImagePath']
                                );
                            }
                            usort($scorers,array($this, 'sortScorers'));
                            $scorers = array_slice($scorers, 0, self::POST_MATCH_REPORT_CORRECT_SCORERS_NUMBER);
                            $report['correctScorers'] = array(
                                'scorers' => $scorers,
                            );

                            //Get User Club player background image
                            if (!empty($scorers)){
                                foreach($scorers as $scorer){
                                    if ($scorer['isUserClub']){
                                        $report['correctScorers']['backgroundImage'] = $scorer['backgroundImage'];
                                        break;
                                    }
                                }
                            }
                        }

                    }
                }
            }

            //Match report correctly predicted result
            if ($totalNumberOfPredictions){
                $correctResultCount = $predictionManager->getUsersCountWithCorrectResult($match->getId());
                $report['correctResult'] = round( ($correctResultCount / $totalNumberOfPredictions) * 100);
            }
        }

        $defaultReportContent = $contentManager->getDefaultReportContentByTypeAndLanguage($languageId, DefaultReportContent::POST_MATCH_TYPE, true);
        $defaultLanguageReportContent = $contentManager->getDefaultReportContentByTypeAndLanguage($defaultLanguage->getId(), DefaultReportContent::POST_MATCH_TYPE, true);

        $defaultReportContent = $contentManager->extendContent($defaultLanguageReportContent, $defaultReportContent);
        if (!empty($defaultReportContent)) {
            $report = $contentManager->extendContent($defaultReportContent, $report);
        }

        //Leagues Positions

        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        $globalLeague = $applicationManager->getGlobalLeague($season);

        $leagueManager = LeagueManager::getInstance($this->getServiceLocator());

        if ($user !== null) {
            //Global League
            $globalUserLeague = $leagueUserDAO->getLeagueUser($globalLeague->getId(), $user->getId(), true);

            if (!empty($globalUserLeague)) {
                $movement = $this->getLeagueUserMovement($globalUserLeague, $matchId);

                if (!empty($movement)) {
                    $movement['name'] = $leagueManager->getLeagueDisplayName($globalLeague->getId());
                    $report['leaguesMovement']['global'] = $movement;
                }
            }

            //Regional League
            $region = $user->getCountry()->getRegion();
            if (!is_null($region)) {
                $regionalLeague = $applicationManager->getRegionalLeague($region, $season);
                if(!is_null($regionalLeague)) {
                    $regionalUserLeague = $leagueUserDAO->getLeagueUser($regionalLeague->getId(), $user->getId(), true);
                    if (!empty($regionalUserLeague)) {
                        $movement = $this->getLeagueUserMovement($regionalUserLeague, $matchId);
                        if (!empty($movement)) {
                            $movement['name'] = $leagueManager->getLeagueDisplayName($regionalLeague->getId());
                            $report['leaguesMovement']['regional'] = $movement;
                        }
                    }
                }
            }
        }

        return $report;
    }


    /**
     * @param Match $match
     * @param $limit
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getMatchCorrectScorers(\Application\Model\Entities\Match $match, $limit = -1, $hydrate = false, $skipCache = false)
    {
        return MatchGoalDAO::getInstance($this->getServiceLocator())->getMatchScorers($match->getId(), $limit, $hydrate, $skipCache);
    }

    /**
     * @param \DateTime $matchStartTime
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return int
     */
    public function getUpcomingMatchNumber($matchStartTime, $season, $skipCache = false) {
        return MatchDAO::getInstance($this->getServiceLocator())->getUpcomingMatchNumber(new \DateTime(), $matchStartTime, $season, $skipCache);
    }

    /**
     * @param \Application\Model\Entities\User $user
     * @param \Application\Model\Entities\Season $season
     * @param \DateTime $matchStartTime
     * @param bool $skipCache
     * @return integer
     */
    public function getFinishedMatchNumber($user, $matchStartTime, $season, $skipCache = false) {
        return MatchDAO::getInstance($this->getServiceLocator())->getFinishedMatchNumber($user, $season, $matchStartTime, $skipCache);
    }

    /**
     * @param $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return Match|array
     */
    public function getMatchInfo($matchId, $hydrate = false, $skipCache = false) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getMatchInfo($matchId, $hydrate, $skipCache);
    }

    /**
     * @param $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return Match|array
     */
    public function getMatchGoals($matchId, $hydrate = false, $skipCache = false) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getMatchGoals($matchId, $hydrate, $skipCache);
    }

    public function getUnfinishedAndPredictableMatches($season, $hydrate = false, $skipCache = false) {
        $liveMatchesNumber = $this->getLiveMatchesNumber(new \DateTime(), $season);
        $matchesLeft = $this->getMatchesLeftInTheSeason($season, $hydrate, $skipCache);
        $settingsManager = SettingsManager::getInstance($this->getServiceLocator());
        $maxAhead = $settingsManager->getSetting(SettingsManager::AHEAD_PREDICTIONS_DAYS, true);
        $matches = array_slice($matchesLeft, 0, $maxAhead + $liveMatchesNumber);
        return $matches;
    }

    public function getHasFinishedMatches($season, $skipCache = false) {
        return MatchDAO::getInstance($this->getServiceLocator())->getHasFinishedMatches($season, $skipCache);
    }
}