<?php

namespace Application\Manager;

use \Application\Model\DAOs\SettingsDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class SettingsManager extends BasicManager {

    const SITE_BACKGROUND_COLOR_KEY = 'site-background-colour';
    const SITE_FOOTER_COLOR_KEY = 'site-footer-colour';
    const AHEAD_PREDICTIONS_DAYS = 'ahead-predictions-days';

    /**
     * @var SettingsManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return SettingsManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new SettingsManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function getSettings($hydrate = false, $skipCache = false) {
        return SettingsDAO::getInstance($this->getServiceLocator())->findAll($hydrate, $skipCache);
    }

    public function getSettingsAsArray($skipCache = false) {
        $settings = $this->getSettings(true, $skipCache);
        $settingsArray = array();
        foreach ($settings as $setting)
            $settingsArray[$setting['settingKey']] = $setting['settingValue'];
        return $settingsArray;
    }

    public function saveSettings(array $settings) {
        $settingsDAO = SettingsDAO::getInstance($this->getServiceLocator());
        foreach ($settings as $k => $v) {
            $settingObj = $settingsDAO->findOneByKey($k);
            if ($settingObj != null) {
                $settingObj->setSettingValue($v);
                $settingsDAO->save($settingObj, false, false);
            }
        }
        $settingsDAO->flush();
        $settingsDAO->clearCache();
    }

    protected $settingsArray = null;

    /**
     * @param string $key
     * @return string|bool
     */
    public function getSetting($key) {
        if ($this->settingsArray == null)
            $this->settingsArray = $this->getSettingsAsArray();
        return array_key_exists($key, $this->settingsArray) ? $this->settingsArray[$key] : false;
    }

}