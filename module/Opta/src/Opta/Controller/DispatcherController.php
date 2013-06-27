<?php

namespace Opta\Controller;

use \Application\Manager\MatchManager;
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
            $optaManager->dispatchFeedsByType($type, false, $console);

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
