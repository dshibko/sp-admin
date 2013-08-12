<?php

namespace Admin\Form;

use Application\Model\Entities\Season;

class SeasonLanguageFieldset extends LeagueLanguageFieldset {

    protected function initElements($required = false)
    {

        $this->add(array(
            'name' => 'seasonDisplayName',
            'type'  => 'text',
            'attributes' => array(
                'required' => $required,
                'maxlength' => self::SEASON_REGION_MAX_DISPLAY_NAME_LENGTH
            ),
            'options' => array(
                'label' => 'User Facing Season Name',
            ),
        ));

        parent::initElements($required);

        $this->add(array(
            'name' => 'terms',
            'type'  => 'textarea',
            'attributes' => array(
                'required' => $required
            ),
            'options' => array(
                'label' => 'Season Terms',
            ),
        ));

        $leagueNameEl = $this->get('leagueDisplayName');
        if (!empty($leagueNameEl)) $leagueNameEl->setLabel('User Facing Global League Name');
        $prizeImageEl = $this->get('prizeImage');
        if (!empty($prizeImageEl)) $prizeImageEl->setLabel('Grand Prize Image');
        $prizeTitleEl = $this->get('prizeTitle');
        if (!empty($prizeTitleEl)) $prizeTitleEl->setLabel('Grand Prize Title');
        $prizeDescriptionEl = $this->get('prizeDescription');
        if (!empty($prizeDescriptionEl)) $prizeDescriptionEl->setLabel('Grand Prize Description');
        $postWinImageEl = $this->get('postWinImage');
        if (!empty($postWinImageEl)) $postWinImageEl->setLabel('Grand Post Win Image');
        $postWinTitleEl = $this->get('postWinTitle');
        if (!empty($postWinTitleEl)) $postWinTitleEl->setLabel('Grand Post Win Title');
        $postWinDescriptionEl = $this->get('postWinDescription');
        if (!empty($postWinDescriptionEl)) $postWinDescriptionEl->setLabel('Grand Post Win Description');

    }

    /**
     * @param \Application\Model\Entities\League $league
     * @param Season|null $season
     */
    function initFieldsetByObject($league, $season = null) {
        parent::initFieldsetByObject($league);
        $language = $this->getLanguage();
        if ($season !== null) {
            $seasonLanguage = $season->getSeasonLanguageByLanguageId($language['id']);
            if ($seasonLanguage !== null) {
                $this->get('seasonDisplayName')->setValue($seasonLanguage->getDisplayName());
                $this->get('terms')->setValue($seasonLanguage->getTerms());
            }
        }
    }
}