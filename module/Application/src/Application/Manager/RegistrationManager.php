<?php
namespace Application\Manager;

use \Application\Model\DAOs\UserDAO;
use \Application\Model\DAOs\CountryDAO;
use \Application\Model\DAOs\RoleDAO;
use \Application\Model\DAOs\LanguageDAO;
use \Application\Model\DAOs\RegionDAO;

use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use Application\Model\Entities\Avatar;
use Application\Model\Entities\User;
use Zend\Crypt\Password\Bcrypt;
use \Application\Manager\AuthenticationManager;
use \Zend\Authentication\Result;
use \Application\Model\Helpers\MessagesConstants;

class RegistrationManager extends BasicManager
{
    const MEMBER_ROLE_ID = 3;
    const DEFAULT_LANGUAGE_ID = 1;
    const DEFAULT_REGION_ID = 1;
    const ACTIVE_USER_STATUS = 1;
    /**
     * @var UserManager
     */
    private static $instance;

    private function loginUser($email, $password)
    {
        //Login registered user
        $result = AuthenticationManager::getInstance($this->getServiceLocator())->authenticate($email, $password);
        if (!$result->isValid()) {
            throw new \Exception('Cannot login user: ' . MessagesConstants::ERROR_WRONG_EMAIL_OR_PASSWORD);
        }
    }

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
     * @return bool
     */


    public function register(array $data)
    {
        $avatarData = array(
            'original_image_path' => $data['avatar'],
            'big_image_path' => $data['avatar'],
            'medium_image_path' => $data['avatar'],
            'small_image_path' => $data['avatar'],
            'tiny_image_path' => $data['avatar']
        );

        $avatar = new Avatar();
        $avatar->populate($avatarData);

        $data['avatar'] = $avatar;
        $data['role'] = RoleDAO::getInstance($this->getServiceLocator())->findOneById(self::MEMBER_ROLE_ID);
        $data['language'] = LanguageDAO::getInstance($this->getServiceLocator())->findOneById(self::DEFAULT_LANGUAGE_ID);
        $data['region'] = RegionDAO::getInstance($this->getServiceLocator())->findOneById(self::DEFAULT_REGION_ID);
        $data['country'] = CountryDAO::getInstance($this->getServiceLocator())->findOneById($data['country']);
        $data['date'] = new \DateTime();
        $data['date_of_birth'] = new \DateTime($data['date_of_birth']);

        $password = $data['password'];
        $data['password'] = md5($data['password']);

        $user = new User();
        $user->populate($data);
        UserDAO::getInstance($this->getServiceLocator())->save($user);
        $this->loginUser($user->getEmail(), $password);
        return true;

    }

    /**
     *   Set up region and language of user
     *
     * @param array $data
     */
    public function setUp(array $data)
    {
        $data['language'] = LanguageDAO::getInstance($this->getServiceLocator())->findOneById($data['language']);
        $region = CountryDAO::getInstance($this->getServiceLocator())->getCountryRegion($data['region'], true);

        $region_id = self::DEFAULT_REGION_ID;
        if (!empty($region[0]['region']['id'])) {
            $region_id = $region[0]['region']['id'];
        }

        $data['region'] = RegionDAO::getInstance($this->getServiceLocator())->findOneById($region_id);
        $data['active'] = self::ACTIVE_USER_STATUS;

        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();

        if (!$user) {
            throw new \Exception('Cannot get current user. Please Login');
        }

        $user->populate($data);
        UserDAO::getInstance($this->getServiceLocator())->save($user);
    }
}