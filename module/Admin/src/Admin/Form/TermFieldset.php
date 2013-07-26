<?php

namespace Admin\Form;

use Neoco\Form\LanguageFieldset;

class TermFieldset extends LanguageFieldset
{
    public function __construct(array $language, $required = false)
    {
        parent::__construct($language);

        //Page Content
        $this->add(array(
            'name' => 'copy',
            'type' => 'textarea',
            'attributes' => array(
                'required' => $required,
                'editor'=> array(
                    'type' => 'ckeditor'
                )
            ),
            'options' => array(
                'label' => 'Copy',
            ),
        ));
    }
    public function initFieldsetByObject($term){
        $data = $this->getLanguage();
        foreach ($term->getTermCopies() as $termCopy) {
            if ($termCopy->getLanguage()->getId() == $data['id']){
                $this->get('copy')->setValue($termCopy->getCopy());
            }
        }
    }
}