<?php
namespace Application\Manager;

use Application\Model\DAOs\LeagueUserDAO;
use \Application\Model\Entities\LeagueUser;
use \Application\Model\DAOs\LeagueDAO;
use \Application\Model\DAOs\UserDAO;
use \Application\Model\DAOs\CountryDAO;
use \Application\Model\DAOs\RoleDAO;
use \Application\Model\DAOs\LanguageDAO;
use \Application\Model\DAOs\RegionDAO;
use Application\Model\DAOs\AvatarDAO;

use Application\Model\Entities\Season;
use Neoco\Exception\OutOfSeasonException;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use Application\Model\Entities\User;


class RegistrationManager extends BasicManager
{

    const MEMBER_ROLE_ID = 3;
    const ACTIVE_USER_STATUS = 1;
    const DEFAULT_AVATAR_ID = 1;

    /**
     * @var RegistrationManager
     */
    private static $instance;
    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return RegistrationManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new RegistrationManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     *  Register new user
     *
     * @param array $data
     * @return \Application\Model\Entities\User
     */

    public function register(array $data)
    {
        $data['role'] = RoleDAO::getInstance($this->getServiceLocator())->findOneById(self::MEMBER_ROLE_ID);
        $data['language'] = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
        $data['country'] = CountryDAO::getInstance($this->getServiceLocator())->findOneById($data['country']);
        $data['date'] = new \DateTime();
        $data['date_of_birth'] = new \DateTime($data['date_of_birth']);
        $data['password'] = ApplicationManager::getInstance($this->getServiceLocator())->encryptPassword($data['password']);

        $user = new User();

        $user->populate($data);
        UserDAO::getInstance($this->getServiceLocator())->save($user);
        $this->sendWelcomeEmail($user->getEmail(), $user->getDisplayName());
        return $user;

    }

    public function sendWelcomeEmail($email, $name)
    {
        $sendWelcomeEmail = SettingsManager::getInstance($this->getServiceLocator())->getSetting(SettingsManager::SEND_WELCOME_EMAIL);
        if ($sendWelcomeEmail){
            MailManager::getInstance($this->getServiceLocator())->sendWelcomeEmail($email, $name);
        }
        return true;
    }

    /**
     *   Set up region and language of user
     *
     * @param array $data
     * @throws \Neoco\Exception\OutOfSeasonException
     * @throws \Exception
     * @return bool
     */
    public function setUp(array $data)
    {
        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
        if ($season === null)
            throw new OutOfSeasonException();

        if (!empty($data['terms'])){
            $data = array_merge($data, $data['terms']);
            unset($data['terms']);
        }
        $country = CountryDAO::getInstance($this->getServiceLocator())->findOneById($data['region']);
        $data['active'] = self::ACTIVE_USER_STATUS;
        $data['language'] = LanguageDAO::getInstance($this->getServiceLocator())->findOneById($data['language']);
        $data['country'] = $country;
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        if (!$user) {
            throw new \Exception('Cannot get current user. Please Login');
        }
        $user->populate($data);
        UserDAO::getInstance($this->getServiceLocator())->save($user);

        $this->registerUserInLeagues($user, $season);

        return true;
    }

    /**
     * @param User $user
     * @param Season $season
     */
    public function registerUserInLeagues($user, $season) {
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        $globalLeague = $season->getGlobalLeague();
        if (!$leagueDAO->getIsUserInLeague($globalLeague, $user)) {
            $leagueUser = new LeagueUser();
            $leagueUser->setUser($user);
            $leagueUser->setJoinDate(new \DateTime());
            $leagueUser->setRegistrationDate($user->getDate());
            $leagueUser->setLeague($globalLeague);
            $globalLeague->addLeagueUser($leagueUser);
            $leagueDAO->save($globalLeague, false, false);
        }
        $region = $user->getCountry()->getRegion();
        if ($region != null) {
            $regionalLeague = $season->getRegionalLeagueByRegionId($region->getId());
            if ($regionalLeague != null && !$leagueUserDAO->getHasUserRegionalLeague($user->getId(), $season->getId())) {
                $leagueUser = new LeagueUser();
                $leagueUser->setUser($user);
                $leagueUser->setJoinDate(new \DateTime());
                $leagueUser->setRegistrationDate($user->getDate());
                $leagueUser->setLeague($regionalLeague);
                $regionalLeague->addLeagueUser($leagueUser);
                $leagueDAO->save($regionalLeague, false, false);
            }
            $temporalLeagues = $leagueDAO->getTemporalLeagues($region, $season);
            foreach ($temporalLeagues as $temporalLeague)
                if (!$leagueDAO->getIsUserInLeague($temporalLeague, $user)) {
                    $leagueUser = new LeagueUser();
                    $leagueUser->setUser($user);
                    $leagueUser->setJoinDate(new \DateTime());
                    $leagueUser->setRegistrationDate($user->getDate());
                    $leagueUser->setLeague($temporalLeague);
                    $temporalLeague->addLeagueUser($leagueUser);
                    $leagueDAO->save($temporalLeague, false, false);
                }
        }
        $leagueDAO->flush();
        $leagueDAO->clearCache();
        LeagueUserDAO::getInstance($this->getServiceLocator())->clearCache();
    }

}