<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class SettingsFormFilter extends InputFilter
{


    function __construct()
    {
        $factory = new InputFactory();

        //Help And Support Email
        $this->add($factory->createInput(array(
            'name' => 'help-and-support-email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(),
                ),                            ),
        )));
    }
}