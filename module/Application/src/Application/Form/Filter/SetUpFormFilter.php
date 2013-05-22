<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class SetUpFormFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();
        $this->add($factory->createInput(array(
            'name'     => 'language',
            'required' => true,
        )));

        $this->add($factory->createInput(array(
            'name'     => 'region',
            'required' => true,
        )));
    }
}