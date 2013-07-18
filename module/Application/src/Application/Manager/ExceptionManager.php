<?php

namespace Application\Manager;

use \Zend\Log\Logger;
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
        LogManager::getInstance($this->getServiceLocator())->logAppException($e);
    }

    /**
     * @param \Exception $e
     * @param int $priority
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    public function handleOptaException(\Exception $e, $priority = Logger::ERR, $console = null) {
        LogManager::getInstance($this->getServiceLocator())->logOptaException($e, $priority);
        MailManager::getInstance($this->getServiceLocator())->sendExceptionEmail($e, $priority);
        if ($console != null) {
            $console->writeLine("");
            $console->writeLine($e->getMessage());
        } else {
            $flashMessenger = $this->getServiceLocator()->get('ControllerPluginManager')->get('FlashMessenger');
            $flashMessenger->addErrorMessage($e->getMessage());
            $flashMessenger->addErrorMessage($e->getTraceAsString());
        }
    }

    /**
     * @param \Exception $e
     * @param int $priority
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    public function handleCustomException(\Exception $e, $priority = Logger::ERR, $console = null) {
        LogManager::getInstance($this->getServiceLocator())->logCustomException($e, $priority);
        if ($console != null) {
            $console->writeLine("");
            $console->writeLine($e->getMessage());
        } else {
            $flashMessenger = $this->getServiceLocator()->get('ControllerPluginManager')->get('FlashMessenger');
            $flashMessenger->addErrorMessage($e->getMessage());
            $flashMessenger->addErrorMessage($e->getTraceAsString());
        }
    }

}
