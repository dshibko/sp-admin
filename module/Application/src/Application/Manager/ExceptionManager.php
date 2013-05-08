<?php

namespace Application\Manager;

use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class ExceptionManager extends BasicManager {

    /**
     * @var ExceptionManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ExceptionManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ExceptionManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param \Exception $e
     * @param \Zend\Mvc\Controller\AbstractActionController $controller
     * @param string $customMessage
     */
    public function handleControllerException(\Exception $e, \Zend\Mvc\Controller\AbstractActionController $controller, $customMessage = '') {
        $userMessage = !empty($customMessage) ? $customMessage : $e->getMessage();
        $controller->flashmessenger()->addErrorMessage($userMessage);
        LogManager::getInstance($this->getServiceLocator())->logException($e);
    }

}
