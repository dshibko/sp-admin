<?php

namespace Application\Manager;


use Application\Model\DAOs\ColourLanguageDAO;
use Neoco\Manager\BasicManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultSkinManager extends BasicManager {

    const CONTENT_COLOUR_TYPE = 'ContentBackground';
    const FOOTER_COLOUR_TYPE = 'FooterBackground';

    /**
     * @var DefaultSkinManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return DefaultSkinManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new DefaultSkinManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param \Zend\Form\Form $form
     * @return array
     */
    public function getDefaultSkinLanguageData(\Zend\Form\Form $form)
    {
        $data = array();
        if (!empty($form)){

            foreach($form->getFieldsets() as $fieldset){
                $language = $fieldset->getLanguage();
                $contentBackgroundColour = $fieldset->get('ContentBackground')->getValue();
                $footerBackgroundColour = $fieldset->get('FooterBackground')->getValue();

                $data['languages'][$language['id']] = array(
                    'ContentBackground' => $contentBackgroundColour,
                    'FooterBackground' => $footerBackgroundColour,
                );
            }
        }
        return $data;
    }

    public function getDefaultSkins() {
        $settingsManager = SettingsManager::getInstance($this->getServiceLocator());
        $defaultSkinImageId = $settingsManager->getSetting(SettingsManager::DEFAULT_SKIN_IMAGE);
        pr($defaultSkinImageId);
    }

    /**
     * @param $type
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getDefaultColourByTypeAndLanguageId($type, $languageId, $hydrate = false, $skipCache = false) {
        $colourLanguageDAO = ColourLanguageDAO::getInstance($this->getServiceLocator());
        $colourLanguage = $colourLanguageDAO->getColourByTypeAndLanguageId($type, $languageId, $hydrate, $skipCache);
        if ($colourLanguage == null) {
            $languageManager = LanguageManager::getInstance($this->getServiceLocator());
            $defaultLanguage = $languageManager->getDefaultLanguage();
            $colourLanguage = $colourLanguageDAO->getColourByTypeAndLanguageId($type, $defaultLanguage->getId());
        }
        return $colourLanguage;
    }

    public function getColourByTypeAndLanguageId($type, $languageId, $hydrate = false, $skipCache = false) {
        $colourLanguageDAO = ColourLanguageDAO::getInstance($this->getServiceLocator());
        return $colourLanguageDAO->getColourByTypeAndLanguageId($type, $languageId, $hydrate, $skipCache);
    }

    public function getDefaultColoursByType($type, $hydrate = false, $skipCache = false) {
        $colourLanguageDAO = ColourLanguageDAO::getInstance($this->getServiceLocator());
        return $colourLanguageDAO->getDefaultColoursByType($type, $hydrate, $skipCache);
    }
}