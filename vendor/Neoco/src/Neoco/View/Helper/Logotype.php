<?php

namespace Neoco\View\Helper;

use Application\Manager\ApplicationManager;
use Application\Manager\ContentManager;
use Application\Manager\UserManager;
use Zend\View\Helper\AbstractHelper;
use Application\Manager\AvatarManager;

class Logotype extends AbstractHelper
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    private function getOpenTag()
    {
        return '<a href="/" class="logo">';
    }
    private function getCloseTag()
    {
        return '</a>';
    }
    private function getDefaultLogotype()
    {
        $config = $this->serviceLocator->get('config');
        return $this->getOpenTag(). '<img src="'.$config['default_logotype_image_source'].'" alt="Truefan Score Predictor">' .$this->getCloseTag();
    }
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function __invoke()
    {
        $applicationManager = ApplicationManager::getInstance($this->serviceLocator);
        $userManager = UserManager::getInstance($this->serviceLocator);
        $contentManager = ContentManager::getInstance($this->serviceLocator);

        $user = $applicationManager->getCurrentUser();
        $language = !is_null($user) ? $user->getLanguage() : $userManager->getUserLanguage();
        $logotype = $contentManager->getLogotypeByLanguage($language->getId());
        if (is_null($logotype)){
            return $this->getDefaultLogotype();
        }
        $html = $this->getOpenTag();
        $html .= '<img src="'.$logotype->getEmblem().'" alt=""/>';
        $html .= '<img src="'.$logotype->getLogotype().'" alt=""/>';
        $html .= $this->getCloseTag();
        return $html;
    }
}