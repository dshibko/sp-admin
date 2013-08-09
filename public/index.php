<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
try {
    $application = Zend\Mvc\Application::init(require 'config/application.config.php');
    $application->run();
} catch (\Exception $e) {
    if (isset($application))
        \Application\Manager\ExceptionManager::getInstance($application->getServiceManager())->handleFatalException($e);
    else {
        $logManager = new \Application\Manager\LogManager();
        $logManager->logAppException($e, \Zend\Log\Logger::CRIT);
    }
    header('HTTP/1.1 500 Internal Server Error');
    require 'module/Application/view/error/500.php';
}