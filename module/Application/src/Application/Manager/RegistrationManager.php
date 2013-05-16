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

class RegistrationManager extends BasicManager
{
    const MEMBER_ROLE_ID = 3;
    const DEFAULT_LANGUAGE_ID = 1;
    const DEFAULT_REGION_ID = 1;
    /**
     * @var UserManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return UserManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new RegistrationManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function getAllCountries(){
        return CountryDAO::getInstance($this->getServiceLocator())->getAllCountries();
    }

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
       $data['region']  = RegionDAO::getInstance($this->getServiceLocator())->findOneById(self::DEFAULT_REGION_ID);
       $data['country'] = CountryDAO::getInstance($this->getServiceLocator())->findOneById($data['country']);
       $data['date']  = new \DateTime();
       $data['date_of_birth'] = new \DateTime($data['date_of_birth']);

       $bcrypt = new Bcrypt();
       $data['password'] = $bcrypt->create($data['password']);

       $user = new User();
       $user->populate($data);
       UserDAO::getInstance($this->getServiceLocator())->save($user);

    }
}