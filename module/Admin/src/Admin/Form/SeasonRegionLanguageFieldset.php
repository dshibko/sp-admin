<?php

namespace Admin\Form;

use Neoco\Form\RegionLanguageFieldset;

class SeasonRegionLanguageFieldset extends RegionLanguageFieldset {

    /**
     * @param \Application\Model\Entities\Season $season
     */
    function initFieldsetByObject($season) {
        $region = $this->getRegion();
        $league = $region['id'] === null ? $season->getGlobalLeague() : $season->getRegionalLeagueByRegionId($region['id']);
        foreach ($this->getLanguageFieldsets() as $languageFieldset)
            if ($league != null)
                if ($region['id'] === null)
                    $languageFieldset->initFieldsetByObject($league, $season);
                else
                    $languageFieldset->initFieldsetByObject($league);
    }

}