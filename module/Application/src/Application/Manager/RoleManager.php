<?php

namespace Application\Manager;

use \Application\Model\DAOs\RoleDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class RoleManager extends BasicManager {

    /**
     * @var RoleManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return RoleManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new RoleManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function getRoleByName($name, $hydrate = false, $skipCache = false)
    {
        return RoleDAO::getInstance($this->getServiceLocator())->getRoleByName($name, $hydrate, $skipCache);
    }

}