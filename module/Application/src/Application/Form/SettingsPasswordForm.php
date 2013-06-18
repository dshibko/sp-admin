<?php
namespace Application\Form;

use Zend\Form\Form;
use \Application\Form\Filter\SettingsPasswordFilter;
use Application\Form\Filter\RegistrationFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

class SettingsPasswordForm extends Form {
    protected $type;
    protected $serviceLocator;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
    public function __construct($type = 'change_password', ServiceLocatorInterface $serviceLocator) {
        parent::__construct('reset');

        $this->setInputFilter(new SettingsPasswordFilter($serviceLocator));
        $this->setAttribute('method', 'post')->setAttribute('id', 'settings-change-password');
        $this->setType($type);

        //Old Password
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'maxlength' => RegistrationFilter::PASSWORD_MAX_LENGTH,
                'class' => 'required',
                'id' => 'old-password'
            ),
            'options' => array(
                'label' => 'Old password',
            ),
        ));

        //New Password
        $this->add(array(
            'name' => 'new_password',
            'attributes' => array(
                'type' => 'password',
                'maxlength' => RegistrationFilter::PASSWORD_MAX_LENGTH,
                'id' => 'new-password',
                'class' => 'required password'
            ),
            'options' => array(
                'label' => 'New password',
            ),
        ));

        //Confirm New Password
        $this->add(array(
            'name' => 'confirm_new_password',
            'attributes' => array(
                'type' => 'password',
                'id' => 'confirm-new-password',
                'maxlength' => RegistrationFilter::PASSWORD_MAX_LENGTH,
                'class' => 'required password'
            ),
            'options' => array(
                'label' => 'Retype new password',
            ),
        ));

        $this->add(array(
            'name' => 'type',
            'type'  => 'hidden',
            'attributes' => array(
                'value' => $this->getType()
            ),

        ));
        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Save changes',
                'id' => 'submitbutton',
            ),
        ));
    }
}