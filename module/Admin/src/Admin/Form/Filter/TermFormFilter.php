<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class TermFormFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();

        $this->add($factory->createInput(array(
            'name'     => 'required',
            'required' => false,
        )));
        $this->add($factory->createInput(array(
            'name'     => 'checked',
            'required' => false,
        )));
    }
}