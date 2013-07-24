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

    public function getSelectedLanguage() {
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $user = $applicationManager->getCurrentUser();
        $country = null;
        if ($user == null) {
            $userManager = UserManager::getInstance($this->getServiceLocator());
            $isoCode = $userManager->getUserGeoIpIsoCode();
            if ($isoCode != null)
                $country = $applicationManager->getCountryByISOCode($isoCode);
        } else
            $country = $user->getCountry();
        if ($country == null || $country->getLanguage() == null)
            return $this->getDefaultLanguage();
        else
            return $country->getLanguage();
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
            $defaultLanguage = LanguageDAO::getInstance($this->getServiceLocator())->getDefaultLanguage();
        if (!file_exists($poFile)){
            $defaultLanguageFile = $this->getPoFilePath($defaultLanguage->getLanguageCode());
            if (!copy($defaultLanguageFile, $poFile)){
                return false;
            }
        }
        if (!empty($data)){
            $poParser = $this->getServiceLocator()->get('poparser');
            $defaultData = $this->getPoFileContent($defaultLanguage->getLanguageCode());
            foreach($data as $msgid => $msgstr){
                if ($msgstr == ''){
                    $msgstr = (isset($defaultData[$msgid]) && $defaultData[$msgid]) != ''  ? $defaultData[$msgid] : $msgid;
                }
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
           LanguageDAO::getInstance($this->getServiceLocator())->clearCache();
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
        $defaultLanguage = $this->getDefaultLanguage();
        $fieldsets = array();
        foreach ($languages as $language){
            if (class_exists($fieldsetClassName)){
                $fieldsets[] = new $fieldsetClassName($language, $language['isDefault']);
            }
        }
        return $fieldsets;
    }

    /**
     * @param array $languageFieldsets
     * @return array
     */
    public function getFeaturedPlayerLanguagesData(array $languageFieldsets)
    {
        $languagesData = array();
        //Prepare regions data
        foreach ($languageFieldsets as $fieldset) {
            $language = $fieldset->getData();
            $languagesData[$language['id']] = array(
                'featured_player' => array(
                    'id' => $fieldset->get('featured_player')->getValue(),
                    'goals' => $fieldset->get('player_goals')->getValue(),
                    'matches_played' => $fieldset->get('player_matches_played')->getValue(),
                    'match_starts' => $fieldset->get('player_match_starts')->getValue(),
                    'minutes_played' => $fieldset->get('player_minutes_played')->getValue(),
                ),
            );
        }
        return $languagesData;
    }

    /**
     * @param array $languageFieldsets
     * @return array
     */
    public function getFeaturedGoalkeeperLanguagesData(array $languageFieldsets)
    {
        $languagesData = array();
        //Prepare regions data
        foreach ($languageFieldsets as $fieldset) {
            $language = $fieldset->getData();
            $languagesData[$language['id']] = array(
                'featured_goalkeeper' => array(
                    'id' => $fieldset->get('featured_goalkeeper')->getValue(),
                    'saves' => $fieldset->get('goalkeeper_saves')->getValue(),
                    'matches_played' => $fieldset->get('goalkeeper_matches_played')->getValue(),
                    'penalty_saves' => $fieldset->get('goalkeeper_penalty_saves')->getValue(),
                    'clean_sheets'  => $fieldset->get('goalkeeper_clean_sheets')->getValue()
                ),
            );
        }
        return $languagesData;
    }

    /**
     * @param array $languageFieldsets
     * @return array
     */
    public function getFeaturedPredictionLanguagesData(array $languageFieldsets)
    {
        $imageManager = ImageManager::getInstance($this->getServiceLocator());
        $languagesData = array();
        //Prepare regions data
        foreach ($languageFieldsets as $fieldset) {
            $language = $fieldset->getData();
            $languagesData[$language['id']] = array(
                'featured_prediction' => array(
                    'name' => $fieldset->get('prediction_name')->getValue(),
                    'copy' => $fieldset->get('prediction_copy')->getValue(),
                )
            );
            $predictionImage = $fieldset->get('prediction_image')->getValue();
            $pImage = ($predictionImage['error'] != UPLOAD_ERR_NO_FILE) ? $imageManager->saveUploadedImage($fieldset->get('prediction_image'), ImageManager::IMAGE_TYPE_REPORT) : null;
            if ($pImage){
                $languagesData[$language['id']]['featured_prediction']['image'] = $pImage;
            }
        }
        return $languagesData;
    }

    /**
     * @param array $languageFieldsets
     * @return array
     */
    public function getPreMatchReportLanguagesData(array $languageFieldsets)
    {
        $imageManager = ImageManager::getInstance($this->getServiceLocator());
        $languagesData = array();
        //Prepare regions data
        foreach ($languageFieldsets as $fieldset) {
            $language = $fieldset->getData();
            $languagesData[$language['id']] = array(
                'pre_match_report' => array(
                    'title' => $fieldset->get('pre_match_report_title')->getValue(),
                    'intro' => $fieldset->get('pre_match_report_intro')->getValue(),
                )
            );
            $headerImage = $fieldset->get('pre_match_report_header_image')->getValue();
            $hImage = ($headerImage['error'] != UPLOAD_ERR_NO_FILE) ? $imageManager->saveUploadedImage($fieldset->get('pre_match_report_header_image'), ImageManager::IMAGE_TYPE_REPORT) : null;
            if ($hImage){
                $languagesData[$language['id']]['pre_match_report']['header_image_path'] = $hImage;
            }
        }
        return $languagesData;
    }

    public function getPostMatchReportLanguagesData(array $languageFieldsets)
    {
        $imageManager = ImageManager::getInstance($this->getServiceLocator());
        $languagesData = array();
        //Prepare regions data
        foreach ($languageFieldsets as $fieldset) {
            $language = $fieldset->getData();
            $languagesData[$language['id']] = array(
                'post_match_report' => array(
                    'title' => $fieldset->get('post_match_report_title')->getValue(),
                    'intro' => $fieldset->get('post_match_report_intro')->getValue(),
                )
            );
            $headerImage = $fieldset->get('post_match_report_header_image')->getValue();
            $hImage = ($headerImage['error'] != UPLOAD_ERR_NO_FILE) ? $imageManager->saveUploadedImage($fieldset->get('post_match_report_header_image'), ImageManager::IMAGE_TYPE_REPORT) : null;
            if ($hImage){
                $languagesData[$language['id']]['post_match_report']['header_image_path'] = $hImage;
            }
        }
        return $languagesData;
    }

}