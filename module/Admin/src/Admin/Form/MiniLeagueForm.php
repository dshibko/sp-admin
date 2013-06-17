<?php

namespace Admin\Form;

use \Neoco\Form\RegionalisedForm;
use \Neoco\Form\RegionFieldset;
use \Zend\InputFilter\InputFilter;
use \Admin\Form\Filter\LoginInputFilter;

class MiniLeagueForm extends RegionalisedForm {

    public function __construct($regionFieldsets) {

        parent::__construct($regionFieldsets, 'league');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setInputFilter(new \Admin\Form\Filter\LeagueInputFilter());

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
            'name' => 'season',
            'type'  => 'select',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Season',
                'disable_inarray_validator' => true,
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
            'name' => 'regions',
            'type'  => 'hidden',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Regions',
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
     * @param \Application\Model\Entities\League $league
     */
    protected function initFormByObject($league) {
        $this->setElementValue('displayName', $league->getDisplayName());
        $this->setElementValue('dates', $league->getStartDate()->format('d/m/Y') . ' - ' . $league->getEndDate()->format('d/m/Y'));
        $regionIds = array();
        foreach ($league->getLeagueRegions() as $leagueRegion)
            $regionIds[] = $leagueRegion->getRegion()->getId();
        $this->setElementValue('regions', implode(",", $regionIds));
    }

}