<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class SettingsLanguageFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();
        //Language
        $this->add($factory->createInput(array(
            'name'     => 'language',
            'required' => true,
        )));
    }
}