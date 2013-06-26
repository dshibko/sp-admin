<?php

namespace Opta\Controller;

use \Application\Manager\SeasonManager;
use \Application\Manager\ApplicationManager;
use \Zend\Log\Logger;
use \Application\Model\Entities\Feed;
use \Application\Model\Helpers\MessagesConstants;
use \Opta\Manager\OptaManager;
use \Application\Manager\ExceptionManager;
use \Zend\Console\Adapter\AdapterInterface as Console;
use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\Console\Exception\RuntimeException;

class DispatcherController extends AbstractActionController {

    public function dispatchAction() {

        error_reporting(E_ERROR | E_PARSE);

        $console = $this->getConsole();

        try {

            $type = $this->getRequest()->getParam('type', '');
            if (empty($type))
                throw new \Exception(MessagesConstants::ERROR_TYPE_NOT_SPECIFIED);

            if (!in_array($type, Feed::getAvailableTypes()))
                throw new \Exception(sprintf(MessagesConstants::ERROR_WRONG_TYPE_SPECIFIED, $type));

            $optaManager = OptaManager::getInstance($this->getServiceLocator());

            switch ($type) {

                // export TZ=UTC;
                case Feed::F1_TYPE: // 10 10 * * * cd <APP_ROOT>; php public/index.php opta F1
                    $feeds = $optaManager->getUploadedFeedsByType($type);
                    if (!empty($feeds)) {
                        $seasonManager = SeasonManager::getInstance($this->getServiceLocator());
                        $unfinishedSeasons = $seasonManager->getAllNotFinishedSeasons(true, true);
                        $processingStarted = false;
                        foreach ($unfinishedSeasons as $unfinishedSeason) {
                            $unfinishedSeasonOptaId = $unfinishedSeason['feederId'];
                            $seasonFeeds = $optaManager->filterFeedsByParameter($feeds, $type, 'season_id', $unfinishedSeasonOptaId);
                            foreach ($seasonFeeds as $seasonFeed)
                                if ($optaManager->hasToBeProcessed($seasonFeed)) {
                                    $processingStarted = true;
                                    $success = $optaManager->parseF1Feed($seasonFeed, $console);
                                    $optaManager->processingCompleted($seasonFeed, $type, $success);
                                }
                        }
                        if ($processingStarted) {
                            $optaManager->saveFeedsChanges();
                            $optaManager->clearAppCache($type, $console);
                        }
                    }
                    break;

                case Feed::F7_TYPE: // */5 * * * * cd <APP_ROOT>; php public/index.php opta F7

                    break;

                case Feed::F40_TYPE: // 0 0,12 * * * cd <APP_ROOT>; php public/index.php opta F40
                    $feeds = $optaManager->getUploadedFeedsByType($type);
                    if (!empty($feeds)) {
                        $seasonManager = SeasonManager::getInstance($this->getServiceLocator());
                        $unfinishedSeasons = $seasonManager->getAllNotFinishedSeasons(true, true);
                        $processingStarted = false;
                        foreach ($unfinishedSeasons as $unfinishedSeason) {
                            $unfinishedSeasonOptaId = $unfinishedSeason['feederId'];
                            $seasonFeeds = $optaManager->filterFeedsByParameter($feeds, $type, 'season_id', $unfinishedSeasonOptaId);
                            foreach ($seasonFeeds as $seasonFeed)
                                if ($optaManager->hasToBeProcessed($seasonFeed)) {
                                    $processingStarted = true;
                                    $success = $optaManager->parseF40Feed($seasonFeed, $console);
                                    $optaManager->processingCompleted($seasonFeed, $type, $success);
                                }
                        }
                        if ($processingStarted) {
                            $optaManager->saveFeedsChanges();
                            $optaManager->clearAppCache($type, $console);
                        }
                    }
                    break;

            }

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::ERR, $console);
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
