<?php

namespace Application\Helper;


use Application\Manager\DefaultSkinManager;
use Application\Manager\SettingsManager;
use Application\Manager\UserManager;
use Application\Model\DAOs\ColourLanguageDAO;
use Application\Model\DAOs\ContentImageDAO;
use Zend\View\Helper\AbstractHelper;

class DefaultSkinHelper extends AbstractHelper
{

    const FAIL_COLOUR = '#ffffff';

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

    public function __invoke()
    {
        return $this;
    }

    public function getHeaderImage() {
        $settingsManager = SettingsManager::getInstance($this->serviceLocator);
        $contentImageDAO = ContentImageDAO::getInstance($this->serviceLocator);
        $defaultSkinImageId = $settingsManager->getSetting(SettingsManager::DEFAULT_SKIN_IMAGE);
        return ($defaultSkinImageId > 0) ? $contentImageDAO->findOneById($defaultSkinImageId) : null;
    }

    public function getContentBackgroundColour() {
        $defaultSkinManager = DefaultSkinManager::getInstance($this->serviceLocator);
        $userManager = UserManager::getInstance($this->serviceLocator);
        $userLanguage = $userManager->getCurrentUserLanguage();
        $backgroundColour = $defaultSkinManager->getDefaultColourByTypeAndLanguageId(DefaultSkinManager::CONTENT_COLOUR_TYPE, $userLanguage->getId());
        return ($backgroundColour != null) ? $backgroundColour->getColour() : self::FAIL_COLOUR;
    }

    public function getFooterBackgroundColour() {
        $defaultSkinManager = DefaultSkinManager::getInstance($this->serviceLocator);
        $userManager = UserManager::getInstance($this->serviceLocator);
        $userLanguage = $userManager->getCurrentUserLanguage();
        $backgroundColour = $defaultSkinManager->getDefaultColourByTypeAndLanguageId(DefaultSkinManager::FOOTER_COLOUR_TYPE, $userLanguage->getId());
        return ($backgroundColour != null) ? $backgroundColour->getColour() : self::FAIL_COLOUR;
    }
}