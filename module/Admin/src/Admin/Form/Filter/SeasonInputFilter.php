<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class SeasonInputFilter extends InputFilter
{
    function __construct()
    {
        $factory = new InputFactory();

        $this->add($factory->createInput(array(
            'name'     => 'displayName',
            'required' => true,
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
                array('name' => 'digits')
            )
        )));

    }
}