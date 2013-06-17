<?php
namespace Application\Manager;

use \Application\Model\Entities\LeagueUser;
use \Application\Model\DAOs\LeagueDAO;
use \Application\Model\DAOs\UserDAO;
use \Application\Model\DAOs\CountryDAO;
use \Application\Model\DAOs\RoleDAO;
use \Application\Model\DAOs\LanguageDAO;
use \Application\Model\DAOs\RegionDAO;
use Application\Model\DAOs\AvatarDAO;

use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use Application\Model\Entities\Avatar;
use Application\Model\Entities\User;
use Application\Model\Entities\Region;


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
     * @return Application\Model\Entities\User
     */

    public function register(array $data)
    {
        $data['role'] = RoleDAO::getInstance($this->getServiceLocator())->findOneById(self::MEMBER_ROLE_ID);
        $data['language'] = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
        $data['country'] = CountryDAO::getInstance($this->getServiceLocator())->findOneById($data['country']);
        $data['date'] = new \DateTime();
        $data['date_of_birth'] = new \DateTime($data['date_of_birth']);
        $data['password'] = md5($data['password']);

        $user = new User();

        $user->populate($data);
        UserDAO::getInstance($this->getServiceLocator())->save($user);
        return $user;

    }

    /**
     *   Set up region and language of user
     *
     * @param array $data
     * @return bool
     */
    public function setUp(array $data)
    {

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

        $this->registerUserInLeagues($user);

        return true;
    }

    /**
     * @param User $user
     */
    public function registerUserInLeagues($user) {
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $globalLeagues = $leagueDAO->getGlobalLeagues();
        foreach ($globalLeagues as $globalLeague) {
            if (!$leagueDAO->getIsUserInLeague($globalLeague, $user)) {
                $leagueUser = new LeagueUser();
                $leagueUser->setUser($user);
                $leagueUser->setJoinDate(new \DateTime());
                $leagueUser->setLeague($globalLeague);
                $globalLeague->addLeagueUser($leagueUser);
                $leagueDAO->save($globalLeague, false, false);
            }
        }
        $region = $user->getCountry()->getRegion();
        if ($region != null) {
            $regionalLeagues = $leagueDAO->getRegionalLeagues($region);
            foreach ($regionalLeagues as $regionalLeague)
                if (!$leagueDAO->getIsUserInLeague($regionalLeague, $user)) {
                    $leagueUser = new LeagueUser();
                    $leagueUser->setUser($user);
                    $leagueUser->setJoinDate(new \DateTime());
                    $leagueUser->setLeague($regionalLeague);
                    $regionalLeague->addLeagueUser($leagueUser);
                    $leagueDAO->save($regionalLeague, false, false);
                }
            $temporalLeagues = $leagueDAO->getTemporalLeagues($region);
            foreach ($temporalLeagues as $temporalLeague)
                if (!$leagueDAO->getIsUserInLeague($temporalLeague, $user)) {
                    $leagueUser = new LeagueUser();
                    $leagueUser->setUser($user);
                    $leagueUser->setJoinDate(new \DateTime());
                    $leagueUser->setLeague($temporalLeague);
                    $temporalLeague->addLeagueUser($leagueUser);
                    $leagueDAO->save($temporalLeague, false, false);
                }
        }
        $leagueDAO->flush();
        $leagueDAO->clearCache();
    }

}