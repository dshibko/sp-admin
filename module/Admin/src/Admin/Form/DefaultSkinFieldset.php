<?php

namespace Admin\Form;

use Neoco\Form\LanguageFieldset;

class DefaultSkinFieldset extends LanguageFieldset
{
    public function __construct(array $language)
    {
        parent::__construct($language);

        $this->add(array(
            'name' => 'ContentBackground',
            'type'  => 'text',
            'options' => array(
                'label' => 'Content Background Colour',
            ),
            'attributes' => array(
                'required' => $language['isDefault'],
                'class' => 'span2 colorpicker-default m-wrap',
            ),
        ));

        $this->add(array(
            'name' => 'FooterBackground',
            'type'  => 'text',
            'options' => array(
                'label' => 'Footer Background Colour',
            ),
            'attributes' => array(
                'required' => $language['isDefault'],
                'class' => 'span2 colorpicker-default m-wrap',
            ),
        ));
    }

    public function initFieldsetByObject($defaultSkins){
        $data = $this->getLanguage();
        foreach ($defaultSkins as $skin) {
            if ($skin->getLanguage()->getId() == $data['id']){
                $this->get($skin->getType())->setValue($skin->getColour());
            }
        }
    }
}