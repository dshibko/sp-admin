<?php

namespace Application\Manager;

use Neoco\Manager\BasicManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container as SessionContainer;

class SessionManager extends BasicManager {

    const ADMIN_STORAGE = 'admin';
    const USER_STORAGE = 'user';

    const IS_JUST_LOGGED_IN = 'isJustLoggedIn';
    const FIRST_ROUTE = 'firstRoute';
    const IS_FIRST_TIME_LOGGED_IN = 'isFirstTimeLoggedIn';

    private static $instance;

    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new SessionManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function setParameter($key, $value, $storage) {
        $this->getStorage($storage)->{$key} = $value;
    }

    public function getParameter($key, $storage) {
        return $this->getStorage($storage)->{$key};
    }

    public function getStorage($storage) {
        return new SessionContainer($storage);
    }
}