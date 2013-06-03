<?php

namespace Admin\Form;

use Zend\Form\Form;
use \Admin\Form\Filter\AccountFormFilter;

class AccountForm extends Form {
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

    public function __construct($type = 'personal_info') {
        parent::__construct('account');
        $this->setAttribute('method', 'post');
        $this->setType($type);
        //First name
        $this->add(array(
            'name' => 'firstName',
            'type'  => 'text',
            'attributes' => array(
                'maxlength'=> AccountFormFilter::NAME_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'First Name',
            ),
        ));

        //Last Name
        $this->add(array(
            'name' => 'lastName',
            'type'  => 'text',
            'attributes' => array(
                'maxlength'=> AccountFormFilter::NAME_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ));

        //Email
        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
            'attributes' => array(
                'maxlength'=> AccountFormFilter::EMAIL_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'Email',
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
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }
}