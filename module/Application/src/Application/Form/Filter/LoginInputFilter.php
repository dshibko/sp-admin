<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class LoginInputFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();
        $this->add($factory->createInput(array(
            'name'     => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Email cannot be empty',
                        ),
                    ),
                ),
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(),
                ),
            )
        )));

        $this->add($factory->createInput(array(
            'name'     => 'password',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Password cannot be empty',
                        ),
                    ),
                ),
            )
        )));

        $this->add($factory->createInput(array(
            'name'     => 'rememberme',
            'required' => false,
        )));
    }
}