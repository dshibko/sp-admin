<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class SeasonInputFilter extends InputFilter
{
    const DISPLAY_NAME_MAX_LENGTH = 100;
    const FEEDER_ID_MIN_VALUE = 0;

    function __construct()
    {
        $factory = new InputFactory();

        $this->add($factory->createInput(array(
            'name'     => 'displayName',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::DISPLAY_NAME_MAX_LENGTH
                    )
                ),
            ),
        )));

        $this->add($factory->createInput(array(
            'name'     => 'dates',
            'required'   => true,
            'validators' => array(
                array('name' => 'regex', 'options' => array(
                    'pattern'   => '/\d{2}\/\d{2}\/\d{4}\s\-\s\d{2}\/\d{2}\/\d{4}/',
                    'messages' => array(\Zend\Validator\Regex::NOT_MATCH => \Application\Model\Helpers\MessagesConstants::ERROR_WRONG_DATES_SELECTED)
                )),
            ),
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