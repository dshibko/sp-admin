<?php

namespace Neoco\View\Helper;

use \Application\Manager\MatchManager;
use \Application\Manager\PredictionManager;
use \Application\Manager\SettingsManager;
use \Application\Manager\ApplicationManager;
use Zend\View\Helper\AbstractHelper;

/**
 *
 */
class UnreadItems extends AbstractHelper
{

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return \Neoco\View\Helper\UnreadItems
     */
    public function __invoke()
    {
        return $this;
    }

    protected $isFirstResultView = false;

    public function setIsFirstResultView($isFirstResultView) {
        $this->isFirstResultView = $isFirstResultView;
    }

    /**
     * @param string $route
     * @return string
     */
    public function getItems($route)
    {
        $items = 0;
        switch ($route) {
            case \Application\Controller\PredictController::PREDICT_ROUTE:
                $items = PredictionManager::getInstance($this->serviceLocator)->getPredictableCount();
                break;
            case \Application\Controller\ResultsController::RESULTS_ROUTE:
                $items = MatchManager::getInstance($this->serviceLocator)->getFinishedNotViewedMatchesInTheSeasonNumber();
                if ($this->isFirstResultView) $items++;
                break;
        }
        return $items;
    }

}