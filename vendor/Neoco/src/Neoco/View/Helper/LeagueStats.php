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
    private $globalPoints = 0;
    private $globalAccuracy = 0;
    private $currentSeason;

    /**
     * @return \Neoco\View\Helper\LeagueStats
     */
    public function __invoke()
    {
        if (!$this->wasInitialized) {
            $this->wasInitialized = true;
            $leagueUserDAO = \Application\Model\DAOs\LeagueUserDAO::getInstance($this->serviceLocator);
            $this->currentSeason = ApplicationManager::getInstance($this->serviceLocator)->getCurrentSeason();
            if ($this->currentSeason === null) return $this;
            $user = ApplicationManager::getInstance($this->serviceLocator)->getCurrentUser();
            $region = $user->getCountry()->getRegion();
            $leagueUsers = $leagueUserDAO->getUserLeagues($user, $this->currentSeason, $region, true);
            $this->overallLeagueUsers = $this->temporalLeagueUsers = array();
            foreach ($leagueUsers as $leagueUser)
                if ($leagueUser['place'] != null) {
                    if ($leagueUser['type'] == \Application\Model\Entities\League::MINI_TYPE)
                        $this->temporalLeagueUsers [] = $leagueUser;
                    else if ($leagueUser['type'] == \Application\Model\Entities\League::GLOBAL_TYPE) {
                        array_unshift($this->overallLeagueUsers, $leagueUser);
                        $this->globalPoints = $leagueUser['points'];
                        $this->globalAccuracy = $leagueUser['accuracy'];
                    } /*else if ($leagueUser['type'] == \Application\Model\Entities\League::REGIONAL_TYPE)
                        array_push($this->overallLeagueUsers, $leagueUser);*/
                    // todo remove
                }
        }
        return $this;
    }

    public function getGlobalPoints() {
        return $this->globalPoints != null ? $this->globalPoints : 0;
    }

    public function getGlobalAccuracy() {
        return $this->globalAccuracy != null ? $this->globalAccuracy : 0;
    }

    public function getOverallLeagueUsers()
    {
        return $this->overallLeagueUsers;
    }

    public function getTemporalLeagueUsers()
    {
        return $this->temporalLeagueUsers;
    }

    public function getCurrentSeason()
    {
        return $this->currentSeason;
    }

}