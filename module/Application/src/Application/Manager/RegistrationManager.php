<?php
namespace Application\Manager;

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
    const DEFAULT_LANGUAGE_ID = 1;
    const DEFAULT_REGION_ID = 1;
    const ACTIVE_USER_STATUS = 1;
    const DEFAULT_AVATAR_ID = 1;
    /**
     * @var UserManager
     */
    private static $instance;
    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return UserManager
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
        $data['language'] = LanguageDAO::getInstance($this->getServiceLocator())->findOneById(self::DEFAULT_LANGUAGE_ID);
        $data['region'] = RegionDAO::getInstance($this->getServiceLocator())->findOneById(self::DEFAULT_REGION_ID);
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
     */
    public function setUp(array $data)
    {

        $country = CountryDAO::getInstance($this->getServiceLocator())->findOneById($data['region']);
        $region = $country->getRegion();
        $region_id = self::DEFAULT_REGION_ID;
        if (!empty($region) && $region instanceof Region){
            $region_id = $region->getId();
        }

        $data['region'] = RegionDAO::getInstance($this->getServiceLocator())->findOneById($region_id);
        $data['active'] = self::ACTIVE_USER_STATUS;
        $data['language'] = LanguageDAO::getInstance($this->getServiceLocator())->findOneById($data['language']);
        $data['country'] = $country;

        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();

        if (!$user) {
            throw new \Exception('Cannot get current user. Please Login');
        }
        $user->populate($data);
        UserDAO::getInstance($this->getServiceLocator())->save($user);
        return true;
    }
}