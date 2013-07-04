<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;
use \Admin\Form\Filter\LoginInputFilter;

class OptaForm extends UploadableForm {

    public function __construct($name = null) {
        parent::__construct('opta-upload');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-vertical');

        $this->add(array(
            'name' => 'feed',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'ext' => 'xml',
            ),
            'options' => array(
                'label' => 'Feed File',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Send',
                'id' => 'submitbutton',
            ),
        ));
    }

}