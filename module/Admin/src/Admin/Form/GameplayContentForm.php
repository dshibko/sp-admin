<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;
use \Admin\Form\Filter\LoginInputFilter;

class GameplayContentForm extends UploadableForm {

    public function __construct($name = null) {
        parent::__construct('gameplay');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-vertical');

        $this->add(array(
            'name' => 'foregroundImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'minWidth' => 600,
                'hint' => 'Min image width: 600px',
            ),
            'options' => array(
                'label' => 'Foreground Image',
            ),
        ));

        $this->add(array(
            'name' => 'heading',
            'type'  => 'text',
            'options' => array(
                'label' => 'Heading',
            ),
            'attributes' => array(
                'required' => true
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type'  => 'textarea',
            'options' => array(
                'label' => 'Description',
            ),
            'attributes' => array(
                'required' => true
            ),
        ));

        $this->add(array(
            'name' => 'order',
            'type'  => 'number',
            'options' => array(
                'label' => 'Order',
            ),
            'attributes' => array(
                'required' => true
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

}