<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;

class HowToPlayContentForm extends UploadableForm {

    const HEADING_MAX_LENGTH = 255;

    public function __construct($name = 'how-to-play', $required = false) {
        parent::__construct($name);

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-vertical');

        $this->add(array(
            'name' => 'foregroundImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => $required,
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
                'required' => $required,
                'maxlength' => self::HEADING_MAX_LENGTH
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'type'  => 'textarea',
            'options' => array(
                'label' => 'Description',
            ),
            'attributes' => array(
                'required' => $required
            ),
        ));
        $this->add(array(
            'name' => 'order',
            'type'  => 'number',
            'options' => array(
                'label' => 'Order',
            ),
            'attributes' => array(
                'required' => $required,
                'digits' => true,
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