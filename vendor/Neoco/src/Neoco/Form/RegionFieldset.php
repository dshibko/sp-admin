<?php

namespace Neoco\Form;

abstract class RegionFieldset extends UploadableFieldset implements  FieldsetObjectInterface {

    private $region;

    public function __construct($region) {

        $this->setRegion($region);

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

    /**
     * @param int|null|string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }


    public function getRegion()
    {
        return $this->region;
    }

}