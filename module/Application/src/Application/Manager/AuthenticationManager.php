<?php

namespace Application\Manager;

use \Application\Model\DAOs\UserDAO;
use \Application\Model\DAOs\RecoveryDAO;
use \Application\Model\Entities\Recovery;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class AuthenticationManager extends BasicManager {

    /**
     * @var ApplicationManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return AuthenticationManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new AuthenticationManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @var \Zend\Authentication\AuthenticationService
     */
    protected $authService;

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService() {
        if ($this->authService == null)
            $this->authService = $this->getServiceLocator()->get('AuthService');
        return $this->authService;
    }

    /**
     * @param string $identity
     * @param string $pwd
     * @param bool $remember
     * @return \Zend\Authentication\Result
     */
    public function authenticate($identity, $pwd, $remember = true) {
        $this->getAuthService()->getAdapter()
            ->setIdentityValue($identity)
            ->setCredentialValue($pwd);
        $result = $this->getAuthService()->authenticate();
        if ($result->isValid()) {
            $this->signIn($identity,$remember);
        }
        return $result;
    }

    public function signIn($identity, $remember = false)
    {
        if ($remember){
            $this->getAuthService()->getStorage()->setRememberMe(1);
        }
        $this->getAuthService()->getStorage()->write($identity);
        UserManager::getInstance($this->getServiceLocator())->updateUserLastLoggedIn();
    }
    /**
     * @return void
     */
    public function logout() {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
    }

    /**
     * @param string $email
     * @return \Application\Model\Entities\User
     */
    public function findUserByEmail($email) {
        return UserDAO::getInstance($this->getServiceLocator())->findOneByIdentity($email);
    }

    /**
     * @param \Application\Model\Entities\User $user
     * @param bool $fromAdmin
     */
    public function sendPasswordResetEmail($user, $fromAdmin = false) {
        $recovery = new Recovery();
        $recovery->setHash($this->generateHash());
        $recovery->setUser($user);
        $recovery->setDate(new \DateTime());
        RecoveryDAO::getInstance($this->getServiceLocator())->save($recovery);
        MailManager::getInstance($this->getServiceLocator())->sendPasswordRecoveryEmail($user->getEmail(), $recovery->getHash(), $fromAdmin);
    }

    /**
     * @param string $hash
     * @return \Application\Model\Entities\Recovery
     */
    public function checkHash($hash) {
        return RecoveryDAO::getInstance($this->getServiceLocator())->checkHash($hash);
    }

    /**
     * @param string $hash
     * @return \Application\Model\Entities\Recovery
     */
    public function checkUserHash($hash) {
        return RecoveryDAO::getInstance($this->getServiceLocator())->checkUserHash($hash);
    }

    /**
     * @param string $hash
     * @return \Application\Model\Entities\Recovery
     */
    public function checkHashDate($hash, $date = '1H') {
        return RecoveryDAO::getInstance($this->getServiceLocator())->checkHashDate($hash, $date);
    }
    /**
     * @param \Application\Model\Entities\Recovery $recovery
     * @param $password
     */
    public function saveNewPassword($recovery, $password) {
        $user = $recovery->getUser();
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $user->setPassword($applicationManager->encryptPassword($password));
        UserDAO::getInstance($this->getServiceLocator())->save($user);
        $recovery->setIsActive(false);
        RecoveryDAO::getInstance($this->getServiceLocator())->save($recovery);
    }

    /**
     * @param string $password
     * @return string
     */
    private function preparePassword($password) {
        return md5($password);
    }

    /**
     * @return string
     */
    private function generateHash() {
        return md5(rand(1, 100) . microtime() . rand(1, 100));
    }

    /**
     * @var \Application\Model\Helpers\AuthStorage
     */
    protected $storage;

    /**
     * @return \Application\Model\Helpers\AuthStorage
     */
    public function getSessionStorage() {
        if ($this->storage == null)
            $this->storage = $this->getServiceLocator()->get('AuthStorage');
        return $this->storage;
    }

    /**
     *
     *   @param string $old_identity
     *   @param string $new_identity
     *   @return AuthenticationManager
    */
    public function changeIdentity($old_identity, $new_identity)
    {
        $this->getAuthService()->getStorage()->clear($old_identity);
        $this->getAuthService()->getStorage()->write($new_identity);
        return $this;
    }

}
