<?php

namespace Application\Controller;

use \Application\Manager\UserManager;
use \Application\Manager\MatchManager;
use \Application\Model\Entities\Match;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\SettingsManager;
use \Application\Manager\ExceptionManager;
use \Application\Manager\PredictionManager;
use \Application\Manager\RegistrationManager;
use \Neoco\Controller\AbstractActionController;
use \Application\Manager\ApplicationManager;
use \Application\Manager\ContentManager;
use \Application\Manager\RegionManager;
use Zend\View\Model\ViewModel;

class FixturesController extends AbstractActionController {

    const FIXTURES_ROUTE = 'fixtures';

    public function indexAction() {

        $matchManager = MatchManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        $settingsManager = SettingsManager::getInstance($this->getServiceLocator());

        $matchesLeft = array();
        $seasonRegion = null;
        $maxAhead = 0;

        try {

//            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();
            $seasonRegion = $season->getSeasonRegionByRegionId($regionManager->getSelectedRegion()->getId());

            if ($season == null)
                throw new \Exception(MessagesConstants::INFO_OUT_OF_SEASON);

            $maxAhead = $settingsManager->getSetting(SettingsManager::AHEAD_PREDICTIONS_DAYS);
            $matchesLeft = $matchManager->getMatchesLeftInTheSeason(new \DateTime(), $season, true);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'fixtures' => $matchesLeft,
            'seasonRegion' => $seasonRegion,
            'maxAhead' => $maxAhead,
        );

    }

}
