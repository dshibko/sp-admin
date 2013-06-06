<?php

namespace Neoco\View\Helper;

use \Application\Manager\MatchManager;
use \Application\Manager\PredictionManager;
use \Application\Manager\SettingsManager;
use \Application\Manager\ApplicationManager;
use Zend\View\Helper\AbstractHelper;

/**
 *
 */
    class LeagueStats extends AbstractHelper
{

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    private $wasInitialized = false;
    private $overallLeagueUsers;
    private $temporalLeagueUsers;
    private $globalPoints;
    private $globalAccuracy;

    /**
     * @return \Neoco\View\Helper\UnreadItems
     */
    public function __invoke()
    {
        if (!$this->wasInitialized) {
            $leagueUserDAO = \Application\Model\DAOs\LeagueUserDAO::getInstance($this->serviceLocator);
            $user = ApplicationManager::getInstance($this->serviceLocator)->getCurrentUser();
            $season = ApplicationManager::getInstance($this->serviceLocator)->getCurrentSeason();
            $region = $user->getCountry()->getRegion();
            $leagueUsers = $leagueUserDAO->getUserLeagues($user, $season, $region, true);
            $this->overallLeagueUsers = $this->temporalLeagueUsers = array();
            foreach ($leagueUsers as $leagueUser) {
                if ($leagueUser['type'] == \Application\Model\Entities\League::MINI_TYPE)
                    $this->temporalLeagueUsers [] = $leagueUser;
                else if ($leagueUser['type'] == \Application\Model\Entities\League::GLOBAL_TYPE) {
                    array_unshift($this->overallLeagueUsers, $leagueUser);
                    $this->globalPoints = $leagueUser['points'];
                    $this->globalAccuracy = $leagueUser['accuracy'];
                } else if ($leagueUser['type'] == \Application\Model\Entities\League::REGIONAL_TYPE)
                    array_push($this->overallLeagueUsers, $leagueUser);
            }
            $this->wasInitialized = true;
        }
        return $this;
    }

    public function getGlobalPoints() {
        return $this->globalPoints;
    }

    public function getGlobalAccuracy() {
        return $this->globalAccuracy;
    }

    public function getOverallLeagueUsers()
    {
        return $this->overallLeagueUsers;
    }

    public function getTemporalLeagueUsers()
    {
        return $this->temporalLeagueUsers;
    }

}