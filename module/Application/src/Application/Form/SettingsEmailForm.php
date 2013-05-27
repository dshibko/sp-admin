<?php
namespace Application\Form;

use Zend\Form\Form;
use \Application\Form\Filter\SettingsEmailFilter;
use Application\Form\Filter\RegistrationFilter;

class SettingsEmailForm extends Form {
    protected $type;


    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
    public function __construct($type = 'change_email') {
        parent::__construct();
        $this->setAttribute('method', 'post')->setAttribute('id', 'settings-change-email');
        $this->setType($type);

        //Email
        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'class' => 'required email',
                'maxlength' => RegistrationFilter::EMAIL_MAX_LENGTH,
                'type' => 'text',
                'id' => 'settings-email'
            )
        ));

        //Confirm Email
        $this->add(array(
            'name' => 'confirm_email',
            'options' => array(
                'label' => 'Confirm Email',
            ),
            'attributes' => array(
                'class' => 'required email',
                'maxlength' => RegistrationFilter::EMAIL_MAX_LENGTH,
                'type' => 'text'
            )
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