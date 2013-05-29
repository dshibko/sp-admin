<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class FixtureFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();
        $this->add($factory->createInput(array(
            'name'     => 'awayTeam',
            'required' => true,
        )));

        $this->add($factory->createInput(array(
            'name'     => 'homeTeam',
            'required' => true,
        )));

        $this->add($factory->createInput(array(
            'name'     => 'date',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Zend\Validator\Date',
                    'options' => array(
                        'format' => 'm/d/Y'
                    )
                )
            )
        )));
        //TODO Regex validation
        $this->add($factory->createInput(array(
            'name'     => 'kick_off_time',
            'required' => true,
        )));
        $this->add($factory->createInput(array(
            'name'     => 'isDoublePoints',
            'required' => false,
        )));
    }
}