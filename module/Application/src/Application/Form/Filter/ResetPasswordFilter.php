<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Application\Form\Filter\RegistrationFilter;

class ResetPasswordFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();

        //Password
        $this->add($factory->createInput(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => RegistrationFilter::PASSWORD_MIN_LENGTH,
                        'max' => RegistrationFilter::PASSWORD_MAX_LENGTH,
                    ),
                ),
            ),
        )));

        //Confirm Password
        $this->add($factory->createInput(array(
            'name' => 'confirm_password',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => RegistrationFilter::PASSWORD_MIN_LENGTH,
                        'max' => RegistrationFilter::PASSWORD_MAX_LENGTH,
                    ),
                ),
                array(
                    'name' => 'identical',
                    'options' => array('token' => 'password'),
                ),
            ),
        )));
    }
}