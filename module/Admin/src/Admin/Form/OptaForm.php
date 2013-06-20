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
                'ext' => 'json',
            ),
            'options' => array(
                'label' => 'Feed File',
            ),
        ));

        //Featured Goalkeeper
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => true,
                'class' => 'chosen',
                'tabindex' => 6,
            ),
            'name' => 'type',
            'options' => array(
                'label' => 'Feed Type',
                'empty_option' => '---',
                'value_options' => array(
                    'F1' => 'F1',
                    'F7' => 'F7',
                    'F40' => 'F40',
                ),
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