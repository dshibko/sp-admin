<?php

namespace Admin\Form;

use Neoco\Form\RegionalisedForm;
use Zend\Form\Form;

class DefaultSkinForm extends RegionalisedForm {

    public function __construct($fieldsets) {
        parent::__construct($fieldsets);

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-vertical');

        $this->add(array(
            'name' => 'defaultSkinImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'minWidth' => 1280,
                'hint' => 'Min image width: 1280px',
            ),
            'options' => array(
                'label' => 'Background Image',
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

    protected function initFormByObject($data) {}
}