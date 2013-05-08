<?php

namespace Admin\Form;

use Zend\Form\Form;
use \Admin\Form\Filter\LoginInputFilter;

class LoginForm extends Form {

    public function __construct($name = null) {
        parent::__construct('auth');

        $this->setInputFilter(new LoginInputFilter());
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-vertical login-form');

        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
            'attributes' => array(
                'placeholder' => 'Email',
                'icon' => 'icon-envelope'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type'  => 'password',
            'attributes' => array(
                'placeholder' => 'Password',
                'icon' => 'icon-lock'
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'rememberme',
            'options' => array(
                'label' => 'Remember Me',
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Login',
                'id' => 'submitbutton',
            ),
        ));
    }
}