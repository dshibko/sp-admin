<?php

namespace Opta\Controller;

use \Opta\Manager\OptaManager;
use \Application\Manager\ExceptionManager;
use \Zend\Console\Adapter\AdapterInterface as Console;
use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\Console\Exception\RuntimeException;

class DispatcherController extends AbstractActionController {

    public function dispatchAction() {

        try {

            $this->parseF40File('Z:\home\zend.loc\opta\feeds\F40\2012-09-26-19-36-38-1292557115.json', $this->getConsole());
//            $this->parseF40File('Z:\home\zend.loc\opta\feeds\F40\8\2012\2013-05-10-12-37-31-1793567476.json', $this->getConsole());

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

    /**
     * @param string $filePath
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    private function parseF40File($filePath, $console) {

        $fileContents = file_get_contents($filePath);
        $json = json_decode($fileContents);
        $xmlContent = simplexml_load_string($json->content);
        OptaManager::getInstance($this->getServiceLocator())->parseF40Feed($xmlContent, $console);

    }

}
