<?php

namespace Admin\Form\Filter;

use Application\Model\Helpers\MessagesConstants;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class SettingsFormFilter extends InputFilter
{
    const MAX_VALUE_LENGTH = 500;
    const CSS_HEX_PATTERN = '/^#[0-9A-Fa-f]{3,6}$/';
    const AHEAD_PREDICTIONS_DAYS_MIN_VALUE = 0;
    const ABSOLUTE_URL_PATTERN = '/^(http(?:s)?\:\/\/[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/';
    function __construct()
    {
        $factory = new InputFactory();

        //Site Background Color
        $this->add($factory->createInput(array(
            'name' => 'site-background-colour',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::MAX_VALUE_LENGTH
                    )
                ),
                array('name' => 'regex',
                    'options' => array(
                        'pattern'   => self::CSS_HEX_PATTERN,
                        'messages' => array(\Zend\Validator\Regex::NOT_MATCH => MessagesConstants::ERROR_INCORRECT_CSS_COLOR)
                    )
                ),
            ),
        )));

        //Site Footer Colour
        $this->add($factory->createInput(array(
            'name' => 'site-footer-colour',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::MAX_VALUE_LENGTH
                    )
                ),
                array('name' => 'regex',
                    'options' => array(
                        'pattern'   => self::CSS_HEX_PATTERN,
                        'messages' => array(\Zend\Validator\Regex::NOT_MATCH => MessagesConstants::ERROR_INCORRECT_CSS_COLOR)
                    )
                ),
            ),
        )));

        //Language
        $this->add($factory->createInput(array(
            'name' => 'language',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::MAX_VALUE_LENGTH
                    )
                ),
            )
        )));

        //Region
        $this->add($factory->createInput(array(
            'name' => 'region',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::MAX_VALUE_LENGTH
                    )
                ),
            )
        )));

        //Bad Words
        $this->add($factory->createInput(array(
            'name' => 'bad-words',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::MAX_VALUE_LENGTH
                    )
                ),
            )
        )));

        //Ahead Predictions Days
        $this->add($factory->createInput(array(
            'name' => 'ahead-predictions-days',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::MAX_VALUE_LENGTH
                    )
                ),
                array('name' => 'digits'),
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => self::AHEAD_PREDICTIONS_DAYS_MIN_VALUE
                    )
                ),
            )
        )));

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
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::MAX_VALUE_LENGTH
                    )
                ),
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(),
                ),
            ),
        )));
        //GA Account Id
        $this->add($factory->createInput(array(
            'name' => 'ga-account-id',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::MAX_VALUE_LENGTH
                    )
                ),
            )
        )));
        //Main Site Link
        $this->add($factory->createInput(array(
            'name' => 'main-site-link',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => self::MAX_VALUE_LENGTH
                    )
                ),
                new \Neoco\Validator\AbsoluteUrlValidator()
            )
        )));
    }
}