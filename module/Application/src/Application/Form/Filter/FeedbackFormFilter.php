<?php

namespace Application\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class FeedbackFormFilter extends InputFilter
{
    const NAME_MAX_LENGTH = 30;
    const QUERY_MAX_LENGTH = 250;

    function __construct()
    {
        $factory = new InputFactory();

        //Email
        $this->add($factory->createInput(array(
            'name'     => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(),
                ),
            )
        )));

        //Name
        $this->add($factory->createInput(array(
            'name'     => 'name',
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
                        'max' => self::NAME_MAX_LENGTH,
                    ),
                ),
            ),
        )));

        //Query
        $this->add($factory->createInput(array(
            'name'     => 'query',
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
                        'max' => self::QUERY_MAX_LENGTH,
                    ),
                ),
            ),
        )));
    }
}