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
            $leagueUserDAO = LeagueUserDAO::getInstance($this->serviceLocator);
            $this->currentSeason = ApplicationManager::getInstance($this->serviceLocator)->getCurrentSeason();
            if ($this->currentSeason === null) return $this;
            $user = ApplicationManager::getInstance($this->serviceLocator)->getCurrentUser();
            $language = $user->getLanguage();
            $defaultLanguage = LanguageManager::getInstance($this->serviceLocator)->getDefaultLanguage();
            $leagueUsers = $leagueUserDAO->getUserLeagues($user, $this->currentSeason, $language->getId(), $defaultLanguage->getId());
            $this->overallLeagueUsers = $this->temporalLeagueUsers = array();
            foreach ($leagueUsers as $leagueUser)
                if ($leagueUser['place'] != null) {
                    if (empty($leagueUser['displayName']) && !$language->getIsDefault())
                        $leagueUser['displayName'] = $leagueUser['defaultDisplayName'];
                    if ($leagueUser['type'] == League::MINI_TYPE)
                        $this->temporalLeagueUsers [] = $leagueUser;
                    else if ($leagueUser['type'] == League::GLOBAL_TYPE) {
                        array_unshift($this->overallLeagueUsers, $leagueUser);
                        $this->globalPoints = $leagueUser['points'];
                        $this->globalAccuracy = $leagueUser['accuracy'];
                    } else if ($leagueUser['type'] == League::REGIONAL_TYPE)
                        array_push($this->overallLeagueUsers, $leagueUser);
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