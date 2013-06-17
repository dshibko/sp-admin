<?php

namespace Admin\Form;

use \Neoco\Form\UploadableForm;
use Zend\Form\Form;

class PostMatchReportCopyForm extends UploadableForm {

    public function __construct($name = null) {
        parent::__construct('settings');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('class', 'form-vertical');
        $this->setAttribute('novalidate', true);

        $this->add(array(
            'name' => 'id',
            'type'  => 'hidden',
            'options' => array(
            ),
            'attributes' => array(
            ),
        ));

        $this->add(array(
            'name' => 'type',
            'type'  => 'hidden',
            'options' => array(
            ),
            'attributes' => array(
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'type'  => 'text',
            'options' => array(
                'label' => 'Title',
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type'  => 'textarea',
            'options' => array(
                'label' => 'Description',
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4',
            ),
        ));

        $this->add(array(
            'name' => 'iconPath',
            'type'  => 'file',
            'options' => array(
                'label' => 'Icon',
            ),
            'attributes' => array(
                'required' => true,
                'isImage' => true,
                'minWidth' => 199,
                'class' => 'span4',
            ),
        ));

        $this->add(array(
            'name' => 'facebook',
            'type'  => 'textarea',
            'options' => array(
                'label' => 'Facebook Share Copy',
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4',
            ),
        ));

        $this->add(array(
            'name' => 'twitter',
            'type'  => 'textarea',
            'options' => array(
                'label' => 'Twitter Share Copy',
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4',
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