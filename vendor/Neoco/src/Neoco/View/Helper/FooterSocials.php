<?php

namespace Neoco\View\Helper;

use \Application\Manager\ContentManager;
use \Application\Manager\LanguageManager;
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
        $language = LanguageManager::getInstance($this->serviceLocator)->getSelectedLanguage();
        $footerSocials = ContentManager::getInstance($this->serviceLocator)->getFooterSocials($language, true);
        if (empty($footerSocials)) {
            $defaultLanguage = LanguageManager::getInstance($this->serviceLocator)->getDefaultLanguage();
            $footerSocials = ContentManager::getInstance($this->serviceLocator)->getFooterSocials($defaultLanguage, true);
        }

        if (!empty($footerSocials)) {
            $footerSocialHtml = '<ul>';
            foreach ($footerSocials as $footerSocial)
                $footerSocialHtml .= '<li><a target="_blank" class="social-link" style="background: url(' . $footerSocial['icon'] . ') no-repeat left top;" href="' . $footerSocial['url'] . '">' . $footerSocial['copy'] . '</a></li>';
            $footerSocialHtml .= '</ul>';
            return $footerSocialHtml;
        } else return '';
    }

}