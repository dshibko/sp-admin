<?php

namespace Application\Manager;

use \Application\Model\DAOs\UserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class UserManager extends BasicManager {

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
            self::$instance = new UserManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function getRegisteredUsersNumber() {
        return UserDAO::getInstance($this->getServiceLocator())->count();
    }

    public function getUsersRegisteredInPastDays($days, $hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->getUsersRegisteredInPastDays($days, $hydrate, $skipCache);
    }

    public function getAllUsers($hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->getAllUsers($hydrate, $skipCache);
    }

    public function getUserByIdentity($identity, $hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->findOneByIdentity($identity, $hydrate, $skipCache);
    }

    public function getUserById($id, $hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    public function getUsersExportContent() {
        $users = UserDAO::getInstance($this->getServiceLocator())->getAllUsers(true, true);
        $exportConfig = array('id' => 'number',
            'displayName' => 'string',
            'email' => 'string',
            'birthday' => array('date' => 'j F Y'),
            'role' => array('array' => 'name'),
            'date' => array('date' => 'j F Y'));
        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($users, $exportConfig);
    }

}