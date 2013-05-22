<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Application\Form\Filter\RegistrationFilter;

class SettingsDisplayNameFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();

        //Display Name
        $this->add($factory->createInput(array(
            'name' => 'display_name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => RegistrationFilter::INPUT_MIN_LENGTH,
                        'max' => RegistrationFilter::DISPLAY_NAME_MAX_LENGTH,
                    ),
                ),
                array(
                    'name' => 'Application\Form\Validator\BadWordValidator',
                    'options' => array()
                )
            ),
        )));
    }
}