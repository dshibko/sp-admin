<?php

namespace Application\Manager;

use \Application\Model\DAOs\AvatarDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class AvatarManager extends BasicManager {

    /**
     * @var AvatarManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return AvatarManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new AvatarManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function getDefaultAvatars($hydrate = false, $skipCache = false)
    {
        return AvatarDAO::getInstance($this->getServiceLocator())->getDefaultAvatars($hydrate, $skipCache);
    }



}