<?php

namespace Neoco\View\Helper;

use \Application\Manager\ContentManager;
use Application\Manager\LanguageManager;
use \Application\Manager\RegionManager;
use Application\Manager\UserManager;
use Application\Model\Entities\Language;
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
        $languageManager = LanguageManager::getInstance($this->serviceLocator);
        $userManager = UserManager::getInstance($this->serviceLocator);
        $contentManager = ContentManager::getInstance($this->serviceLocator);

        $userLanguage = $userManager->getCurrentUserLanguage();
        $defaultLanguage = $languageManager->getDefaultLanguage();
        $html = '';
        if ($defaultLanguage instanceof Language){
            $footerImages = $contentManager->getFooterImages($defaultLanguage, true);
            if ($userLanguage instanceof Language && $userLanguage->getId() !== $defaultLanguage->getId()){
                $userFooterImages = $contentManager->getFooterImages($userLanguage, true);
                $footerImages = $contentManager->extendContent($footerImages, $userFooterImages);
            }
            if (!empty($footerImages)) {
                $footerImagesCount = count($footerImages);
                $footerImagesIndex = rand(1, $footerImagesCount);
                $selectedFooterImage = $footerImages[$footerImagesIndex - 1]['footerImage'];
                $html = "<img src='$selectedFooterImage' alt='footer'/>";
            }
        }

        return $html;
    }

}