<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;
use \Admin\Form\Filter\LoginInputFilter;

class LandingContentForm extends UploadableForm {

    public function __construct($name = null) {
        parent::__construct('landing');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-vertical');

        $this->add(array(
            'name' => 'heroBackgroundImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'minWidth' => 1280,
                'hint' => 'Min image width: 1280px',
            ),
            'options' => array(
                'label' => 'Hero Background',
            ),
        ));

        $this->add(array(
            'name' => 'heroForegroundImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'minWidth' => 600,
                'hint' => 'Min image width: 600px',
            ),
            'options' => array(
                'label' => 'Hero Foreground',
            ),
        ));

        $this->add(array(
            'name' => 'headlineCopy',
            'type'  => 'text',
            'options' => array(
                'label' => 'Headline Copy',
            ),
            'attributes' => array(
                'required' => true
            ),
        ));

        $this->add(array(
            'name' => 'registerButtonCopy',
            'type'  => 'text',
            'options' => array(
                'label' => 'Register Button Copy',
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