<?php

namespace Admin\Form\Filter;

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
        )));

        $this->add($factory->createInput(array(
            'name'     => 'password',
            'required' => true,
        )));

        $this->add($factory->createInput(array(
            'name'     => 'rememberme',
            'required' => false,
        )));
    }
}