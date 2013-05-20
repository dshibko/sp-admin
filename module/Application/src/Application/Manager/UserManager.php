<?php

namespace Application\Manager;

use \Application\Model\DAOs\UserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;

class UserManager extends BasicManager {

    /**
     * @var UserManager
     */
    private static $instance;

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
     *   Proccess change password form on settings page
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
            AuthenticationManager::getInstance($this->getServiceLocator())->getAuthService()->getStorage()->clear($old_email);
            AuthenticationManager::getInstance($this->getServiceLocator())->getAuthService()->getStorage()->write($user->getEmail());
            return true;
        }

        return false;
    }

    /**
     *   Proccess change password form on settings page
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
     *   Proccess change password form on settings page
     *   @param  \Application\Form\SettingsAvatarForm $form
     *   @return bool
     */
    public function processChangeAvatarForm(\Application\Form\SettingsAvatarForm $form)
    {
        die('processing avatar...');
        /*if ($form->isValid()){
            /*$data = $form->getData();
            $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
            $user->setDisplayName($data['display_name']);
            UserDAO::getInstance($this->getServiceLocator())->save($user);
            return true;
        }*/

        return false;
    }

}