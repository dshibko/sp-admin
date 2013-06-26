<?php

namespace Admin\Form;


use Admin\Form\Filter\SeasonInputFilter;
use \Neoco\Form\UploadableForm;
use Zend\Form\Form;

class ClubForm extends UploadableForm {

    const LOGO_HEIGHT = 110;
    const LOGO_WIDTH = 110;
    const DISPLAY_NAME_MAX_LENGTH = 50;
    const SHORT_NAME_MAX_LENGTH = 10;

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
                'maxlength' => self::DISPLAY_NAME_MAX_LENGTH,
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
                'minWidth' => self::LOGO_WIDTH,
                'minHeight' => self::LOGO_HEIGHT,
                'hint' => 'Min image width: '.self::LOGO_WIDTH.'px, height: '.self::LOGO_HEIGHT.'px',
            ),
            'options' => array(
                'label' => 'Club Logo',
            ),
        ));

        $this->add(array(
            'name' => 'shortName',
            'type'  => 'text',
            'attributes' => array(
                'maxlength' => self::SHORT_NAME_MAX_LENGTH
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