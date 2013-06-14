<?php

namespace Admin\Form;

use Neoco\Form\LanguageFieldset;

class FooterPageFieldset extends LanguageFieldset
{
    public function __construct(array $language)
    {
        parent::__construct($language);

        //Page Content
        $this->add(array(
            'name' => 'content',
            'type' => 'textarea',
            'attributes' => array(
                'required' => true
            ),
            'options' => array(
                'label' => 'Content',
            ),
        ));
    }
    public function initFieldsetByObject($pageData){
        $data = $this->getData();
        foreach ($pageData as $page) {
            if ($page->getLanguage()->getId() == $data['id']){
                $this->get('content')->setValue($page->getContent());
            }
        }
    }
}