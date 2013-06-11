<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;

class FeaturedPlayerFieldset extends RegionFieldset
{

    const FIELD_GROUP_FEATURED_PLAYER = 'featured_player';


    const MAX_MATCH_STATS_VALUE = 999;
    const MIN_MATCH_STATS_VALUE = 0;
    const MAX_SEASON_PLAYED_MINUTES = 999999;

    const MAX_INPUT_LENGTH = 3;
    const MAX_SEASON_PLAYED_MINUTES_INPUT_LENGTH = 6;


    public function __construct($region)
    {

        parent::__construct($region);

        //Featured Player
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'fieldgroup' => array(
                    'type' => 'start',
                    'title' => 'Featured Player',
                    'name' => self::FIELD_GROUP_FEATURED_PLAYER,
                    'color' => 'red'
                ),
                'class' => 'chosen',
                'tabindex' => 6,
                'style' => 'width:247px',
                'data-placeholder' => 'Choose Featured Player'
            ),
            'name' => 'featured_player',
            'options' => array(
                'label' => 'Featured Player',
                'empty_option' => '- Select Featured Player -',
                'value_options' => array()

            ),
        ));

        /*----------------------Player Stats Start-------------------------*/
        //Number of goals
        $this->add(array(
            'name' => 'player_goals',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_INPUT_LENGTH,
                'between' => array(
                    'min' => self::MIN_MATCH_STATS_VALUE,
                    'max' => self::MAX_MATCH_STATS_VALUE
                ),
                'digits' => true,
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_FEATURED_PLAYER
                ),
            ),
            'options' => array(
                'label' => 'Number of goals scored this season',
            ),
        ));
        //Number of matches
        $this->add(array(
            'name' => 'player_matches_played',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_INPUT_LENGTH,
                'between' => array(
                    'min' => self::MIN_MATCH_STATS_VALUE,
                    'max' => self::MAX_MATCH_STATS_VALUE
                ),
                'digits' => true,
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_FEATURED_PLAYER
                ),
            ),
            'options' => array(
                'label' => 'Number of matches played this season (started and as a substitute)',
            ),
        ));

        //Number of matches starts this season
        $this->add(array(
            'name' => 'player_match_starts',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_INPUT_LENGTH,
                'between' => array(
                    'min' => self::MIN_MATCH_STATS_VALUE,
                    'max' => self::MAX_MATCH_STATS_VALUE
                ),
                'digits' => true,
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_FEATURED_PLAYER
                ),
            ),
            'options' => array(
                'label' => 'Number of match starts this season',
            ),
        ));

        //Total number of minutes
        $this->add(array(
            'name' => 'player_minutes_played',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_SEASON_PLAYED_MINUTES_INPUT_LENGTH,
                'between' => array(
                    'min' => self::MIN_MATCH_STATS_VALUE,
                    'max' => self::MAX_SEASON_PLAYED_MINUTES
                ),
                'digits' => true,
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_FEATURED_PLAYER,
                    'type' => 'end'
                )
            ),
            'options' => array(
                'label' => 'Total number of minutes played this season',
            ),
        ));
        /*----------------------Player Stats End---------------------------*/

    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    function initFieldsetByObject($match)
    {
        $region = $this->getRegion();
        foreach ($match->getMatchRegions() as $matchRegion) {
            if ($matchRegion->getRegion()->getId() == $region['id']) {
                $featuredPlayer = $matchRegion->getFeaturedPlayer();
                //Featured Player
                if ($featuredPlayer && $featuredPlayer->getPlayer()) {
                    $this->get('featured_player')->setValue($featuredPlayer->getPlayer()->getId());
                    $this->get('player_matches_played')->setValue($featuredPlayer->getMatchesPlayed());
                    $this->get('player_match_starts')->setValue($featuredPlayer->getMatchStarts());
                    $this->get('player_minutes_played')->setValue($featuredPlayer->getMinutesPlayed());
                    $this->get('player_goals')->setValue($featuredPlayer->getGoals());
                }
            }
        }
    }
}