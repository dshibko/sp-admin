<?php

namespace Neoco\Form;

abstract class RegionFieldset extends UploadableFieldset {

    private $region;

    public function __construct($region) {

        $this->region = $region;

        parent::__construct(str_replace(" ", "_", $region['displayName']));

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

    public function getElement($name) {
        return $this->get($name);
    }

    public function getRegion()
    {
        return $this->region;
    }

    abstract function initFieldsetByObject($dataObject);

}