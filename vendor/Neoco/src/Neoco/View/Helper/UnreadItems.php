<?php

namespace Neoco\View\Helper;

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
     * @param string $route
     * @return string
     */
    public function __invoke($route)
    {
        $items = 0;
        switch ($route) {
            case \Application\Controller\PredictController::PREDICT_ROUTE:
                $items = PredictionManager::getInstance($this->serviceLocator)->getPredictableCount();
                break;
        }
        return $items;
    }

}