<?php

namespace Admin\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Neoco\Validator\InputsArrayValidator;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class LanguageFormFilter extends InputFilter implements InputFilterAwareInterface
{
    protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            $inputFilter->add($factory->createInput(array(
                'name'     => 'countries',
                'required' => true,

            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'display_name',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max' => 40,
                        ),
                    ),
                )

            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'language_code',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max' => 5,
                        ),
                    ),
                )

            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'strings',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Neoco\Validator\InputsArrayValidator',
                        'options' => array(
                            'messages' => array(
                                InputsArrayValidator::EMPTY_VALUES => 'Empty values for translations'
                            )
                        )
                    ),
                )
            )));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}