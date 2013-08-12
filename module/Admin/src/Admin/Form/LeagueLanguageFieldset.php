<?php

namespace Admin\Form;

use \Neoco\Form\LanguageFieldset;

class LeagueLanguageFieldset extends LanguageFieldset {

    const SEASON_REGION_MAX_DISPLAY_NAME_LENGTH = 100;
    const PRIZE_TITLE_MAX_LENGTH = 50;
    const POST_WIN_TITLE_MAX_LENGTH = 50;

    public function __construct($language, $required = false) {

        parent::__construct($language);
        $this->initElements($required);

    }

    protected function initElements($required = false) {

        $this->add(array(
            'name' => 'leagueDisplayName',
            'type'  => 'text',
            'attributes' => array(
                'required' => $required,
                'maxlength' => self::SEASON_REGION_MAX_DISPLAY_NAME_LENGTH
            ),
            'options' => array(
                'label' => 'User Facing League Name',
            ),
        ));

        $this->add(array(
            'name' => 'prizeImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => $required,
                'isImage' => true,
            ),
            'options' => array(
                'label' => 'League Prize Image',
            ),
        ));

        $this->add(array(
            'name' => 'prizeTitle',
            'type'  => 'text',
            'attributes' => array(
                'required' => $required,
                'maxlength' => self::PRIZE_TITLE_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'League Prize Title',
            ),
        ));

        $this->add(array(
            'name' => 'prizeDescription',
            'type'  => 'textarea',
            'attributes' => array(
                'required' => $required
            ),
            'options' => array(
                'label' => 'League Prize Description',
            ),
        ));

        $this->add(array(
            'name' => 'postWinImage',
            'type'  => 'file',
            'attributes' => array(
                'required' => $required,
                'isImage' => true,
            ),
            'options' => array(
                'label' => 'League Post Win Image',
            ),
        ));

        $this->add(array(
            'name' => 'postWinTitle',
            'type'  => 'text',
            'attributes' => array(
                'required' => $required,
                'maxlength' => self::POST_WIN_TITLE_MAX_LENGTH
            ),
            'options' => array(
                'label' => 'League Post Win Title',
            ),
        ));

        $this->add(array(
            'name' => 'postWinDescription',
            'type'  => 'textarea',
            'attributes' => array(
                'required' => $required
            ),
            'options' => array(
                'label' => 'League Post Win Description',
            ),
        ));

    }

    /**
     * @param \Application\Model\Entities\League $league
     */
    function initFieldsetByObject($league) {
        $language = $this->getLanguage();
        $leagueLanguage = $league->getLeagueLanguageByLanguageId($language['id']);
        foreach ($this->getElements() as $element) {
            $name = $element->getName();
            $getter = 'get' . ucfirst($this->filterFieldName($name));
            if (method_exists($leagueLanguage, $getter)) {
                $elValue = $element->getValue();
                if ($element->getAttribute('isImage') || empty($elValue))
                    $element->setValue($leagueLanguage->{$getter}());
            }
        }
    }

    private function filterFieldName($name) {
        switch ($name) {
            case 'leagueDisplayName':
                return 'displayName';
            default:
                return $name;
        }
    }

}