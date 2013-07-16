<?php

namespace Neoco\View\Helper;

use Application\Manager\ContentManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class FooterPageContent extends AbstractHelper
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function __invoke($pageType)
    {
        $contentManager = ContentManager::getInstance($this->serviceLocator);
        $content = $contentManager->getFooterPageContent($pageType);
        if ($content != ''){
            return true;
        }
        return false;
    }

}