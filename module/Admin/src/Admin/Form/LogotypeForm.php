<?php

namespace Admin\Form;

use Neoco\Form\RegionalisedForm;

class LogotypeForm extends RegionalisedForm
{

    public function __construct($fieldsets) {

        parent::__construct($fieldsets);
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        //Emblem
        $this->add(array(
            'name' => 'emblem',
            'type' => 'file',
            'attributes' => array(
                'isImage' => true,
                'required' => true,
            ),
            'options' => array(
                'label' => 'Emblem'
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }

    protected function initFormByObject($logotypes){
        if (!empty($logotypes)){
            $this->get('emblem')->setValue($logotypes[0]->getEmblem()->getPath());
        }
    }
}