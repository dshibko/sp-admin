<?php

namespace Neoco\View\Helper;

use \Application\Manager\ContentManager;
use \Application\Manager\RegionManager;
use Zend\View\Helper\AbstractHelper;

class FooterImage extends AbstractHelper
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
        $region = RegionManager::getInstance($this->serviceLocator)->getSelectedRegion();
        $footerImages = ContentManager::getInstance($this->serviceLocator)->getFooterImages($region, true);
        if (!empty($footerImages)) {
            $footerImagesCount = count($footerImages);
            $footerImagesIndex = rand(1, $footerImagesCount);
            $selectedFooterImage = $footerImages[$footerImagesIndex - 1]['footerImage'];
            return "<img src='$selectedFooterImage' alt='footer'/>";
        } else return '';
    }

}