<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;

class PostMatchReportFieldset extends RegionFieldset
{
    const MAX_TITLE_LENGTH = 255;

    public function __construct($region)
    {

        parent::__construct($region);

        //Title
        $this->add(array(
            'name' => 'post_match_report_title',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_TITLE_LENGTH,
            ),
            'options' => array(
                'label' => 'Post Match Report Title',
            ),
        ));

        //Intro
        $this->add(array(
            'name' => 'post_match_report_intro',
            'type' => 'textarea',
            'attributes' => array(),
            'options' => array(
                'label' => 'Post Match Report Intro',
            ),
        ));

        //Header Image
        $this->add(array(
            'name' => 'post_match_report_header_image',
            'type' => 'file',
            'attributes' => array(
                'isImage' => true,
            ),
            'options' => array(
                'label' => 'Post Match Report Header Image',
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
            if ($matchRegion->getLanguage()->getId() == $region['id']) {
                $this->get('post_match_report_title')->setValue($matchRegion->getPostMatchReportTitle());
                $this->get('post_match_report_intro')->setValue($matchRegion->getPostMatchReportIntro());
                $this->get('post_match_report_header_image')->setValue($matchRegion->getPostMatchReportHeaderImagePath());
            }
        }
    }
}