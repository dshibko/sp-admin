<?php

namespace Neoco\Manager;

use Zend\ServiceManager\ServiceLocatorInterface;

interface SingleManagerInterface {

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return SingleManagerInterface
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface);

}