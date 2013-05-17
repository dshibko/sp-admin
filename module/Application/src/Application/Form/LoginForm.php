<?php

namespace Application\Form;

use Zend\Form\Form;
use \Application\Form\Filter\LoginInputFilter;

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