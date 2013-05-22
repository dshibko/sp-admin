<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Application\Form\Filter\RegistrationFilter;

class SettingsPasswordFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();

        //Old Password
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

        //New Password
        $this->add($factory->createInput(array(
            'name' => 'new_password',
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

        //Confirm New Password
        $this->add($factory->createInput(array(
            'name' => 'confirm_new_password',
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
                    'options' => array('token' => 'new_password'),
                ),
            ),
        )));
    }
}