<?php

namespace Admin\Form;

use Neoco\Form\LanguageFieldset;

class PostMatchReportFieldset extends LanguageFieldset
{
    const MAX_TITLE_LENGTH = 255;

    public function __construct($language, $required = 0)
    {

        parent::__construct($language);

        //Title
        $this->add(array(
            'name' => 'post_match_report_title',
            'type' => 'text',
            'attributes' => array(
                'required' => $required,
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
            'attributes' => array(
                'required' => $required,
            ),
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
        $language = $this->getLanguage();
        foreach ($match->getMatchLanguages() as $matchLanguage) {
            if ($matchLanguage->getLanguage()->getId() == $language['id']) {
                $this->get('post_match_report_title')->setValue($matchLanguage->getPostMatchReportTitle());
                $this->get('post_match_report_intro')->setValue($matchLanguage->getPostMatchReportIntro());
                $this->get('post_match_report_header_image')->setValue($matchLanguage->getPostMatchReportHeaderImagePath());
            }
        }
    }
}