<?php

namespace Admin\Form;

use Neoco\Form\LanguageFieldset;

class MatchReportContentFieldset extends LanguageFieldset
{

    const TITLE_MAX_LENGTH = 255;

    public function __construct($language, $required = 0) {

        parent::__construct($language);

        $this->add(array(
            'name' => 'title',
            'type'  => 'text',
            'attributes' => array(
                'required' => $required,
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
                'required' => $required
            ),
            'options' => array(
                'label' => 'Intro',
            ),
        ));

        $this->add(array(
            'name' => 'headerImage',
            'type'  => 'file',
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
     * @param array $languageContent
     */
    function initFieldsetByObject($languageContent) {
        $language = $this->getLanguage();
        $reportContent = $languageContent[$language['id']];
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