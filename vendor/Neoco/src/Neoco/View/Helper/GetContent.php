<?php

namespace Neoco\View\Helper;

use \Application\Manager\RegionManager;
use \Application\Manager\ContentManager;
use Zend\View\Helper\AbstractHelper;

class GetContent extends AbstractHelper
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

    private $content;

    /**
     * @return \Application\Model\Entities\User
     */
    public function __invoke()
    {
        if ($this->content === null) {
            $contentManager = ContentManager::getInstance($this->serviceLocator);
            $regionManager = RegionManager::getInstance($this->serviceLocator);
            $region = $regionManager->getSelectedRegion();
            $this->content = $contentManager->getRegionContent($region, true);
        }
        return $this->content;
    }

}