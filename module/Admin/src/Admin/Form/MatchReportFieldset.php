<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;

class MatchReportFieldset extends RegionFieldset
{
    const FIELD_GROUP_MATCH_REPORT = 'match_report';
    const MAX_TITLE_LENGTH = 255;

    public function __construct($region)
    {

        parent::__construct($region);

        //Title
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_TITLE_LENGTH,
                'fieldgroup' => array(
                    'type' => 'start',
                    'color' => 'yellow',
                    'title' => 'Match Report',
                    'name' => self::FIELD_GROUP_MATCH_REPORT
                )
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));

        //Intro
        $this->add(array(
            'name' => 'intro',
            'type' => 'textarea',
            'attributes' => array(
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_MATCH_REPORT
                )
            ),
            'options' => array(
                'label' => 'Intro',
            ),
        ));

        //Header Image
        $this->add(array(
            'name' => 'header_image',
            'type' => 'file',
            'attributes' => array(
                'isImage' => true,
                'fieldgroup' => array(
                    'type' => 'end',
                    'name' => self::FIELD_GROUP_MATCH_REPORT
                )
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
                $this->get('title')->setValue($matchRegion->getTitle());
                $this->get('intro')->setValue($matchRegion->getIntro());
                $this->get('header_image')->setValue($matchRegion->getHeaderImagePath());
            }
        }
    }
}