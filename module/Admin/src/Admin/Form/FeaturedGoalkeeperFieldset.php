<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;

class FeaturedGoalkeeperFieldset extends RegionFieldset
{
    const MAX_MATCH_STATS_VALUE = 999;
    const MIN_MATCH_STATS_VALUE = 0;
    const MAX_SEASON_PLAYED_MINUTES = 999999;
    const MAX_SEASON_SAVES = 999999;
    const MAX_SEASON_PENALTIES = 999999;

    const MAX_INPUT_LENGTH = 3;
    const MAX_SEASON_PLAYED_MINUTES_INPUT_LENGTH = 6;
    const MAX_SEASON_SAVES_INPUT_LENGTH = 6;
    const MAX_SEASON_PENALTIES_INPUT_LENGTH = 6;


    public function __construct($region)
    {

        parent::__construct($region);

        //Featured Goalkeeper
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                //'required' => 'required',
                'class' => 'chosen',
                'tabindex' => 6,
                'style' => 'width:247px',
                'data-placeholder' => 'Choose Featured Goalkeeper',
            ),
            'name' => 'featured_goalkeeper',
            'options' => array(
                'label' => 'Featured Goalkeeper',
                'empty_option' => '- Select Featured Goalkeeper -',
                'value_options' => array()

            ),
        ));

        /*----------------------Goalkeeper Stats Start-------------------------*/
        //Saves
        $this->add(array(
            'name' => 'goalkeeper_saves',
            'type' => 'text',
            'attributes' => array(
                'between' => array(
                    'min' => self::MIN_MATCH_STATS_VALUE,
                    'max' => self::MAX_SEASON_SAVES
                ),
                'digits' => true,
                'maxlength' => self::MAX_SEASON_SAVES_INPUT_LENGTH,
            ),
            'options' => array(
                'label' => 'Number of saves this season',
            ),
        ));
        //Number of matches
        $this->add(array(
            'name' => 'goalkeeper_matches_played',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_INPUT_LENGTH,
                'between' => array(
                    'min' => self::MIN_MATCH_STATS_VALUE,
                    'max' => self::MAX_MATCH_STATS_VALUE
                ),
                'digits' => true,
            ),
            'options' => array(
                'label' => 'Number of matches played this season (started and as a substitute)',
            ),
        ));

        //Number of penalty saves
        $this->add(array(
            'name' => 'goalkeeper_penalty_saves',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_SEASON_PENALTIES_INPUT_LENGTH,
                'between' => array(
                    'min' => self::MIN_MATCH_STATS_VALUE,
                    'max' => self::MAX_SEASON_PENALTIES
                ),
                'digits' => true,
            ),
            'options' => array(
                'label' => 'Number of penalty saves this season',
            ),
        ));

        //Number of clean sheets
        $this->add(array(
            'name' => 'goalkeeper_clean_sheets',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_INPUT_LENGTH,
                'between' => array(
                    'min' => self::MIN_MATCH_STATS_VALUE,
                    'max' => self::MAX_MATCH_STATS_VALUE
                ),
                'digits' => true,
            ),
            'options' => array(
                'label' => 'Number of clean sheets this season',
            ),
        ));
        /*----------------------Goalkeeper Stats End---------------------------*/


    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    function initFieldsetByObject($match)
    {
        $region = $this->getRegion();
        foreach ($match->getMatchRegions() as $matchRegion) {
            if ($matchRegion->getLanguage()->getId() == $region['id']) {
                $featuredGoalkeeper = $matchRegion->getFeaturedGoalkeeper();
                //Featured Goalkeeper
                if ($featuredGoalkeeper && $featuredGoalkeeper->getPlayer()) {
                    $this->get('featured_goalkeeper')->setValue($featuredGoalkeeper->getPlayer()->getId());
                    $this->get('goalkeeper_saves')->setValue($featuredGoalkeeper->getSaves());
                    $this->get('goalkeeper_matches_played')->setValue($featuredGoalkeeper->getMatchesPlayed());
                    $this->get('goalkeeper_penalty_saves')->setValue($featuredGoalkeeper->getPenaltySaves());
                    $this->get('goalkeeper_clean_sheets')->setValue($featuredGoalkeeper->getCleanSheets());
                }
            }
        }
    }
}