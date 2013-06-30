<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;

class MatchReportContentFieldset extends RegionFieldset {

    const TITLE_MAX_LENGTH = 255;

    public function __construct($region) {

        parent::__construct($region);

        $this->add(array(
            'name' => 'title',
            'type'  => 'text',
            'attributes' => array(
                'required' => 'required',
                'maxlength' => self::TITLE_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));

        $this->add(array(
            'name' => 'intro',
            'type'  => 'textarea',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Intro',
            ),
        ));

        $this->add(array(
            'name' => 'headerImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => 'required',
                'isImage' => true,
            ),
            'options' => array(
                'label' => 'Header Image',
            ),
        ));

    }

    /**
     * @param array $regionContent
     */
    function initFieldsetByObject($regionContent) {
        $region = $this->getRegion();
        $reportContent = $regionContent[$region['id']];
        foreach ($this->getElements() as $element) {
            $getter = 'get' . ucfirst($element->getName());
            if (method_exists($reportContent, $getter)) {
                $elValue = $element->getValue();
                if ($element->getAttribute('isImage') || empty($elValue))
                    $element->setValue($reportContent->{$getter}());
            }
        }
    }

}