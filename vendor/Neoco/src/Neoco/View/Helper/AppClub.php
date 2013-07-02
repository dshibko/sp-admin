<?php

namespace Neoco\View\Helper;

use Application\Manager\ApplicationManager;
use Zend\View\Helper\AbstractHelper;

class AppClub extends AbstractHelper
{

    protected $appClub = null;
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

    public function getAppClub()
    {
        if (null === $this->appClub){
             $this->appClub = ApplicationManager::getInstance($this->serviceLocator)->getAppClub();
        }

        return $this->appClub;
    }

    public function __invoke()
    {
        return $this->getAppClub();
    }

}