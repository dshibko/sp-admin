<?php

namespace Application\Manager;

use \Application\Model\DAOs\LeagueDAO;
use \Application\Model\DAOs\SeasonDAO;
use \Application\Model\Entities\Season;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\Entities\User;
use \Application\Model\DAOs\UserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use \Application\Model\DAOs\LanguageDAO;
use \Application\Model\DAOs\RegionDAO;
use \Application\Model\DAOs\CountryDAO;


class ApplicationManager extends BasicManager {

    const DEFAULT_COUNTRY_ID = 95;
    const DEFAULT_COUNTRY_ISO_CODE = 'GB';
    const CLUB_EDITION = 'club';
    const COMPETITION_EDITION = 'competition';

    /**
     * @var ApplicationManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ApplicationManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance === null) {
            self::$instance = new ApplicationManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @var \Application\Model\Entities\User
     */
    protected $currentUser;

    /**
     * @return \Application\Model\Entities\User
     */
    public function getCurrentUser()
    {
        if (is_null($this->currentUser)) {
            $identity = $this->getServiceLocator()->get('AuthService')->getIdentity();
            if ($identity === null) $this->currentUser = null;
            else
                if ($identity instanceof User)
                    $this->currentUser = $identity;
                else if (is_string($identity))
                    $this->currentUser = UserDAO::getInstance($this->getServiceLocator())->findOneByIdentity($identity, false, true);
        }
        return $this->currentUser;
    }

    /**
     * @var \Application\Model\Entities\User
     */
    protected $currentSeason;

    /**
     * @return \Application\Model\Entities\Season
     */
    public function getCurrentSeason()
    {
        if ($this->currentSeason === null)
            $this->currentSeason = SeasonDAO::getInstance($this->getServiceLocator())->getCurrentSeason();
        return $this->currentSeason;
    }

    /**
     * @param bool $hydrate
     * @return array
     */
    public function getAllRegions($hydrate = false)
    {
        return RegionDAO::getInstance($this->getServiceLocator())->getAllRegions($hydrate);
    }

    /**
     * @param bool $hydrate
     * @return array
     */
    public function getAllLanguages($hydrate = false)
    {
        return LanguageDAO::getInstance($this->getServiceLocator())->getAllLanguages($hydrate);
    }

    /**
     * @param bool $hydrate
     * @return array
     */
    public function getAllCountries($hydrate = false)
    {
        return CountryDAO::getInstance($this->getServiceLocator())->getAllCountries($hydrate);
    }

    /**
     * @param $isoCode
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Country
     */
    public function getCountryByISOCode($isoCode, $hydrate = false, $skipCache = false)
    {
        return CountryDAO::getInstance($this->getServiceLocator())->getCountryByISOCode($isoCode, $hydrate, $skipCache);
    }

    //Get countries for select options
    /**
     * @return array
     */
    public function getCountriesSelectOptions()
    {
        $countries = array();
        $data = $this->getAllCountries(true);
        if (!empty($data) && is_array($data)){
            foreach($data as $country){
                $countries[$country['id']] = $country['name'];
            }
        }
        return $countries;
    }

    /**
     * @return mixed
     */
    public function getDefaultCountry()
    {
        return CountryDAO::getInstance($this->getServiceLocator())->findOneById(self::DEFAULT_COUNTRY_ID);
    }

    /**
     * @return mixed
     */
    public function getAppEdition() {
        return array_shift($this->getAppConfig());
    }

    /**
     * @return mixed
     */
    public function getAppOptaId() {
        return array_pop($this->getAppConfig());
    }

    /**
     * @var array
     */
    private $appConfig;

    /**
     * @return array
     * @throws \Exception
     */
    private function getAppConfig() {
        if ($this->appConfig === null) {
            $config = $this->getServiceLocator()->get('config');
            if (!array_key_exists('app', $config))
                throw new \Exception(MessagesConstants::ERROR_APP_CONFIG_NOT_FOUND);
            $appConfig = $config['app'];
            if (!array_key_exists('edition', $appConfig))
                throw new \Exception(MessagesConstants::ERROR_APP_EDITION_CONFIG_NOT_FOUND);
            if (!array_key_exists('opta_id', $appConfig))
                throw new \Exception(MessagesConstants::ERROR_APP_OPTA_CONFIG_NOT_FOUND);
            $edition = $appConfig['edition'];
            if ($edition != self::CLUB_EDITION && $edition != self::COMPETITION_EDITION)
                throw new \Exception(MessagesConstants::ERROR_APP_UNKNOWN_EDITION);
            $optaId = $appConfig['opta_id'];
            if (!preg_match('/[0-9]+/', $optaId))
                throw new \Exception(MessagesConstants::ERROR_APP_WRONG_OPTA_CONFIG);
            $this->appConfig = array($edition, $optaId);
        }
        return $this->appConfig;
    }

    private $globalLeagues = array();

    /**
     * @param Season|null $season
     * @return \Application\Model\Entities\League
     */
    public function getGlobalLeague($season = null)
    {
        if ($season === null)
            $season = $this->getCurrentSeason();
        if (!array_key_exists($season->getId(), $this->globalLeagues))
            $this->globalLeagues[$season->getId()] = LeagueDAO::getInstance($this->getServiceLocator())->getGlobalLeague($season);
        return $this->globalLeagues[$season->getId()];
    }

    private $regionalLeagues = array();

    /**
     * @param \Application\Model\Entities\Region $region
     * @param Season|null $season
     * @return \Application\Model\Entities\League
     */
    public function getRegionalLeague($region, $season = null)
    {
        if ($season === null)
            $season = $this->getCurrentSeason();
        if (!array_key_exists($season->getId(), $this->regionalLeagues))
            $this->regionalLeagues[$season->getId()] = array();
        if (!array_key_exists($region->getId(), $this->regionalLeagues[$season->getId()]))
            $this->regionalLeagues[$season->getId()][$region->getId()] = LeagueDAO::getInstance($this->getServiceLocator())->getRegionalLeague($this->getCurrentSeason(), $region);
        return $this->regionalLeagues[$season->getId()][$region->getId()];
    }

    /**
     * @param \DateTime $dateTime
     * @param string|null $timezone
     * @return \DateTime
     */
    public function getLocalTime($dateTime, $timezone = null) {
        if ($timezone === null)
            $timezone = 'UTC';
        $localTime = new \DateTime();
        $localTime->setTimestamp($dateTime->getTimestamp());
        $localTime->setTimezone(new \DateTimeZone(timezone_name_from_abbr($timezone)));
        return $localTime;
    }

    /**
     * @param \Application\Model\Entities\User $user
     * @return \Application\Model\Entities\Region
     */
    public function getUserRegion(\Application\Model\Entities\User $user)
    {
        $region = $user->getCountry()->getRegion();
        if (is_null($region)){
            $region = RegionManager::getInstance($this->getServiceLocator())->getDefaultRegion();
        }
        return $region;
    }

}