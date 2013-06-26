<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class FixtureFilter extends InputFilter
{
    const FEEDER_ID_MIN_VALUE = 0;
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
            'name'     => 'competition',
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
        $this->add($factory->createInput(array(
            'name'     => 'kick_off_time',
            'required' => true,
            'validators' => array(
                array('name' => 'regex', 'options' => array(
                    'pattern'   => '/^\d{2}:\d{2} AM|PM$/',
                    'messages' => array(\Zend\Validator\Regex::NOT_MATCH => \Application\Model\Helpers\MessagesConstants::ERROR_INCORRECT_TIME)
                )),
            ),
        )));
        $this->add($factory->createInput(array(
            'name'     => 'isDoublePoints',
            'required' => false,
        )));
        $this->add($factory->createInput(array(
            'name'     => 'feederId',
            'required'   => true,
            'validators' => array(
                array('name' => 'digits'),
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => self::FEEDER_ID_MIN_VALUE
                    )
                ),
            )
        )));
    }
}