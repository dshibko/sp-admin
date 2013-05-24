<?php

namespace Application\Form;

use Zend\Form\Form;
use \Application\Form\Filter\ForgotPasswordFilter;

class ForgotPasswordForm extends Form {

    public function __construct($name = null) {
        parent::__construct('forgot');

        $this->setInputFilter(new ForgotPasswordFilter());
        $this->setAttribute('method', 'post');


        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
            'attributes' => array(),
            'options' => array(
                'label' => 'Email',
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