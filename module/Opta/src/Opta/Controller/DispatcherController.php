<?php

namespace Opta\Controller;

use \Opta\Manager\OptaManager;
use \Application\Manager\ExceptionManager;
use \Zend\Console\Adapter\AdapterInterface as Console;
use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\Console\Exception\RuntimeException;

class DispatcherController extends AbstractActionController {

    public function dispatchAction() {

        error_reporting(E_ERROR | E_PARSE);

        try {
//            \Application\Manager\LeagueManager::getInstance($this->getServiceLocator())->recalculateLeaguesTables();

            $console = $this->getConsole();
            $optaManager = OptaManager::getInstance($this->getServiceLocator());

            $filePath = 'Z:\home\sp.loc\opta\feeds\F7\8\2012\2013-04-28-15-57-30-1596559402.json';
            $optaManager->parseF7Feed($filePath, $console);

//            $filePath = 'Z:\home\sp.loc\opta\feeds\F7\8\2012\2013-04-21-16-58-02-1643751763.json';
//            $optaManager->parseF7Feed($filePath, $console);

//            $filePath = 'Z:\home\sp.loc\opta\feeds\F1\8\2012\2013-05-13-12-16-19-987650526.json';
//            $optaManager->parseF1Feed($filePath, $console);

//            $filePath = 'Z:\home\sp.loc\opta\feeds\F40\2012-09-26-19-36-38-1292557115.json';
//            $optaManager->parseF40Feed($filePath, $console);

//            $filePath = 'Z:\home\sp.loc\opta\feeds\F40\8\2012\2013-05-10-12-37-31-1793567476.json';
//            $optaManager->parseF40Feed($filePath, $console);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, $this);
        }

    }

    /**
     * @return \Zend\Console\Adapter\AdapterInterface
     * @throws \Zend\Console\Exception\RuntimeException
     */
    private function getConsole() {
        $console = $this->getServiceLocator()->get('console');
        if (!$console instanceof Console)
            throw new RuntimeException('Cannot run this action out of console!');
        return $console;
    }

}
