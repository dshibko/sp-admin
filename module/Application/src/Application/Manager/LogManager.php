<?php

namespace Application\Manager;

use \Zend\Log\Writer\Stream;
use \Zend\Log\Logger;
use \Application\Model\Helpers\MessagesConstants;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class LogManager extends BasicManager {

    const APP_LOGGER_NAME = 'app';
    const OPTA_LOGGER_NAME = 'opta';
    const CUSTOM_LOGGER_NAME = 'custom';

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

    private $loggers = array();

    private static function getLogDir() {
        return getcwd() . DIRECTORY_SEPARATOR . 'data'  . DIRECTORY_SEPARATOR . 'log'  . DIRECTORY_SEPARATOR;
    }

    /**
     * @param $name
     * @return Logger
     * @throws \Exception
     */
    private function getLogger($name) {

        $logDir = self::getLogDir();

        if (!in_array($name, array(self::APP_LOGGER_NAME, self::OPTA_LOGGER_NAME, self::CUSTOM_LOGGER_NAME)))
            throw new \Exception('Unknown logger name');

        if (!array_key_exists($name, $this->loggers)) {
            $logPath = $logDir . $name . DIRECTORY_SEPARATOR . $name . '.log';
            $logWriter = new Stream($logPath);
            $logger = new Logger();
            $logger->addWriter($logWriter);
            $this->loggers[$name] = $logger;
        }

        return $this->loggers[$name];

    }

    public function logAppException(\Exception $e, $priority = Logger::ERR) {
        $this->getLogger(self::APP_LOGGER_NAME)->log($priority, $e->getMessage(), array($e->getTraceAsString()));
    }

    public function logOptaException(\Exception $e, $priority = Logger::ERR) {
        $this->getLogger(self::OPTA_LOGGER_NAME)->log($priority, $e->getMessage(), array($e->getTraceAsString()));
    }

    public function logCustomException(\Exception $e, $priority = Logger::ERR) {
        $this->getLogger(self::CUSTOM_LOGGER_NAME)->log($priority, $e->getMessage(), array($e->getTraceAsString()));
    }

    public function logOptaMessage($message, $priority = Logger::INFO) {
        $this->getLogger(self::OPTA_LOGGER_NAME)->log($priority, $message);
    }

    public function logOptaInfo($info) {
        $this->getLogger(self::OPTA_LOGGER_NAME)->log(Logger::INFO, $info);
    }

    public function logCustomMessage($message, $priority = Logger::INFO) {
        $this->getLogger(self::CUSTOM_LOGGER_NAME)->log($priority, $message);
    }

}
