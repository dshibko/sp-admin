<?php

namespace Application\Manager;

use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\DAOs\LeagueUserDAO;
use \Application\Model\DAOs\SeasonDAO;
use \Application\Model\DAOs\LeagueDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class LeagueManager extends BasicManager {

    /**
     * @var LeagueManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LeagueManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LeagueManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function recalculateLeaguesTables() {
        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();

        if ($season != null) {

            $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
            $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
            foreach ($season->getLeagues() as $league) {
                $usersData = $leagueDAO->getLeagueUsersScores($league);
                usort($usersData, function($u2, $u1) {
                    $res = $u1['points'] != $u2['points'] ? $u1['points'] - $u2['points'] :
                        (floor(100 * $u1['accuracy']) != floor(100 * $u2['accuracy']) ? $u1['accuracy'] - $u2['accuracy'] :
                        ($u1['predictions_count'] != $u2['predictions_count'] ? $u1['predictions_count'] - $u2['predictions_count'] :
                        ($u1['correct_results'] != $u2['correct_results'] ? $u1['correct_results'] - $u2['correct_results'] :
                        ($u1['correct_scores'] != $u2['correct_scores'] ? $u1['correct_scores'] - $u2['correct_scores'] :
                        ($u1['correct_scorers'] != $u2['correct_scorers'] ? $u1['correct_scorers'] - $u2['correct_scorers'] :
                        ($u1[0]['user']['date']->getTimestamp() - $u2[0]['user']['date']->getTimestamp()))))));
                    return (int)($res/abs($res));
                });

                foreach ($usersData as $i => $userRow) {
                    $leagueUser = $leagueUserDAO->findOneById($userRow[0]['id']);
                    $leagueUser->setPlace($i + 1);
                    $leagueUser->setPoints($userRow['points']);
                    $leagueUser->setAccuracy(floor(100 * $userRow['accuracy']));
                    $leagueUserDAO->save($leagueUser, false, false);
                }
                $leagueUserDAO->flush();
                $leagueUserDAO->clearCache();

            }

        } else
            throw new \Exception(MessagesConstants::INFO_OUT_OF_SEASON);

    }

    public function getAllLeagues($hydrate = false, $skipCache = false) {
        return LeagueDAO::getInstance($this->getServiceLocator())->getAllLeagues($hydrate, $skipCache);
    }

}