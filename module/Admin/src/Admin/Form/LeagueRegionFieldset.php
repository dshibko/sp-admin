<?php

namespace Admin\Form;

use \Neoco\Form\RegionFieldset;

class LeagueRegionFieldset extends RegionFieldset {

    const DISPLAY_NAME_MAX_LENGTH = 255;
    const PRIZE_TITLE_MAX_LENGTH = 50;
    const POST_WIN_TITLE_MAX_LENGTH = 50;

    public function __construct($region) {

        parent::__construct($region);

        $this->add(array(
            'name' => 'displayName',
            'type'  => 'text',
            'attributes' => array(
                'required' => 'required',
                'maxlength' => self::DISPLAY_NAME_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'User facing league name',
            ),
        ));

        $this->add(array(
            'name' => 'prizeImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => 'required',
                'isImage' => true,
            ),
            'options' => array(
                'label' => 'Grand Prize Image',
            ),
        ));

        $this->add(array(
            'name' => 'prizeTitle',
            'type'  => 'text',
            'attributes' => array(
                'required' => 'required',
                'maxlength' => self::PRIZE_TITLE_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'Grand Prize Title',
            ),
        ));

        $this->add(array(
            'name' => 'prizeDescription',
            'type'  => 'textarea',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Grand Prize Description',
            ),
        ));

        $this->add(array(
            'name' => 'postWinImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => 'required',
                'isImage' => true,
            ),
            'options' => array(
                'label' => 'Post Win Image',
            ),
        ));

        $this->add(array(
            'name' => 'postWinTitle',
            'type'  => 'text',
            'attributes' => array(
                'required' => 'required',
                'maxlength' => self::POST_WIN_TITLE_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'Post Win Title',
            ),
        ));

        $this->add(array(
            'name' => 'postWinDescription',
            'type'  => 'textarea',
            'attributes' => array(
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Post Win Description',
            ),
        ));

    }

    /**
     * @param \Application\Model\Entities\League $league
     */
    function initFieldsetByObject($league) {
        $region = $this->getRegion();
        foreach ($league->getLeagueRegions() as $leagueRegion)
            if ($leagueRegion->getRegion()->getId() == $region['id']) {
                $this->get('displayName')->setValue($leagueRegion->getDisplayName());
                break;
            }
        foreach ($league->getPrizes() as $prize)
            if ($prize->getRegion()->getId() == $region['id']) {
                foreach ($this->getElements() as $element) {
                    $getter = 'get' . ucfirst($element->getName());
                    if (method_exists($prize, $getter)) {
                        $elValue = $element->getValue();
                        if ($element->getAttribute('isImage') || empty($elValue))
                            $element->setValue($prize->{$getter}());
                    }
                }
                break;
            }
    }

}