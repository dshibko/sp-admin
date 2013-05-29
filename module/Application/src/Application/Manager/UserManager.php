<?php

namespace Application\Manager;

use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;
use Application\Helper\AvatarHelper;
use Application\Model\DAOs\LanguageDAO;
use Application\Model\DAOs\UserDAO;
use Application\Model\DAOs\AvatarDAO;
use Application\Model\DAOs\CountryDAO;
use Application\Manager\ImageManager;
use Zend\Http\PhpEnvironment\RemoteAddress;

class UserManager extends BasicManager {

    /**
     * @var UserManager
     */
    private static $instance;

    /**
     * @var  \Application\Model\Entities\Country
     */
    protected $userGeoIpCountry;
    protected $userGeoIpIsoCode;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return UserManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new UserManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param $userGeoIpCountry
     * @return \Application\Manager\UserManager
     */
    public function setUserGeoIpCountry($userGeoIpCountry)
    {
        $this->userGeoIpCountry = $userGeoIpCountry;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Country
     */
    public function getUserGeoIpCountry()
    {
        if (null === $this->userGeoIpCountry){
            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
            $isoCode = $this->getUserGeoIpIsoCode();
            if (empty($isoCode)){
                $isoCode = ApplicationManager::DEFAULT_COUNTRY_ISO_CODE;
            }
            $applicationManager->getCountryByISOCode($isoCode);
            if (empty($country)){
                $country =  $applicationManager->getDefaultCountry();
            }
            $this->userGeoIpCountry = $country;
        }
        return $this->userGeoIpCountry;
    }

    /**
     * @return \Application\Model\Entities\Language
     */
    public function getUserLanguage()
    {
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $isoCode = $this->getUserGeoIpIsoCode();
        $language = null;
        if ($isoCode != null) {
            $country = $applicationManager->getCountryByISOCode($isoCode);
            if (!empty($country)) {
                $language = $country->getLanguage();
            }
        }
        if ($language == null)
            $language = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
        return $language;
    }

    private function deleteAvatarImages(\Application\Model\Entities\Avatar $avatar)
    {
        //TODO change web separator to directory separator
        //Delete user avatars
        $publicPath = ImageManager::getInstance($this->getServiceLocator())->getAppPublicPath();
        if (file_exists($publicPath.$avatar->getBigImagePath())){
            unlink($publicPath.$avatar->getBigImagePath());
        }
        if (file_exists($publicPath.$avatar->getMediumImagePath())){
            unlink($publicPath.$avatar->getMediumImagePath());
        }
        if (file_exists($publicPath.$avatar->getOriginalImagePath())){
            unlink($publicPath.$avatar->getOriginalImagePath());
        }
        if (file_exists($publicPath.$avatar->getSmallImagePath())){
            unlink($publicPath.$avatar->getSmallImagePath());
        }
        if (file_exists($publicPath.$avatar->getTinyImagePath())){
            unlink($publicPath.$avatar->getTinyImagePath());
        }
        return true;
    }

    public function getRegisteredUsersNumber() {
        return UserDAO::getInstance($this->getServiceLocator())->count();
    }

    public function getUsersRegisteredInPastDays($days, $hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->getUsersRegisteredInPastDays($days, $hydrate, $skipCache);
    }

    public function getAllUsers($hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->getAllUsers($hydrate, $skipCache);
    }

    public function getUserByIdentity($identity, $hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->findOneByIdentity($identity, $hydrate, $skipCache);
    }

    public function getUserById($id, $hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    public function getUserByFacebookId($facebook_id, $hydrate = false, $skipCache = false)
    {
        return UserDAO::getInstance($this->getServiceLocator())->getUserByFacebookId($facebook_id, $hydrate, $skipCache);
    }

    public function getUsersExportContent() {
        $users = UserDAO::getInstance($this->getServiceLocator())->getAllUsers(true, true);
        $exportConfig = array('id' => 'number',
            'displayName' => 'string',
            'email' => 'string',
            'birthday' => array('date' => 'j F Y'),
            'role' => array('array' => 'name'),
            'date' => array('date' => 'j F Y'));
        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($users, $exportConfig);
    }

    /**
     *   Proccess change password form on settings page
     *   @param  \Application\Form\SettingsPasswordForm $form
     *   @return bool
    */
    public function processChangePasswordForm(\Application\Form\SettingsPasswordForm $form)
    {
        if ($form->isValid()){
            $data = $form->getData();
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            if ($user->getPassword() !== md5($data['password'])){
                throw new \Exception(MessagesConstants::ERROR_INVALID_OLD_PASSWORD);
            }
            $user->setPassword(md5($data['new_password']));
            UserDAO::getInstance($this->getServiceLocator())->save($user);
            return true;
        }

        return false;
    }

    /**
     *   Proccess change email on settings page
     *   @param  \Application\Form\SettingsEmailForm $form
     *   @return bool
     */
    public function processChangeEmailForm(\Application\Form\SettingsEmailForm $form)
    {
        if ($form->isValid()){
            $data = $form->getData();
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            $old_email = $user->getEmail();
            $user->setEmail($data['email']);
            UserDAO::getInstance($this->getServiceLocator())->save($user);
            //Set new and clear old email from session
            AuthenticationManager::getInstance($this->getServiceLocator())->changeIdentity($old_email, $user->getEmail());
            return true;
        }

        return false;
    }

    /**
     *   Proccess change display name settings page
     *   @param  \Application\Form\SettingsDisplayNameForm $form
     *   @return bool
     */
    public function processChangeDisplayNameForm(\Application\Form\SettingsDisplayNameForm $form)
    {
        if ($form->isValid()){
            $data = $form->getData();
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            $user->setDisplayName($data['display_name']);
            UserDAO::getInstance($this->getServiceLocator())->save($user);
            return true;
        }

        return false;
    }

    /**
     *   Proccess change avatar on settings page
     *   @param  \Application\Model\Entities\Avatar $newAvatar
     *   @return bool
     */
    public function processChangeAvatarForm(\Application\Model\Entities\Avatar $newAvatar)
    {
        if (!empty($newAvatar)){
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            $oldAvatar = $user->getAvatar();
            $user->setAvatar($newAvatar);
            UserDAO::getInstance($this->getServiceLocator())->save($user, false);
            if (!$oldAvatar->getIsDefault()){
                //TODO move these lines to method
                $this->deleteAvatarImages($oldAvatar);
                AvatarDAO::getInstance($this->getServiceLocator())->remove($oldAvatar, false);
            }
            UserDAO::getInstance($this->getServiceLocator())->flush();
            return true;
        }
        return false;
    }
    /**
     *    @param \Zend\Form\Form $form
     *    @param int $defaultAvatarId
     *    @return \Application\Model\Entities\Avatar
    */
    public function getUserAvatar(\Zend\Form\Form $form, $defaultAvatarId)
    {
        $avatarHelper = new AvatarHelper($form->get('avatar'), $this->getServiceLocator());
        $avatarHelper->setDefaultAvatarId(!empty($defaultAvatarId) ? $defaultAvatarId : null);
        $form->isValid();
        if ($avatarHelper->validate()) {
            $avatarHelper->save()->resize();
        } else {
            $form->setMessages(array('avatar' => $avatarHelper->getErrorMessages()));
            return false;
        }
        return $avatarHelper->getAvatar();
    }

    /**
     *   Proccess change language on settings page
     *   @param  \Application\Form\SettingsLanguageForm $form
     *   @return bool
     */
    public function processChangeLanguageForm(\Application\Form\SettingsLanguageForm $form)
    {
        if ($form->isValid()){
            $data = $form->getData();
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            $language = LanguageDAO::getInstance($this->getServiceLocator())->findOneById($data['language']);
            $user->setLanguage($language);
            UserDAO::getInstance($this->getServiceLocator())->save($user);
            return true;
        }

        return false;
    }

    /**
     *   Proccess change public profile option on settings page
     *   @param  \Application\Form\SettingsPublicProfileForm $form
     *   @return bool
     */
    public function processChangePublicProfileForm(\Application\Form\SettingsPublicProfileForm $form)
    {
        if ($form->isValid()){
            $data = $form->getData();
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            $user->setIsPublic(!empty($data['is_public']) ? 1 : 0);
            UserDAO::getInstance($this->getServiceLocator())->save($user);
            return true;
        }

        return false;
    }
    /**
     *   Delete User account
     *   @param \Application\Model\Entities\User $user
     *   @return bool
     */
    public function deleteAccount(\Application\Model\Entities\User $user, $deleteFacebookApp = true)
    {
        //TODO remove predictions, etc all user data
        if ($user->getFacebookId() && $deleteFacebookApp){
            //remove facebook application
            $facebook = $this->getServiceLocator()->get('facebook');
            $facebook->api('/'.$user->getFacebookId(). '/permissions', 'DELETE', array('access_token' => $user->getFacebookAccessToken()));
        }
        $avatar = $user->getAvatar();
        UserDAO::getInstance($this->getServiceLocator())->remove($user, false, false);
        if (!$avatar->getIsDefault()){
            //TODO move these lines to method
            $this->deleteAvatarImages($avatar);
            AvatarDAO::getInstance($this->getServiceLocator())->remove($avatar, false, false);
        }
        UserDAO::getInstance($this->getServiceLocator())->flush();
        return true;
    }

    public function getActiveUsersNumber($season) {
        return UserDAO::getInstance($this->getServiceLocator())->getActiveUsersNumber($season);
    }

    private $isGeoIpBlocked = -1;

    private function getIsGeoIpBlocked() {
        if ($this->isGeoIpBlocked === -1) {
            $config = $this->getServiceLocator()->get('config');
            $this->isGeoIpBlocked = $config['is_geo_ip_blocked'];
        }
        return $this->isGeoIpBlocked;
    }

    /**
     * @return string
     */
    public function getUserGeoIpIsoCode()
    {
        if ($this->getIsGeoIpBlocked())
            return null;
        if (null === $this->userGeoIpIsoCode){
            $remoteAddresses = new RemoteAddress();
            $this->userGeoIpIsoCode = geoip_country_code_by_name($remoteAddresses->getIpAddress());
        }
        return $this->userGeoIpIsoCode;
    }

}