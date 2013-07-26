<?php

namespace Admin\Form;

use Neoco\Form\LanguageFieldset;

class PreMatchReportFieldset extends LanguageFieldset
{
    const MAX_TITLE_LENGTH = 255;

    public function __construct($language, $required = 0)
    {

        parent::__construct($language);

        //Title
        $this->add(array(
            'name' => 'pre_match_report_title',
            'type' => 'text',
            'attributes' => array(
                'required' => $required,
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
                'required' => $required,
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
                'required' => $required,
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
        $language = $this->getLanguage();
        foreach ($match->getMatchLanguages() as $matchLanguage) {
            if ($matchLanguage->getLanguage()->getId() == $language['id']) {
                $this->get('pre_match_report_title')->setValue($matchLanguage->getPreMatchReportTitle());
                $this->get('pre_match_report_intro')->setValue($matchLanguage->getPreMatchReportIntro());
                $this->get('pre_match_report_header_image')->setValue($matchLanguage->getPreMatchReportHeaderImagePath());
            }
        }
    }
}