<?php

namespace Neoco\View\Helper;

use \Application\Manager\SettingsManager;
use \Application\Manager\ContentManager;
use \Application\Manager\RegionManager;
use Zend\View\Helper\AbstractHelper;

class SettingsHelper extends AbstractHelper
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
     * @return string
     */
    public function __invoke()
    {
        return $this;
    }

    public function getBackgroundColor() {
        return SettingsManager::getInstance($this->serviceLocator)->getSetting(SettingsManager::SITE_BACKGROUND_COLOR_KEY);
    }

    public function getFooterColor() {
        return SettingsManager::getInstance($this->serviceLocator)->getSetting(SettingsManager::SITE_FOOTER_COLOR_KEY);
    }

}