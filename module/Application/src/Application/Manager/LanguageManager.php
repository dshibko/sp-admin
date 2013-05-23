<?php

namespace Application\Manager;

use \Application\Model\DAOs\LanguageDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class LanguageManager extends BasicManager {

    /**
     * @var LanguageManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LanguageManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LanguageManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return \Application\Model\Entities\Language
     */
    public function getDefaultLanguage()
    {
        return LanguageDAO::getInstance($this->getServiceLocator())->getDefaultLanguage();
    }

    public function getLanguageById($id, $hydrate = false, $skipCache = false) {
        return LanguageDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    public function getAllLanguages($hydrate = false, $skipCache = false) {
        return LanguageDAO::getInstance($this->getServiceLocator())->getAllLanguages($hydrate, $skipCache);
    }

    /**
     * @param \Application\Model\Entities\Language $language
     */
    public function setDefaultLanguage($language) {
        $languageDAO = LanguageDAO::getInstance($this->getServiceLocator());
        $oldDefaultLanguage = $this->getDefaultLanguage();
        if ($oldDefaultLanguage->getId() != $language->getId()) {
            $oldDefaultLanguage->setIsDefault(false);
            $language->setIsDefault(true);
            $languageDAO->save($oldDefaultLanguage, false, false);
            $languageDAO->save($language, false, false);
            $languageDAO->flush();
            $languageDAO->clearCache();
        }
    }

}