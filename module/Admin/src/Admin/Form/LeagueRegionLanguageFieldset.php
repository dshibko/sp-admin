<?php

namespace Admin\Form;

use Neoco\Form\RegionLanguageFieldset;

class LeagueRegionLanguageFieldset extends RegionLanguageFieldset {

    /**
     * @param \Application\Model\Entities\League $league
     */
    function initFieldsetByObject($league) {
        $region = $this->getRegion();
        $league = $region['id'] === null ? $season->getGlobalLeague() : $season->getRegionalLeagueByRegionId($region['id']);
        foreach ($this->getLanguageFieldsets() as $languageFieldset)
            if ($region['id'] === null)
                $languageFieldset->initFieldsetByObject($league, $season);
            else
                $languageFieldset->initFieldsetByObject($league);
    }

}