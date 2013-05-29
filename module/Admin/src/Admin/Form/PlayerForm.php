<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;
use \Zend\InputFilter\InputFilter;

class PlayerForm extends UploadableForm {

    public function __construct() {

        parent::__construct('player');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        //Player Name
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
        //Squad Number
        $this->add(array(
            'name' => 'shirtNumber',
            'type'  => 'text',
            'attributes' => array(
                'required' => true,
                'maxlength' => 2,
                'between' =>array(
                    'min' => 1,
                    'max' => 99
                ),
                'digits' => true
            ),
            'options' => array(
                'label' => 'Squad Number',
            ),
        ));

        //Player Status
        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'disable_inarray_validator' => true,
                'label' => 'Status',
                'value_options' => array(
                    1 => 'Active',
                    0 => 'Inactive'
                ),
            ),
            'attributes' => array(
                'class' => 'required'
            )
        ));

        //Background Image
        $this->add(array(
            'name' => 'backgroundImagePath',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'minWidth' => 110,
                'minHeight' => 110,
                'hint' => 'Min image width: 110px, height: 110px',
            ),
            'options' => array(
                'label' => 'Player Background Image',
            ),
        ));

        //Avatar
        $this->add(array(
            'name' => 'imagePath',
            'type'  => 'file',
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'minWidth' => 110,
                'minHeight' => 110,
                'hint' => 'Min image width: 110px, height: 110px',
            ),
            'options' => array(
                'label' => 'Player Avatar',
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