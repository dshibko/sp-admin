<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;
use \Admin\Form\Filter\LoginInputFilter;

class FooterSocialForm extends UploadableForm {

    public function __construct($name = null) {
        parent::__construct('footer-social');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-vertical');

        $this->add(array(
            'name' => 'url',
            'type'  => 'text',
            'options' => array(
                'label' => 'Url',
            ),
            'attributes' => array(
                'required' => true
            ),
        ));

        $this->add(array(
            'name' => 'copy',
            'type'  => 'text',
            'options' => array(
                'label' => 'Copy',
            ),
            'attributes' => array(
                'required' => true
            ),
        ));

        $this->add(array(
            'name' => 'icon',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'width' => 18,
                'height' => 18,
                'hint' => 'Image must be 18px square',
            ),
            'options' => array(
                'label' => 'Icon',
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