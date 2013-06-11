<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;

class PreMatchReportFieldset extends RegionFieldset
{
    const MAX_TITLE_LENGTH = 255;

    public function __construct($region)
    {

        parent::__construct($region);

        //Title
        $this->add(array(
            'name' => 'pre_match_report_title',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_TITLE_LENGTH,
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));

        //Intro
        $this->add(array(
            'name' => 'pre_match_report_intro',
            'type' => 'textarea',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Intro',
            ),
        ));

        //Header Image
        $this->add(array(
            'name' => 'pre_match_report_header_image',
            'type' => 'file',
            'attributes' => array(
                'isImage' => true,
            ),
            'options' => array(
                'label' => 'Header Image',
            ),
        ));


    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    function initFieldsetByObject($match)
    {
        $region = $this->getRegion();
        foreach ($match->getMatchRegions() as $matchRegion) {
            if ($matchRegion->getRegion()->getId() == $region['id']) {
                $this->get('pre_match_report_title')->setValue($matchRegion->getPreMatchReportTitle());
                $this->get('pre_match_report_intro')->setValue($matchRegion->getPreMatchReportIntro());
                $this->get('pre_match_report_header_image')->setValue($matchRegion->getPreMatchReportHeaderImagePath());
            }
        }
    }
}