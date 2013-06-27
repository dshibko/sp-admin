<?php

namespace Admin\Form;

use \Neoco\Form\RegionalisedForm;
use \Admin\Form\Filter\FixtureFilter;

class FixtureForm extends RegionalisedForm {

    protected $teams = array();
    protected $type;

    /**
     * @param mixed $type
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

    public function __construct(array $teams, $type = 'fixture_form') {

        parent::__construct(array(), 'fixture');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setTeams($teams)->setInputFilter(new FixtureFilter());
        $this->setType($type);
        //Competition

        $this->add(array(
            'name' => 'competition',
            'type'  => 'hidden',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Competition',
            ),
        ));
        //Home Team
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'check-values chosen',
                'tabindex' => 6,
                'style' => 'width:247px',
                'data-original_value'=> '',
                'data-placeholder' => 'Choose Home Team'
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
                'class' => 'check-values chosen',
                'tabindex' => 6,
                'style' => 'width:247px',
                'data-original_value'=> '',
                'data-placeholder' => 'Choose Away Team'
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
                'class' => 'kick-off-time',
                 'hint' => 'UTC time'
            ),
            'options' => array(
                'label' => 'Kick-off Time',
            ),
        ));

        //Feeder Id
        $this->add(array(
            'name' => 'feederId',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'check-values',
                'data-original_value'=> '',

            ),
            'options' => array(
                'label' => 'Feeder Id',
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
            'attributes' => array(
                'class' => 'double-points-checkbox'
            )
        ));

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
                'value' => 'Update',
                'id' => 'submitbutton',
            ),
        ));


    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    protected function initFormByObject($match) {
        $awayTeamId = $match->getAwayTeam()->getId();
        $homeTeamId = $match->getHomeTeam()->getId();
        $this->populateValues(array(
            'awayTeam' => $awayTeamId,
            'homeTeam' => $homeTeamId,
            'date' => $match->getStartTime()->format('m/d/Y'),
            'kick_off_time' => $match->getStartTime()->format('h:i A'),
            'isDoublePoints' => $match->getIsDoublePoints(),
            'feederId' => $match->getFeederId()
        ));
        $this->get('awayTeam')->setAttribute('data-original_value', $awayTeamId);
        $this->get('homeTeam')->setAttribute('data-original_value', $homeTeamId);
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