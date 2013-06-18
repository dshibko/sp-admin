<?php
namespace Neoco\Validator;

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
        $this->setValue($value);
        if (md5($value) !== $this->getPassword()){
            $this->error(self::INCORRECT_PASSWORD);
            return false;
        }
        return true;
    }
}
