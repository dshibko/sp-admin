<?php
namespace Application\Form;

use Zend\Form\Form;
use \Application\Form\Filter\ResetPasswordFilter;

class ResetPasswordForm extends Form {

    public function __construct($name = null) {
        parent::__construct('reset');

        $this->setInputFilter(new ResetPasswordFilter());
        $this->setAttribute('method', 'post');

        //Password
        $this->add(array(
            'name' => 'password',
            'type'  => 'text',
            'attributes' => array(
                'placeholder' => 'Password',
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        //Confirm Password
        $this->add(array(
            'name' => 'confirm_password',
            'type'  => 'text',
            'attributes' => array(
                'placeholder' => 'Confirm Password',
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ));
        //CSRF
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Submit',
                'id' => 'submitbutton',
            ),
        ));
    }
}