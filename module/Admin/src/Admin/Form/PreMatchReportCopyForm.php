<?php

namespace Admin\Form;

use Zend\Form\Form;

class PreMatchReportCopyForm extends Form {

    public function __construct($name = null) {
        parent::__construct('settings');

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-vertical');
        $this->setAttribute('novalidate', true);

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }

    public function addSocialField($shareCopy, $label) {
        $this->add(array(
            'name' => 'share-copy-' . $shareCopy['id'],
            'type'  => 'textarea',
            'options' => array(
                'label' => $label,
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'span4',
                'value' => $shareCopy['copy'],
            ),
        ));
    }

}