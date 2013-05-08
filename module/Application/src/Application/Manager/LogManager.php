<?php

namespace Application\Manager;

use \Zend\Log\Writer\Stream;
use \Zend\Log\Logger;
use \Application\Model\Helpers\MessagesConstants;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class LogManager extends BasicManager {

    private function __construct() {
        $this->initLogger();
    }

    /**
     * @var LogManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LogManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LogManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @var \Zend\Log\Logger
     */
    private $logger;

    private function initLogger() {
        $logDir = getcwd() . DIRECTORY_SEPARATOR . 'data'  . DIRECTORY_SEPARATOR . 'log'  . DIRECTORY_SEPARATOR;
        $errorLogPath = $logDir . 'error.log';
        $errorLogWriter = new Stream($errorLogPath);
        $this->logger = new Logger();
        $this->logger->addWriter($errorLogWriter, Logger::ERR);
    }

    public function logException(\Exception $e, $priority = Logger::ERR) {
        $this->logger->log($priority, $e->getMessage(), array($e->getTraceAsString()));
    }

}
