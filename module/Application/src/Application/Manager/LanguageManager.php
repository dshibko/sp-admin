<?php

namespace Application\Manager;

use \Application\Model\DAOs\LanguageDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use \Application\Model\Entities\Language;
use \Application\Model\DAOs\CountryDAO;

require_once getcwd() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'mogenerator' .DIRECTORY_SEPARATOR .'php-mo.php';

class LanguageManager extends BasicManager {

    const LANGUAGE_FILES_DIRECTORY = 'module/Application/language/';
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

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Language
     */
    public function getLanguageById($id, $hydrate = false, $skipCache = false) {
        return LanguageDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
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
    /**
     * @param $fileName
     * @return string
     */
    public function getPoFilePath($fileName)
    {
        return $this->getAppLanguageFolder() . $fileName . '.po';
    }
    /**
     * @return string
     */
    public function getAppLanguageFolder()
    {
        return getcwd() .DIRECTORY_SEPARATOR . self::LANGUAGE_FILES_DIRECTORY;
    }
    /**
     * @param string $fileName
     * @return array $data
     */
    public function getPoFileContent($fileName = 'en_EN')
    {
        $data = array();
        $poFile = $this->getPoFilePath($fileName);
        if (file_exists($poFile)){
            $poParser = $this->getServiceLocator()->get('poparser');
            $content = $poParser->read($poFile);
            if (!empty($content)){
                foreach($content as $id => $value){
                    if (!empty($id)){
                        $data[$id] = !empty($value['msgstr'][0]) ? $value['msgstr'][0] : '';
                    }
                }
            }
        }
        return $data;
    }

    /**
     *  @param array $data
     *  @param $poFileName
     *  @return boolean
     */
    public function savePoFileContent($poFileName, array $data)
    {
        $poFile = $this->getPoFilePath($poFileName);

        if (!file_exists($poFile)){
            $defaultLanguage = LanguageDAO::getInstance($this->getServiceLocator())->getDefaultLanguage();
            $defaultLanguageFile = $this->getPoFilePath($defaultLanguage->getLanguageCode());
            if (!copy($defaultLanguageFile, $poFile)){
                return false;
            }
        }
        if (!empty($data)){
            $poParser = $this->getServiceLocator()->get('poparser');
            foreach($data as $msgid => $msgstr){
                $poParser->update_entry( $msgid, $msgstr);
            }
            $poParser->write($poFile);
            return true;
        }

        return false;
    }

    /**
     * @param $poFileName
     * @return bool
     */
    public function convertPoToMo($poFileName)
    {
        $poFile = $this->getPoFilePath($poFileName);
        if (file_exists($poFile)){
           return phpmo_convert($poFile);
        }
        return false;
    }


    /**
     * @return array
     */
    public function getLanguagesSelectOptions()
    {
        $data = $this->getAllLanguages(true);
        $languages = array();
        if (!empty($data) && is_array($data)){
            foreach($data as $language){
                $languages[$language['id']] = $language['displayName'];
            }
        }
        return $languages;
    }

    /**
     * @param \Application\Model\Entities\Language $language
     * @param array $countries
     * @return bool
     */
    public function updateLanguageCountries(Language $language, array $countries)
    {
        if (!empty($countries)){
            $countryDAO = CountryDAO::getInstance($this->getServiceLocator());
            //Clear old countries
            foreach ($language->getCountries() as $country) {
                $country->setLanguage(null);
                $countryDAO->save($country, false, false);
            }
            foreach($countries as $countryId){
                $country = $countryDAO->findOneById($countryId);
                $country->setLanguage($language);
                $countryDAO->save($country, false, false);
            }
           $countryDAO->flush();
           $countryDAO->clearCache();
        }
        return true;
    }

    /**
     * @param $fieldsetClassName
     * @return array
     */
    public function getLanguagesFieldsets($fieldsetClassName)
    {
        $languages = $this->getAllLanguages(true);
        $fieldsets = array();
        foreach ($languages as $language){
            if (class_exists($fieldsetClassName)){
                $fieldsets[] = new $fieldsetClassName($language);
            }
        }
        return $fieldsets;
    }


}