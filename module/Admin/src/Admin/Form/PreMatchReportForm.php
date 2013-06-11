<?php

namespace Admin\Form;

use \Neoco\Form\RegionalisedForm;


class PreMatchReportForm extends RegionalisedForm {

    protected $type;


    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function __construct($regionFieldsets, $type = 'match_report') {

        parent::__construct($regionFieldsets, 'match-report');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setType($type);

        $this->add(array(
            'name' => 'type',
            'type'  => 'hidden',
            'attributes' => array(
                'value' => $this->getType()
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

    /**
     * @param \Application\Model\Entities\Match $match
     */
    protected function initFormByObject($match) {

    }
}