<?php

namespace Admin\Form;

use \Neoco\Form\RegionalisedForm;
use \Neoco\Form\RegionFieldset;
use \Zend\InputFilter\InputFilter;
use \Admin\Form\Filter\LoginInputFilter;

class SeasonForm extends RegionalisedForm {

    public function __construct($regionFieldsets) {

        parent::__construct($regionFieldsets, 'season');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setInputFilter(new \Admin\Form\Filter\SeasonInputFilter());

        $this->add(array(
            'name' => 'displayName',
            'type'  => 'text',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Internal season name',
            ),
        ));

        $this->add(array(
            'name' => 'dates',
            'type'  => 'text',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Go live start and end',
            ),
        ));

        $this->add(array(
            'name' => 'feederId',
            'type'  => 'text',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Feeder season id',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Create',
                'id' => 'submitbutton',
            ),
        ));
    }

    /**
     * @param \Application\Model\Entities\Season $season
     */
    protected function initFormByObject($season) {
        $this->get('displayName')->setValue($season->getDisplayName());
        $this->get('dates')->setValue($season->getStartDate()->format('d/m/Y') . ' - ' . $season->getEndDate()->format('d/m/Y'));
        $this->get('feederId')->setValue($season->getFeederId());
    }

}