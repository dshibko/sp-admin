<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;

class FooterImageForm extends UploadableForm {

    public function __construct($name = null) {
        parent::__construct('footer-image');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-vertical');

        $this->add(array(
            'name' => 'footerImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'minWidth' => 180,
                'minHeight' => 205,
                'hint' => 'Min image width: 180px, height: 205px',
            ),
            'options' => array(
                'label' => 'New Footer Image',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Upload',
                'id' => 'submitbutton',
            ),
        ));
    }


}