<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;
use \Zend\Form\Fieldset;

class FixtureRegionFieldset extends RegionFieldset {

    protected $players = array();

    public function __construct($region) {

        parent::__construct($region);

        //Featured Player
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                //'required' => 'required',
                'class' => 'chosen',
                'tabindex' => 6,
                'style' => 'width:247px',
                'data-placeholder' => 'Choose Featured Player'
            ),
            'name' => 'featured_player',
            'options' => array(
                'label' => 'Featured Player',
                'empty_option' => '- Select Featured Player -',
                'value_options' => $this->getPlayers()

            ),
        ));

        //Title
        $this->add(array(
            'name' => 'title',
            'type'  => 'text',
            'attributes' => array(),
            'options' => array(
                'label' => 'Title',
            ),
        ));

        //Intro
        $this->add(array(
            'name' => 'intro',
            'type'  => 'textarea',
            'attributes' => array(),
            'options' => array(
                'label' => 'Intro',
            ),
        ));

        //Header Image
        $this->add(array(
            'name' => 'header_image',
            'type'  => 'file',
            'attributes' => array(
                'isImage' => true,
            ),
            'options' => array(
                'label' => 'Header Image',
            ),
        ));
    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    function initFieldsetByObject($match) {
        $region = $this->getRegion();
        foreach ($match->getMatchRegions() as $matchRegion) {
            if ($matchRegion->getRegion()->getId() == $region['id']) {
                $this->get('title')->setValue($matchRegion->getTitle());
                $this->get('intro')->setValue($matchRegion->getIntro());
                $this->get('header_image')->setValue($matchRegion->getHeaderImagePath());
                if ($matchRegion->getFeaturedPlayer()){
                    $this->get('featured_player')->setValue($matchRegion->getFeaturedPlayer()->getId());
                }

            }
        }
    }

    /**
     * @param $players
     * @return \Admin\Form\FixtureRegionFieldset
     */
    public function setPlayers($players)
    {
        $this->players = $players;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlayers()
    {
        return $this->players;
    }

}