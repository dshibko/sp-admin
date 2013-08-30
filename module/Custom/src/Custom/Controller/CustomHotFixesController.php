<?php

namespace Custom\Controller;

use Application\Controller\ClearAppCacheController;
use Application\Manager\ApplicationManager;
use Application\Manager\ExportManager;
use Application\Manager\FTPManager;
use Application\Manager\LogManager;
use Application\Manager\UserManager;
use Application\Model\DAOs\LeagueUserDAO;
use Application\Model\DAOs\LeagueUserPlaceDAO;
use Application\Model\DAOs\MatchGoalDAO;
use Application\Model\DAOs\MessageDAO;
use Application\Model\DAOs\PredictionDAO;
use Custom\Manager\CustomExportManager;
use Custom\Manager\CustomHotFixManager;
use Custom\Model\Helper\CustomMessagesConstants;
use \Zend\Log\Logger;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Zend\Console\Adapter\AdapterInterface as Console;
use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\Console\Exception\RuntimeException;

class CustomHotFixesController extends AbstractActionController {

    public function clearCacheAction() {
        $entitiesToBeCleared = array();
        $cacheClearArr[] = MatchGoalDAO::getInstance($this->getServiceLocator())->getRepositoryName();
        $cacheClearArr[] = PredictionDAO::getInstance($this->getServiceLocator())->getRepositoryName();
        $cacheClearArr[] = LeagueUserDAO::getInstance($this->getServiceLocator())->getRepositoryName();
        $cacheClearArr[] = LeagueUserPlaceDAO::getInstance($this->getServiceLocator())->getRepositoryName();
        $cacheClearArr[] = MessageDAO::getInstance($this->getServiceLocator())->getRepositoryName();

        $entitiesToBeClearedQueryString = implode(",", $entitiesToBeCleared);
        $clearAppCacheUrl = ApplicationManager::getInstance($this->getServiceLocator())->getClearAppCacheUrl();
        $clearAppCacheUrl = $clearAppCacheUrl . urlencode($entitiesToBeClearedQueryString);
        $clearAppCacheResult = file_get_contents($clearAppCacheUrl);
        if ($clearAppCacheResult == ClearAppCacheController::OK_MESSAGE)
            var_dump(MessagesConstants::APP_CACHE_CLEARED);
        else
            var_dump(MessagesConstants::APP_CACHE_NOT_CLEARED);
    }

    public function privateLeagueAction() {

        $console = $this->getConsole();

        try {

            $customHotFixManager = CustomHotFixManager::getInstance($this->getServiceLocator());

            $console->writeLine("");
            $console->writeLine("Hot fix started");

            $customHotFixManager->fixPrivateLeaguesPredictions(array(62, 75, 124, 167, 258, 308));

            $console->writeLine("Hot fix finished");

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleCustomException($e, Logger::ERR, $console);
        }

    }

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
