<?php
namespace Neoco\Validator;

use Application\Manager\ApplicationManager;
use Zend\Validator\AbstractValidator;

class ConfirmPasswordValidator  extends AbstractValidator
{
    const INCORRECT_PASSWORD = 'incorrectPassword';
    protected $password;

    protected $messageTemplates = array(
        self::INCORRECT_PASSWORD => "Incorrect"
    );
    protected $options = array();
    /**
     * @param mixed $password
     * @return $this
     */
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function __construct($password = null){
        parent::__construct();
        $this->setPassword($password);
    }

    public function isValid($value){
        $applicationManager = ApplicationManager::getInstance($this->serviceLocator);
        $this->setValue($value);
        if ($applicationManager->encryptPassword($value, $this->getPassword()) !== $this->getPassword()){
            $this->error(self::INCORRECT_PASSWORD);
            return false;
        }
        return true;
    }
}
