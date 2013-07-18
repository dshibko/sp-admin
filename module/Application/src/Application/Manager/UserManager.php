<?php

namespace Application\Manager;

use Application\Model\DAOs\AccountRemovalDAO;
use Application\Model\Entities\AccountRemoval;
use Zend\Authentication\Storage\Session;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use Application\Manager\ApplicationManager;
use Application\Helper\AvatarHelper;
use Application\Model\DAOs\LanguageDAO;
use Application\Model\DAOs\UserDAO;
use Application\Model\DAOs\AvatarDAO;
use Application\Model\DAOs\CountryDAO;
use Application\Manager\ImageManager;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Session\Container as SessionContainer;

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
            if (empty($isoCode))
                $isoCode = ApplicationManager::DEFAULT_COUNTRY_ISO_CODE;
            $country = $applicationManager->getCountryByISOCode($isoCode);
            if (empty($country))
                $country =  $applicationManager->getDefaultCountry();
            $this->userGeoIpCountry = $country;
        }
        return $this->userGeoIpCountry;
    }

    /**
     * @return \Application\Model\Entities\Country
     */
    public function getGeoIpCountry()
    {
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $isoCode = $this->getUserGeoIpIsoCode();
        if (empty($isoCode)) return null;
        return $applicationManager->getCountryByISOCode($isoCode);
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

    /**
     * @param \Application\Model\Entities\Avatar $avatar
     * @return bool
     */
    private function deleteAvatarImages(\Application\Model\Entities\Avatar $avatar)
    {
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

    /**
     * @return int
     */
    public function getRegisteredUsersNumber() {
        return UserDAO::getInstance($this->getServiceLocator())->count();
    }

    /**
     * @param $days
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getUsersRegisteredInPastDays($days, $hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->getUsersRegisteredInPastDays($days, $hydrate, $skipCache);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllUsers($hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->getAllUsers($hydrate, $skipCache);
    }

    /**
     * @param array $roles
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getUsersByRoles(array $roles, $hydrate = false, $skipCache = false)
    {
        return UserDAO::getInstance($this->getServiceLocator())->getUsersByRoles($roles, $hydrate, $skipCache);
    }
    /**
     * @param $identity
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\User
     */
    public function getUserByIdentity($identity, $hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->findOneByIdentity($identity, $hydrate, $skipCache);
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\User
     */
    public function getUserById($id, $hydrate = false, $skipCache = false) {
        return UserDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    /**
     * @param $facebook_id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\User
     */
    public function getUserByFacebookId($facebook_id, $hydrate = false, $skipCache = false)
    {
        return UserDAO::getInstance($this->getServiceLocator())->getUserByFacebookId($facebook_id, $hydrate, $skipCache);
    }

    /**
     * @return string
     */
    public function getUsersExportContent() {
        $users = UserDAO::getInstance($this->getServiceLocator())->getExportUsers(true);
        $facebookManager = FacebookManager::getInstance($this->getServiceLocator());
        foreach ($users as &$user) {
            if (!empty($user['facebookId']) && !empty($user['facebookAccessToken'])) {
                $facebookData = $facebookManager->getFacebookUserInfo($user['facebookAccessToken'], $user['facebookId']);
                $user = array_merge($user, $facebookData);
            }
        }

        $exportConfig = array(
            'id' => 'number',
            'displayName' => 'string',
            'email' => 'string',
            'date' => array('date' => 'j F Y'),
            'predictions' => 'number',
            'facebook_id' => 'number',
            'facebook_first_name' => 'string',
            'facebook_last_name' => 'string',
            'facebook_username' => 'string',
            'facebook_email' => 'string',
            'facebook_avatar_link' => 'string',
            'facebook_gender' => 'string',
            'facebook_date_of_birth' => 'string',
            'facebook_locale' => 'string',
            'facebook_number_of_friends' => 'string',
            'facebook_user_likes' => 'array',
            'facebook_user_checkins' => 'array',
            'term1' => 'number',
            'term2' => 'number'
        );
        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($users, $exportConfig);
    }

    /**
     * @param array $aliasConfig
     * @return string
     */
    public function getUsersExportContentWithoutFacebookData(array $aliasConfig = array()) {
        $users = UserDAO::getInstance($this->getServiceLocator())->getExportUsersWithoutFacebookData();

        $exportConfig = array(
            'email' => 'string',
            'date' => array('date' => 'd/m/Y'),
            'birthday' => array('date' => 'd/m/Y'),
            'country' => 'string',
            'term1' => 'string',
            'term2' => 'string'
        );
        return ExportManager::getInstance($this->getServiceLocator())->exportArrayToCSV($users, $exportConfig, $aliasConfig);
    }

    /**
     * Process change password form on settings page
     * @param \Application\Form\SettingsPasswordForm $form
     * @return bool
     */
    public function processChangePasswordForm(\Application\Form\SettingsPasswordForm $form)
    {
        if ($form->isValid()){
            $data = $form->getData();
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            $user->setPassword(ApplicationManager::getInstance($this->getServiceLocator())->encryptPassword($data['new_password']));
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
            UserDAO::getInstance($this->getServiceLocator())->save($user, false, false);
            if (!$oldAvatar->getIsDefault()){
                $this->deleteAvatarImages($oldAvatar);
                AvatarDAO::getInstance($this->getServiceLocator())->remove($oldAvatar, false, false);
            }
            UserDAO::getInstance($this->getServiceLocator())->flush();
            UserDAO::getInstance($this->getServiceLocator())->clearCache();
            AvatarDAO::getInstance($this->getServiceLocator())->clearCache();
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
     * @param \Application\Model\Entities\User $user
     * @param bool $deleteFacebookApp
     * @return bool
     */
    public function deleteAccount(\Application\Model\Entities\User $user, $deleteFacebookApp = true)
    {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());
        $accountRemovalDAO = AccountRemovalDAO::getInstance($this->getServiceLocator());
        $avatarDAO = AvatarDAO::getInstance($this->getServiceLocator());
        if ($user->getFacebookId() && $deleteFacebookApp){
            //remove facebook application
            $facebook = $this->getServiceLocator()->get('facebook');
            $facebook->api('/'.$user->getFacebookId(). '/permissions', 'DELETE', array('access_token' => $user->getFacebookAccessToken()));
        }
        $avatar = $user->getAvatar();
        $userDAO->remove($user, false, false);
        if (!$avatar->getIsDefault()){
            $this->deleteAvatarImages($avatar);
            $avatarDAO->remove($avatar, false, false);
        }
        $accountRemoval = new AccountRemoval();
        $accountType = ($user->getFacebookId()) ? AccountRemoval::FACEBOOK_ACCOUNT : AccountRemoval::DIRECT_ACCOUNT;
        $accountRemoval->setDate(new \DateTime())->setAccountType($accountType);
        $accountRemovalDAO->save($accountRemoval, false, false);

        $userDAO->flush();
        $accountRemovalDAO->clearCache();
        $userDAO->clearCache();
        $avatarDAO->clearCache();

        return true;
    }

    /**
     * @return int
     */
    public function getDirectUsersNumber() {
        return UserDAO::getInstance($this->getServiceLocator())->getDirectUsersNumber();
    }

    /**
     * @return int
     */
    public function getFacebookUsersNumber() {
        return UserDAO::getInstance($this->getServiceLocator())->getFacebookUsersNumber();
    }

    /**
     * @param $season
     * @return int
     */
    public function getActiveUsersNumber($season) {
        return UserDAO::getInstance($this->getServiceLocator())->getActiveUsersNumber($season);
    }

    /**
     * @var int
     */
    private $isGeoIpBlocked = -1;

    /**
     * @return int
     */
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

    /**
     * @param \Application\Model\Entities\User $user
     */
    public function save(\Application\Model\Entities\User $user)
    {
        UserDAO::getInstance($this->getServiceLocator())->save($user);
    }

    /**
     * @return \Application\Model\Entities\Language
     */
    public function getCurrentUserLanguage()
    {
//        // todo remove
//        return LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        $language = !is_null($user) ? $user->getLanguage() : $this->getUserLanguage();
        return $language;
    }

    public function updateUserLastLoggedIn(\Application\Model\Entities\User $user = null)
    {
        if (is_null($user)){
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        }
        $lastLoggedIn = $user->getLastLoggedIn();
        if (is_null($lastLoggedIn) && $user->isAdmin()){
            $session = new SessionContainer('admin');
            $session->isFirstTimeLoggedIn = true;
        }
        $user->setLastLoggedIn(new \DateTime());
        $this->save($user);
    }

    public function saveAdmin(\Application\Model\Entities\User $user, array $data)
    {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());
        if (!empty($data)){
            $user->populate($data);
            $userDAO->save($user);
        }
    }

    public function registerLeagueUsers($league, $regionId = null)
    {
        $userDAO = UserDAO::getInstance($this->getServiceLocator());
        $userDAO->registerLeagueUsers($league->getId(), $regionId);
    }

}