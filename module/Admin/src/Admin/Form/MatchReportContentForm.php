<?php

namespace Admin\Form;

use \Neoco\Form\RegionalisedForm;

class MatchReportContentForm extends RegionalisedForm {

    public function __construct($regionFieldsets) {

        parent::__construct($regionFieldsets, 'matchReportContent');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }

    /**
     * @param array $regionContent
     */
    protected function initFormByObject($regionContent) {
    }

}