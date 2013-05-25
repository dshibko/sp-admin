<?php

namespace Application\Manager;

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
        if (self::$instance == null) {
            self::$instance = new ApplicationManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @var \Application\Model\Entities\User
     */
    protected $currentUser = -1;

    /**
     * @return \Application\Model\Entities\User
     */
    public function getCurrentUser()
    {
        if ($this->currentUser == -1) {
            $identity = $this->getServiceLocator()->get('AuthService')->getIdentity();
            if ($identity == null) $this->currentUser = null;
            else
                if ($identity instanceof User)
                    $this->currentUser = $identity;
                else if (is_string($identity))
                    $this->currentUser = UserDAO::getInstance($this->getServiceLocator())->findOneByIdentity($identity);
        }
        return $this->currentUser;
    }

    /**
     * @var \Application\Model\Entities\User
     */
    protected $currentSeason = -1;

    /**
     * @return \Application\Model\Entities\Season
     */
    public function getCurrentSeason()
    {
        if ($this->currentSeason == -1)
            $this->currentSeason = SeasonDAO::getInstance($this->getServiceLocator())->getCurrentSeason();
        return $this->currentSeason;
    }

    public function getAllRegions($hydrate = false)
    {
        return RegionDAO::getInstance($this->getServiceLocator())->getAllRegions($hydrate);
    }

    public function getAllLanguages($hydrate = false)
    {
        return LanguageDAO::getInstance($this->getServiceLocator())->getAllLanguages($hydrate);
    }
    public function getAllCountries($hydrate = false)
    {
        return CountryDAO::getInstance($this->getServiceLocator())->getAllCountries($hydrate);
    }

    public function getCountryByISOCode($isoCode, $hydrate = false)
    {
        return CountryDAO::getInstance($this->getServiceLocator())->getCountryByISOCode($isoCode, $hydrate);
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

    public function getAppEdition() {
        return array_shift($this->getAppConfig());
    }

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
        if ($this->appConfig == null) {
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

}
