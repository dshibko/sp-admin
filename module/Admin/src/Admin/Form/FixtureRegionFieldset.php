<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;
use \Zend\Form\Fieldset;

class FixtureRegionFieldset extends RegionFieldset
{

    const FIELD_GROUP_FEATURED_PLAYER = 'featured_player';
    const FIELD_GROUP_FEATURED_GOALKEEPER = 'featured_goalkeeper';
    const FIELD_GROUP_FEATURED_PREDICTION = 'featured_prediction';
    const FIELD_GROUP_MATCH_REPORT = 'match_report';

    const MAX_MATCH_STATS_VALUE = 999;
    const MIN_MATCH_STATS_VALUE = 0;
    const MAX_SEASON_PLAYED_MINUTES = 999999;
    const MAX_SEASON_SAVES = 999999;
    const MAX_SEASON_PENALTIES = 999999;

    const MAX_INPUT_LENGTH = 3;
    const MAX_SEASON_PLAYED_MINUTES_INPUT_LENGTH = 6;
    const MAX_SEASON_SAVES_INPUT_LENGTH = 6;
    const MAX_SEASON_PENALTIES_INPUT_LENGTH = 6;

    const MAX_TITLE_LENGTH = 255;

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

        //Featured Goalkeeper
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                //'required' => 'required',
                'class' => 'chosen',
                'tabindex' => 6,
                'style' => 'width:247px',
                'data-placeholder' => 'Choose Featured Goalkeeper',
                'fieldgroup' => array(
                    'type' => 'start',
                    'color' => 'green',
                    'title' => 'Featured Goalkeeper',
                    'name' => self::FIELD_GROUP_FEATURED_GOALKEEPER
                )
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
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_FEATURED_GOALKEEPER
                )
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
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_FEATURED_GOALKEEPER
                )
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
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_FEATURED_GOALKEEPER
                )
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
                'fieldgroup' => array(
                    'maxlength' => self::MAX_INPUT_LENGTH,
                    'between' => array(
                        'min' => self::MIN_MATCH_STATS_VALUE,
                        'max' => self::MAX_MATCH_STATS_VALUE
                    ),
                    'digits' => true,
                    'type' => 'end',
                    'name' => self::FIELD_GROUP_FEATURED_GOALKEEPER
                )
            ),
            'options' => array(
                'label' => 'Number of clean sheets this season',
            ),
        ));
        /*----------------------Goalkeeper Stats End---------------------------*/

        /*----------------------Featured Prediction Start----------------------*/

        //Predictors name
        $this->add(array(
            'name' => 'prediction_name',
            'type' => 'text',
            'attributes' => array(
                'hint' => 'Name of person who is making the prediction',
                'maxlength' => self::MAX_TITLE_LENGTH,
                'fieldgroup' => array(
                    'type' => 'start',
                    'color' => 'purple',
                    'title' => 'Featured Prediction',
                    'name' => self::FIELD_GROUP_FEATURED_PREDICTION
                )
            ),
            'options' => array(
                'label' => 'Predictor Name',
            ),
        ));

        //Prediction
        $this->add(array(
            'name' => 'prediction_copy',
            'type' => 'textarea',
            'attributes' => array(
                'hint' => 'Prediction of the user',
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_FEATURED_PREDICTION
                )
            ),
            'options' => array(
                'label' => 'Copy',
            ),
        ));

        //Image
        $this->add(array(
            'name' => 'prediction_image',
            'type' => 'file',
            'attributes' => array(
                'hint' => 'Image of the person',
                'isImage' => true,
                'fieldgroup' => array(
                    'type' => 'end',
                    'name' => self::FIELD_GROUP_FEATURED_PREDICTION
                )
            ),
            'options' => array(
                'label' => 'Image of the predictor',
            ),
        ));

        /*----------------------Featured Prediction End------------------------*/
        //Title
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => self::MAX_TITLE_LENGTH,
                'hint' => 'Title of report',
                'fieldgroup' => array(
                    'type' => 'start',
                    'color' => 'yellow',
                    'title' => 'Match Report',
                    'name' => self::FIELD_GROUP_MATCH_REPORT
                )
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));

        //Intro
        $this->add(array(
            'name' => 'intro',
            'type' => 'textarea',
            'attributes' => array(
                'hint' => 'Intro of report',
                'fieldgroup' => array(
                    'name' => self::FIELD_GROUP_MATCH_REPORT
                )
            ),
            'options' => array(
                'label' => 'Intro',
            ),
        ));

        //Header Image
        $this->add(array(
            'name' => 'header_image',
            'type' => 'file',
            'attributes' => array(
                'hint' => 'Header image of report',
                'isImage' => true,
                'fieldgroup' => array(
                    'type' => 'end',
                    'name' => self::FIELD_GROUP_MATCH_REPORT
                )
            ),
            'options' => array(
                'label' => 'Header Image',
            ),
        ));
    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    function initFieldsetByObject($match)
    {
        $region = $this->getRegion();
        foreach ($match->getMatchRegions() as $matchRegion) {
            if ($matchRegion->getRegion()->getId() == $region['id']) {
                $this->get('title')->setValue($matchRegion->getTitle());
                $this->get('intro')->setValue($matchRegion->getIntro());
                $this->get('header_image')->setValue($matchRegion->getHeaderImagePath());
                $featuredPlayer = $matchRegion->getFeaturedPlayer();
                $featuredGoalkeeper = $matchRegion->getFeaturedGoalkeeper();
                $featuredPrediction = $matchRegion->getFeaturedPrediction();
                //Featured Player
                if ($featuredPlayer && $featuredPlayer->getPlayer()) {
                    $this->get('featured_player')->setValue($featuredPlayer->getPlayer()->getId());
                    $this->get('player_matches_played')->setValue($featuredPlayer->getMatchesPlayed());
                    $this->get('player_match_starts')->setValue($featuredPlayer->getMatchStarts());
                    $this->get('player_minutes_played')->setValue($featuredPlayer->getMinutesPlayed());
                    $this->get('player_goals')->setValue($featuredPlayer->getGoals());
                }

                //Featured Goalkeeper
                if ($featuredGoalkeeper && $featuredGoalkeeper->getPlayer()) {
                    $this->get('featured_goalkeeper')->setValue($featuredGoalkeeper->getPlayer()->getId());
                    $this->get('goalkeeper_saves')->setValue($featuredGoalkeeper->getSaves());
                    $this->get('goalkeeper_matches_played')->setValue($featuredGoalkeeper->getMatchesPlayed());
                    $this->get('goalkeeper_penalty_saves')->setValue($featuredGoalkeeper->getPenaltySaves());
                    $this->get('goalkeeper_clean_sheets')->setValue($featuredGoalkeeper->getCleanSheets());
                }

                //Featured Prediction
                if ($featuredPrediction) {
                    $this->get('prediction_name')->setValue($featuredPrediction->getName());
                    $this->get('prediction_copy')->setValue($featuredPrediction->getCopy());
                    $this->get('prediction_image')->setValue($featuredPrediction->getImagePath());
                }
            }
        }
    }

    /*public function getInputFilterSpecification($inputSpec = array())
    {


        foreach ($this->getElements() as $element) {
            echo $element->getValue();
        }

        return parent::getInputFilterSpecification($inputSpec);
    }*/
}