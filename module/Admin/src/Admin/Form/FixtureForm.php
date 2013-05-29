<?php

namespace Admin\Form;

use \Neoco\Form\RegionalisedForm;
use \Neoco\Form\RegionFieldset;
use \Zend\InputFilter\InputFilter;
use \Admin\Form\Filter\FixtureFilter;

class FixtureForm extends RegionalisedForm {

    protected $teams = array();

    public function __construct($regionFieldsets, array $teams) {

        parent::__construct($regionFieldsets, 'fixture');
        //TODO beautiful selects
        $this->setAttribute('method', 'post');
        $this->setTeams($teams)->setInputFilter(new FixtureFilter());

        //Home Team
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'check-values',
                'tabindex' => 6,
                'data-changed' => 0,
                'data-original_value'=> ''
            ),
            'name' => 'homeTeam',
            'options' => array(
                'label' => 'Home Team',
                'empty_option' => '- Select Team -',
                'value_options' => $this->getTeams()

            ),
        ));

        //Away Team
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'check-values',
                'tabindex' => 6,
                'data-changed' => 0,
                'data-original_value'=> ''
            ),
            'name' => 'awayTeam',
            'options' => array(
                'label' => 'Away Team',
                'empty_option' => '- Select Team -',
                'value_options' => $this->getTeams()

            ),
        ));

        //Date
        $this->add(array(
            'name' => 'date',
            'type'  => 'text',
            'attributes' => array(
                'required' => true,
                'class' => 'my-picker'
            ),
            'options' => array(
                'label' => 'Date',
            ),
        ));

        //Kick-off time
        $this->add(array(
            'name' => 'kick_off_time',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'kick-off-time'
            ),
            'options' => array(
                'label' => 'Kick-off Time',
            ),
        ));

        //Double Point Match
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'isDoublePoints',
            'options' => array(
                'label' => 'Double Point Match',
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
            'attributes' => array()
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

    /**
     * @param \Application\Model\Entities\match $match
     */
    protected function initFormByObject($match) {

    }

    /**
     * @param $teams
     * @return \Admin\Form\FixtureForm
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeams()
    {
        return $this->teams;
    }
}