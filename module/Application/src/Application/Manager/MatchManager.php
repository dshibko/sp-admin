<?php

namespace Application\Manager;

use \Application\Model\Entities\Match;
use \Application\Model\DAOs\MatchDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use \Application\Manager\UserManager;
use \Application\Manager\PredictionManager;

class MatchManager extends BasicManager
{
    const TOP_SCORERS_NUMBER = 5;
    const HOURS_FROM_NOW = 12;
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

    public function getMatchesLeftInTheSeasonNumber($fromTime, $season, $skipCache = false)
    {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getMatchesLeftInTheSeasonNumber($fromTime, $season, $skipCache);
    }

    public function getLiveMatchesNumber($fromTime, $season, $skipCache = false)
    {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getLiveMatchesNumber($fromTime, $season, $skipCache);
    }

    public function getMatchesLeftInTheSeason($fromTime, $season, $hydrate = false, $skipCache = false)
    {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        $matches = $matchDAO->getMatchesLeftInTheSeason($fromTime, $season, $hydrate, $skipCache);
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
     */
    public function save(\Application\Model\Entities\Match $match)
    {
        MatchDAO::getInstance($this->getServiceLocator())->save($match);
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
        $predictions = $match->getPredictions();

        if ($predictions->count()){
            $totalNumberOfPredictions = $predictions->count();
            //Get prediction ids
            $predictionIds = array();
            foreach($predictions as $prediction){
                $predictionIds[] = $prediction->getId();
            }
            $registeredUsersCount = $userManager->getRegisteredUsersNumber();
            $topScorers = $predictionManager->getTopScorers($predictionIds,self::TOP_SCORERS_NUMBER, true);
            $topScores = $predictionManager->getTopScores($predictionIds,self::TOP_SCORERS_NUMBER, true);
            $numberOfPredictionsPerHour = $predictionManager->getNumberOfPredictionsPerHour($predictionIds, self::HOURS_FROM_NOW);

            //Match full time analytics
            if ($match->getStatus() == Match::FULL_TIME_STATUS){
                $correctResultCount = $predictionManager->getUsersCountWithCorrectResult($predictionIds);
                $correctScoreCount = $predictionManager->getUsersCountWithCorrectScore($predictionIds);
                $matchPredictionPlayersCount = $predictionManager->getPredictionPlayersCount($predictionIds);
                $scorersSum = $predictionManager->getPredictionCorrectScorersSum($predictionIds);
                $scorersOrderSum = $predictionManager->getPredictionCorrectScorersOrderSum($predictionIds, true);
                $usersWithPerfectResult = $predictionManager->getUsersWithPerfectResult($predictionIds);

                if ($totalNumberOfPredictions){
                    //Percentage of users with correct score
                    $analytics['correct_score'] = round(($correctScoreCount / $totalNumberOfPredictions) * 100);
                    // Percentage of users with correct result
                    $analytics['correct_result'] = round(($correctResultCount / $totalNumberOfPredictions) * 100);
                }
                 if ($matchPredictionPlayersCount){
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

}