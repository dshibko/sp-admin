<?php

namespace Neoco\View\Helper;

use Application\Manager\ApplicationManager;
use Application\Manager\ContentManager;
use Application\Manager\LanguageManager;
use Application\Manager\UserManager;
use Application\Model\Entities\Language;
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
        return '<a href="/" class="logo clearfix">';
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
        $languageManager = LanguageManager::getInstance($this->serviceLocator);
        $userManager = UserManager::getInstance($this->serviceLocator);
        $contentManager = ContentManager::getInstance($this->serviceLocator);

        $userLanguage = $userManager->getCurrentUserLanguage();
        $defaultLanguage = $languageManager->getDefaultLanguage();

        if ($defaultLanguage instanceof Language){
            $logotype = $contentManager->getLogotypeByLanguage($defaultLanguage->getId(), true);
            if (!empty($logotype) && is_array($logotype)){

                if ($userLanguage instanceof Language && $userLanguage->getId() !== $defaultLanguage->getId()){
                    $userLogotype = $contentManager->getLogotypeByLanguage($userLanguage->getId(), true);
                    if (!empty($userLogotype) && is_array($userLogotype)){
                        $logotype = $contentManager->extendContent($logotype, $userLogotype);
                    }
                }

                if (empty($logotype['emblem']['path']) || empty($logotype['logotype'])){
                    return $this->getDefaultLogotype();
                }

                $html = $this->getOpenTag();
                $html .= '<img src="'.$logotype['emblem']['path'].'" alt=""/>';
                $html .= '<img src="'.$logotype['logotype'].'" alt=""/>';
                $html .= $this->getCloseTag();
                return $html;
            }
        }

        return $this->getDefaultLogotype();


    }
}