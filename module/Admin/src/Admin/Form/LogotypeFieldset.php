<?php

namespace Admin\Form;

use Neoco\Form\LanguageFieldset;

class LogotypeFieldset extends LanguageFieldset
{
    public function __construct(array $language, $required = false)
    {
        parent::__construct($language);

        //Logotype
        $this->add(array(
            'name' => 'logotype',
            'type' => 'file',
            'attributes' => array(
                'isImage' => true,
                'required' => $required,
            ),
            'options' => array(
                'label' => 'Logotype',
            ),
        ));
    }
    public function initFieldsetByObject($logotypes){
        $data = $this->getLanguage();
        foreach ($logotypes as $logotype) {
            if ($logotype->getLanguage()->getId() == $data['id']){
                $this->get('logotype')->setValue($logotype->getLogotype());
            }
        }
    }
}