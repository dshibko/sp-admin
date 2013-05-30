<?php

namespace Application\Manager;

use \Application\Model\Entities\Match;
use \Application\Model\DAOs\MatchDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class MatchManager extends BasicManager {

    /**
     * @var MatchManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return MatchManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
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
    public function getNextMatch($hydrate = false, $skipCache = false) {
        return MatchDAO::getInstance($this->getServiceLocator())->getNextMatch($hydrate, $skipCache);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Match
     */
    public function getPrevMatch($hydrate = false, $skipCache = false) {
        return MatchDAO::getInstance($this->getServiceLocator())->getPrevMatch($hydrate, $skipCache);
    }

    public function getMatchesLeftInTheSeasonNumber($fromTime, $season, $skipCache = false) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getMatchesLeftInTheSeasonNumber($fromTime, $season, $skipCache);
    }

    public function getLiveMatchesNumber($fromTime, $season, $skipCache = false) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        return $matchDAO->getLiveMatchesNumber($fromTime, $season, $skipCache);
    }

    public function getMatchesLeftInTheSeason($fromTime, $season, $hydrate = false, $skipCache = false) {
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

}