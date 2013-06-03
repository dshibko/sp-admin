<?php

namespace Admin\Form;

use Zend\Form\Form;
use \Admin\Form\Filter\AccountPasswordFormFilter;

class AccountPasswordForm extends Form {

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

    public function __construct($type = 'password') {
        parent::__construct('account-password');
        $this->setAttribute('method', 'post');
        $this->setType($type)->setInputFilter(new AccountPasswordFormFilter());
        //Old Password
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'maxlength' => AccountPasswordFormFilter::PASSWORD_MAX_LENGTH,
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
                'maxlength' => AccountPasswordFormFilter::PASSWORD_MAX_LENGTH,
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
                'maxlength' => AccountPasswordFormFilter::PASSWORD_MAX_LENGTH,
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
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }
}