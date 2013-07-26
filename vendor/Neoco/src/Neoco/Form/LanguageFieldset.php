<?php

namespace Neoco\Form;

use \Neoco\Form\FieldsetObjectInterface;

abstract class LanguageFieldset extends UploadableFieldset implements  FieldsetObjectInterface{

    protected $language;

    /**
     * @param mixed $data
     * @return $this
     */
    public function setLanguage($data)
    {
        $this->language = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    public function __construct($language) {

        $this->setLanguage($language);
        parent::__construct(str_replace(" ", "_", $language['displayName']));

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
        return parent::getInputFilterSpecification($inputSpec);
    }

}