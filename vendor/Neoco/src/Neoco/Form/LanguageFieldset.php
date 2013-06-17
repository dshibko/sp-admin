<?php

namespace Neoco\Form;
use \Zend\Form\Fieldset;
use \Zend\InputFilter\InputFilterProviderInterface;
use \Neoco\Form\FieldsetObjectInterface;

abstract class LanguageFieldset extends Fieldset implements InputFilterProviderInterface, FieldsetObjectInterface{

    protected $data;

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function __construct(array $data) {

        $this->setData($data);
        parent::__construct(str_replace(" ", "_", $data['displayName']));

    }
    /**
     * @param array $inputSpec
     * @return array
     */
    public function getInputFilterSpecification($inputSpec = array())
    {
        foreach ($this->getElements() as $element) {
            $required = $element->getAttribute('required');
            $validators = array('required' => $required);
            $inputSpec[$element->getName()] = $validators;
        }
        return $inputSpec;
    }

    public function initFieldsetByObject($dataObject){}

}