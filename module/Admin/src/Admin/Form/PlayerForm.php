<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;


class PlayerForm extends UploadableForm {

    const DISPLAY_NAME_MAX_LENGTH = 50;
    const SQUAD_NUMBER_MAX_LENGTH = 2;
    const SQUAD_NUMBER_MIN_VALUE = 1;
    const SQUAD_NUMBER_MAX_VALUE = 99;
//    const AVATAR_WIDTH = 110;
//    const AVATAR_HEIGHT = 110;
    const BACKGROUND_WIDTH = 570;
    const BACKGROUND_HEIGHT = 290;

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
                'maxlength' => self::DISPLAY_NAME_MAX_LENGTH,
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
                'maxlength' => self::SQUAD_NUMBER_MAX_LENGTH,
                'between' =>array(
                    'min' => self::SQUAD_NUMBER_MIN_VALUE,
                    'max' => self::SQUAD_NUMBER_MAX_VALUE
                ),
                'digits' => true
            ),
            'options' => array(
                'label' => 'Squad Number',
            ),
        ));

        //Background Image
        $this->add(array(
            'name' => 'backgroundImagePath',
            'type'  => 'file',
            'attributes' => array(
                'isImage' => true,
                'minWidth' => self::BACKGROUND_WIDTH,
                'minHeight' => self::BACKGROUND_HEIGHT,
                'hint' => 'Min image width: '.self::BACKGROUND_WIDTH.'px, height: '.self::BACKGROUND_HEIGHT.'px',
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
                'isImage' => true,
//                'minWidth' => self::AVATAR_WIDTH,
//                'minHeight' => self::AVATAR_HEIGHT,
//                'hint' => 'Min image width: '.self::AVATAR_WIDTH.'px, height: '.self::AVATAR_HEIGHT.'px',
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