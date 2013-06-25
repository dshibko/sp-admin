<?php

namespace Application\Manager;

use \Neoco\Exception\InfoException;
use \Application\Model\DAOs\PredictionDAO;
use \Application\Model\DAOs\UserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use \Application\Model\Helpers\MessagesConstants;

class StatsManager extends BasicManager {

    const PREDICTED_LAST_DAYS = 30;
    const TOP_SCORERS_COUNT = 5;

    /**
     * @var StatsManager
     */
    private static $instance;

    /**
     * @var  \Application\Model\Entities\Country
     */
    protected $userGeoIpCountry;
    protected $userGeoIpIsoCode;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return StatsManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new StatsManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    public function getFacebookVsDirectContent() {
        $userManager = UserManager::getInstance($this->getServiceLocator());

        $data = array(
            array(
                'facebook' => $userManager->getFacebookUsersNumber(),
                'direct' => $userManager->getDirectUsersNumber(),
            ),
        );

        $exportConfig = array(
            'facebook' => 'number',
            'direct' => 'number',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($data, $exportConfig);
    }

    /**
     * @return string
     */
    public function getRegistrationsPerWeekContent() {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());

        $perWeekRegistrations = $userDAO->getPerWeekRegistrations();
        $perWeekRegistrationsArr = array();
        $prevLastDay = null;
        $oneDayInterval = new \DateInterval('P1D');
        $sixDaysInterval = new \DateInterval('P6D');
        foreach ($perWeekRegistrations as $perWeekRegistration) {
            if ($prevLastDay !== null) {
                while (($firstDay = clone $prevLastDay->add($oneDayInterval)) != $perWeekRegistration['first_day']) {
                    $prevLastDay = $prevLastDay->add($sixDaysInterval);
                    $perWeekRegistrationsArr [] = array(
                        'first_week_day' => $firstDay,
                        'last_week_day' => clone $prevLastDay,
                        'registrations' => 0,
                    );
                }
            }
            $perWeekRegistrationsArr [] = array(
                'first_week_day' => clone $perWeekRegistration['first_day'],
                'last_week_day' => clone $perWeekRegistration['last_day'],
                'registrations' => $perWeekRegistration['number'],
            );
            $prevLastDay = clone $perWeekRegistration['last_day'];
        }

        if ($prevLastDay !== null) {
            $today = new \DateTime();
            $today->setTime(0, 0, 0);
            while ($prevLastDay < $today) {
                $firstDay = clone $prevLastDay->add($oneDayInterval);
                $prevLastDay = $prevLastDay->add($sixDaysInterval);
                $perWeekRegistrationsArr [] = array(
                    'first_week_day' => $firstDay,
                    'last_week_day' => clone $prevLastDay,
                    'registrations' => 0,
                );
            }
        }

        $perWeekRegistrationsArr = array_reverse($perWeekRegistrationsArr);

        $exportConfig = array(
            'first_week_day' => array('date' => 'j F Y'),
            'last_week_day' => array('date' => 'j F Y'),
            'registrations' => 'number',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($perWeekRegistrationsArr, $exportConfig);
    }

    /**
     * @return string
     */
    public function getActiveVsInactiveContent() {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());

        $data = array(
            array(
                'active' => $userDAO->getNumberOfUsersPredictedLastNDays(self::PREDICTED_LAST_DAYS),
                'inactive' => $userDAO->getNumberOfUsersNotPredictedLastNDays(self::PREDICTED_LAST_DAYS),
            ),
        );

        $exportConfig = array(
            'active' => 'number',
            'inactive' => 'number',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($data, $exportConfig);
    }

    /**
     * @return string
     */
    public function getIncompleteRegistrationsContent() {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());

        $data = array(
            array(
                'incomplete' => $userDAO->getIncompleteUsersNumber(),
            ),
        );

        $exportConfig = array(
            'incomplete' => 'number',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($data, $exportConfig);
    }

    /**
     * @return string
     */
    public function getUsersByRegionContent() {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());

        $data = $userDAO->getUsersByRegion();
        $sum = 0;
        foreach ($data as $row)
            $sum += $row['users'];
        $usersNumber = UserDAO::getInstance($this->getServiceLocator())->count();
        if ($usersNumber > $sum)
            $data [] = array(
                'region' => 'Other Regions',
                'users' => $usersNumber - $sum,
            );

        $exportConfig = array(
            'region' => 'string',
            'users' => 'number',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($data, $exportConfig);
    }

    /**
     * @return string
     */
    public function getPredictionsThisSeasonContent() {
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $data = array(
            array(
                'predictions' => $predictionDAO->getPredictionsCount($season->getId()),
            ),
        );

        $exportConfig = array(
            'predictions' => 'number',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($data, $exportConfig);
    }

    /**
     * @return string
     */
    public function getAvgPredictionsPerMatchThisSeasonContent() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $avgNumberOfPredictions = $predictionManager->getAvgNumberOfPredictions($season);

        $data = array(
            array(
                'avg_number_of_predictions' => $avgNumberOfPredictions,
            ),
        );

        $exportConfig = array(
            'avg_number_of_predictions' => 'number',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($data, $exportConfig);
    }

    /**
     * @return string
     */
    public function getHighestPredictedMatchContent() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $highestPredictedMatches = $predictionDAO->getHighestPredictedMatches($season->getId());

        $exportConfig = array(
            'predictions' => 'number',
            'start_time' => array('date' => 'j F Y'),
            'competition' => 'string',
            'home_team' => 'string',
            'away_team' => 'string',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($highestPredictedMatches, $exportConfig);
    }

    /**
     * @return string
     */
    public function getLowestPredictedMatchContent() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $lowestPredictedMatches = $predictionDAO->getLowestPredictedMatches($season->getId());

        $exportConfig = array(
            'predictions' => 'number',
            'start_time' => array('date' => 'j F Y'),
            'competition' => 'string',
            'home_team' => 'string',
            'away_team' => 'string',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($lowestPredictedMatches, $exportConfig);
    }

    /**
     * @return string
     */
    public function getMostPopularScorersThisSeasonContent() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $topScorersThisSeason = $predictionDAO->getTopScorersThisSeason($season->getId(), self::TOP_SCORERS_COUNT);

        $exportConfig = array(
            'player' => 'string',
            'team' => 'string',
            'predictions' => 'number',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($topScorersThisSeason, $exportConfig);
    }

    /**
     * @return string
     */
    public function getMostPopularScoresThisSeasonContent() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $topScoresThisSeason = $predictionDAO->getTopScoresThisSeason($season->getId(), self::TOP_SCORERS_COUNT);
        foreach ($topScoresThisSeason as &$topScoreThisSeason)
            $topScoreThisSeason['score'] = $topScoreThisSeason['home_team_score'] . '-' . $topScoreThisSeason['away_team_score'];

        $exportConfig = array(
            'score' => 'string',
            'predictions' => 'number',
        );

        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($topScoresThisSeason, $exportConfig);
    }

}