<?php

namespace Neoco\View\Helper;

use Application\Manager\LanguageManager;
use \Application\Manager\MatchManager;
use \Application\Manager\PredictionManager;
use Application\Manager\RegionManager;
use \Application\Manager\SettingsManager;
use \Application\Manager\ApplicationManager;
use Application\Model\DAOs\LeagueUserDAO;
use Application\Model\Entities\League;
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
    private $isActive = false;
    private $overallLeagueUsers;
    private $temporalLeagueUsers;
    private $privateLeagueUsers;
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
            $leagueUserDAO = LeagueUserDAO::getInstance($this->serviceLocator);
            $this->currentSeason = ApplicationManager::getInstance($this->serviceLocator)->getCurrentSeason();
            if ($this->currentSeason === null) return $this;
            $user = ApplicationManager::getInstance($this->serviceLocator)->getCurrentUser();
            $language = $user->getLanguage();
            $defaultLanguage = LanguageManager::getInstance($this->serviceLocator)->getDefaultLanguage();
            $leagueUsers = $leagueUserDAO->getUserLeagues($user, $this->currentSeason, $language->getId(), $defaultLanguage->getId());
            $this->isActive = !empty($leagueUsers);
            $this->overallLeagueUsers = $this->temporalLeagueUsers = $this->privateLeagueUsers = array();
            foreach ($leagueUsers as $leagueUser)
                if ($leagueUser['place'] != null) {
                    if (empty($leagueUser['displayName']) && !$language->getIsDefault())
                        $leagueUser['displayName'] = $leagueUser['defaultDisplayName'];
                    switch($leagueUser['type']) {
                        case League::MINI_TYPE:
                            $this->temporalLeagueUsers [] = $leagueUser;
                            break;
                        case League::PRIVATE_TYPE:
                            $leagueUser['displayName'] = $leagueUser['internalName'];
                            $this->privateLeagueUsers [] = $leagueUser;
                            break;
                        case League::GLOBAL_TYPE:
                            array_unshift($this->overallLeagueUsers, $leagueUser);
                            $this->globalPoints = $leagueUser['points'];
                            $this->globalAccuracy = $leagueUser['accuracy'];
                            break;
                        case League::REGIONAL_TYPE:
                            array_push($this->overallLeagueUsers, $leagueUser);
                            break;
                    }
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

    public function getPrivateLeagueUsers()
    {
        return $this->privateLeagueUsers;
    }

    public function getCurrentSeason()
    {
        return $this->currentSeason;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

}