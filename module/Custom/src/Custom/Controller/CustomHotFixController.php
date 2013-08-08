<?php

namespace Custom\Controller;

use Application\Manager\ExportManager;
use Application\Manager\FTPManager;
use Application\Manager\LogManager;
use Application\Manager\UserManager;
use Custom\Manager\CustomExportManager;
use Custom\Manager\CustomHotFixManager;
use Custom\Model\Helper\CustomMessagesConstants;
use \Zend\Log\Logger;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Zend\Console\Adapter\AdapterInterface as Console;
use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\Console\Exception\RuntimeException;

class CustomHotFixController extends AbstractActionController {

//    public function mergePredictionsAction() {
//
//        $console = $this->getConsole();
//
//        try {
//
//            $customHotFixManager = CustomHotFixManager::getInstance($this->getServiceLocator());
//
//            $console->writeLine("");
//            $console->writeLine("Hot fix started");
//
//            $customHotFixManager->mergePredictions();
//
//            $console->writeLine("Hot fix finished");
//
//        } catch(\Exception $e) {
//            ExceptionManager::getInstance($this->getServiceLocator())->handleCustomException($e, Logger::ERR, $console);
//        }
//
//    }

    /**
     * @return \Zend\Console\Adapter\AdapterInterface
     * @throws \Zend\Console\Exception\RuntimeException
     */
    private function getConsole() {
        $console = $this->getServiceLocator()->get('console');
        if (!$console instanceof Console)
            throw new RuntimeException(MessagesConstants::ERROR_RUN_OUT_OF_CONSOLE);
        return $console;
    }

}
