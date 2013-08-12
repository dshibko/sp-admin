<?php

namespace Admin\View\Helpers;

use Neoco\Form\RegionLanguageFieldset;

class RegionLanguageFieldsetsRenderer extends RegionFieldsetsRenderer
{

    private $languageFieldsetRenderer;

    protected function renderRegionFieldset(RegionLanguageFieldset $regionLanguageFieldset, $add = true) {
        if ($this->languageFieldsetRenderer === null) {
            $this->languageFieldsetRenderer = new LanguageFieldsetsRenderer();
            $this->languageFieldsetRenderer->setTranslator($this->getTranslator());
        }
        return $this->languageFieldsetRenderer->__invoke($regionLanguageFieldset->getLanguageFieldsets(), $add, $regionLanguageFieldset->getName(), false);
    }

}