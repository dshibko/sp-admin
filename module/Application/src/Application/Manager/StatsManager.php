<?php

namespace Application\Manager;

use Application\Model\DAOs\AccountRemovalDAO;
use Application\Model\Entities\AccountRemoval;
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
     * @return array
     */
    public function getFacebookVsDirectData() {
        $userManager = UserManager::getInstance($this->getServiceLocator());

        $data = array(
                     'facebook' => $userManager->getFacebookUsersNumber(),
                     'direct' => $userManager->getDirectUsersNumber(),
                );

        return $data;
    }

    /**
     * @return array
     */
    public function getRegistrationsPerWeekData() {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());

        $perWeekRegistrations = $userDAO->getPerWeekRegistrations();
        $perWeekRegistrationsArr = array();
        $prevLastDay = null;
        $oneDayInterval = new \DateInterval('P1D');
        $sixDaysInterval = new \DateInterval('P6D');
        foreach ($perWeekRegistrations as $perWeekRegistration) {
            if ($prevLastDay !== null) {
                if ($prevLastDay == $perWeekRegistration['last_day']) {
                    $perWeekRegistrationsArr[count($perWeekRegistrationsArr) - 1]['registrations'] += $perWeekRegistration['number'];
                    continue;
                }
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

        return $perWeekRegistrationsArr;
    }

    /**
     * @return array
     */
    public function getActiveVsInactiveData() {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());

        $data = array(
                    'active' => $userDAO->getNumberOfUsersPredictedLastNDays(self::PREDICTED_LAST_DAYS),
                    'inactive' => $userDAO->getNumberOfUsersNotPredictedLastNDays(self::PREDICTED_LAST_DAYS),
                );

        return $data;
    }

    /**
     * @throws \Neoco\Exception\InfoException
     * @return array
     */
    public function getIncompleteRegistrationsData() {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null || $season->getGlobalLeague() == null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $data = array(
            'incomplete' => $userDAO->getIncompleteUsersNumber($season->getGlobalLeague()->getId()),
        );

        return $data;
    }

    /**
     * @return array
     */
    public function getUsersByRegionData() {
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

        return $data;
    }

    /**
     * @throws \Neoco\Exception\InfoException
     * @return array
     */
    public function getPredictionsThisSeasonData() {
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $data = array(
            'predictions' => $predictionDAO->getPredictionsCount($season->getId()),
        );

        return $data;
    }

    /**
     * @throws \Neoco\Exception\InfoException
     * @return array
     */
    public function getAvgPredictionsPerMatchThisSeasonData() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $avgNumberOfPredictions = $predictionManager->getAvgNumberOfPredictions($season);

        $data = array(
            'avg_number_of_predictions' => $avgNumberOfPredictions,
        );

        return $data;
    }

    /**
     * @throws \Neoco\Exception\InfoException
     * @return array
     */
    public function getHighestPredictedMatchData() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $highestPredictedMatches = $predictionDAO->getHighestPredictedMatches($season->getId());

        return $highestPredictedMatches;
    }

    /**
     * @throws \Neoco\Exception\InfoException
     * @return array
     */
    public function getLowestPredictedMatchData() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $lowestPredictedMatches = $predictionDAO->getLowestPredictedMatches($season->getId());

        return $lowestPredictedMatches;
    }

    /**
     * @throws \Neoco\Exception\InfoException
     * @return array
     */
    public function getMostPopularScorersThisSeasonData() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $topScorersThisSeason = $predictionDAO->getTopScorersThisSeason($season->getId(), self::TOP_SCORERS_COUNT);

        return $topScorersThisSeason;
    }

    /**
     * @return string
     * @throws \Neoco\Exception\InfoException
     */
    public function getPredictionsPerDayThisSeasonData()
    {
        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $predictionsPerDayWhileSeason = $predictionDAO->getPredictionsPerDayThisSeason($season->getId());

        return $predictionsPerDayWhileSeason;
    }

    /**
     * @throws \Neoco\Exception\InfoException
     * @return array
     */
    public function getMostPopularScoresThisSeasonData() {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new InfoException(MessagesConstants::INFO_ADMIN_OUT_OF_SEASON);

        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $topScoresThisSeason = $predictionDAO->getTopScoresThisSeason($season->getId(), self::TOP_SCORERS_COUNT);
        foreach ($topScoresThisSeason as &$topScoreThisSeason)
            $topScoreThisSeason['score'] = $topScoreThisSeason['home_team_score'] . '-' . $topScoreThisSeason['away_team_score'];

        return $topScoresThisSeason;
    }

    /**
     * @return array
     */
    public function getAccountDeletionsData()
    {
        $accountRemovalDAO = AccountRemovalDAO::getInstance($this->getServiceLocator());

        $data = array(
            'facebook' => $accountRemovalDAO->getDeletionsCountByType(AccountRemoval::FACEBOOK_ACCOUNT),
            'direct' => $accountRemovalDAO->getDeletionsCountByType(AccountRemoval::DIRECT_ACCOUNT),
        );

        return $data;
    }

}