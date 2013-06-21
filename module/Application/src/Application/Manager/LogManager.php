<?php

namespace Application\Manager;

use \Zend\Log\Writer\Stream;
use \Zend\Log\Logger;
use \Application\Model\Helpers\MessagesConstants;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class LogManager extends BasicManager {

    private function __construct() {
        $this->initLoggers();
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
    private $appLogger;

    /**
     * @var \Zend\Log\Logger
     */
    private $optaLogger;

    private function initLoggers() {

        $logDir = getcwd() . DIRECTORY_SEPARATOR . 'data'  . DIRECTORY_SEPARATOR . 'log'  . DIRECTORY_SEPARATOR;

        $appErrorLogPath = $logDir . 'app' . DIRECTORY_SEPARATOR . 'error.log';
        $appErrorLogWriter = new Stream($appErrorLogPath);
        $this->appLogger = new Logger();
        $this->appLogger->addWriter($appErrorLogWriter);

        $optaErrorLogPath = $logDir . 'opta' . DIRECTORY_SEPARATOR . 'opta.log';
        $optaErrorLogWriter = new Stream($optaErrorLogPath);
        $this->optaLogger = new Logger();
        $this->optaLogger->addWriter($optaErrorLogWriter);

    }

    public function getAppLogger()
    {
        return $this->appLogger;
    }

    public function logAppException(\Exception $e, $priority = Logger::ERR) {
        $this->getAppLogger()->log($priority, $e->getMessage(), array($e->getTraceAsString()));
    }

    public function logOptaException(\Exception $e, $priority = Logger::ERR) {
        $this->optaLogger->log($priority, $e->getMessage(), array($e->getTraceAsString()));
    }

    /**
     * @param $message
     * @param int $priority
     */
    public function logOptaMessage($message, $priority = Logger::INFO) {
        $this->optaLogger->log($priority, $message);
    }

    public function logOptaInfo($info) {
        $this->optaLogger->log(Logger::INFO, $info);
    }

}
