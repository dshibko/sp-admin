<?php

namespace Neoco\Form;

use Zend\Form\Fieldset;

abstract class RegionLanguageFieldset extends Fieldset implements FieldsetObjectInterface {

    private $region;
    private $languageFieldsets;

    public function __construct($region, $languageFieldsets) {

        $this->setRegion($region);
        $this->setLanguageFieldsets($languageFieldsets);

        parent::__construct(str_replace(" ", "_", $region['displayName']));

        foreach ($languageFieldsets as $languageFieldset)
            $this->add($languageFieldset);

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

    public function setLanguageFieldsets($languageFieldsets)
    {
        $this->languageFieldsets = $languageFieldsets;
    }

    public function getLanguageFieldsets()
    {
        return $this->languageFieldsets;
    }

}