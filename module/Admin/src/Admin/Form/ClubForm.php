<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;
use \Zend\InputFilter\InputFilter;
use \Admin\Form\Filter\ClubFormFilter;

class ClubForm extends UploadableForm {

    public function __construct() {

        parent::__construct('club');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        //Club Name
        $this->add(array(
            'name' => 'displayName',
            'type'  => 'text',
            'attributes' => array(
                'required' => true,
                'maxlength' => 50,
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));
        //Logo
        $this->add(array(
            'name' => 'logoPath',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'minWidth' => 110,
                'minHeight' => 110,
                'hint' => 'Min image width: 110px, height: 110px',
            ),
            'options' => array(
                'label' => 'Club Logo',
            ),
        ));

        $this->add(array(
            'name' => 'shortName',
            'type'  => 'text',
            'attributes' => array(
                'maxlength' => 10
            ),
            'options' => array(
                'label' => 'Short Name',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Update',
                'id' => 'submitbutton',
            ),
        ));
    }
}