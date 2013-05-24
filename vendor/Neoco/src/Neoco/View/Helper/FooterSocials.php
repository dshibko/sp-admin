<?php

namespace Neoco\View\Helper;

use \Application\Manager\ContentManager;
use \Application\Manager\RegionManager;
use Zend\View\Helper\AbstractHelper;

class FooterSocials extends AbstractHelper
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
        $footerSocials = ContentManager::getInstance($this->serviceLocator)->getFooterSocials($region, true);
        if (!empty($footerSocials)) {
            $footerSocialHtml = '<ul>';
            foreach ($footerSocials as $footerSocial)
                $footerSocialHtml .= '<li><a class="social-link" style="background: url(' . $footerSocial['icon'] . ') no-repeat left top;" href="' . $footerSocial['url'] . '">' . $footerSocial['copy'] . '</a></li>';
            $footerSocialHtml .= '</ul>';
            return $footerSocialHtml;
        } else return '';
    }

}