<?php

namespace Neoco\View\Helper;

use Application\Manager\LanguageManager;
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
            $languageManager = LanguageManager::getInstance($this->serviceLocator);
            $language = $languageManager->getSelectedLanguage();
            $this->content = $contentManager->getLanguageContent($language, true);
        }
        return $this->content;
    }

}