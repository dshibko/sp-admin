<?php

namespace Application\Controller;

use Application\Manager\SeasonManager;
use \Neoco\Exception\OutOfSeasonException;
use \Neoco\Exception\InfoException;
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

    public function indexAction() {

        try {

            $matchManager = MatchManager::getInstance($this->getServiceLocator());
            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
            $seasonManager = SeasonManager::getInstance($this->getServiceLocator());
            $settingsManager = SettingsManager::getInstance($this->getServiceLocator());

            $season = $applicationManager->getCurrentSeason();
            if ($season == null)
                throw new OutOfSeasonException();

            $seasonName = $seasonManager->getSeasonDisplayName($season->getId());
            $maxAhead = $settingsManager->getSetting(SettingsManager::AHEAD_PREDICTIONS_DAYS);
            $matchesLeft = $matchManager->getMatchesLeftInTheSeason($season, true);
            if (empty($matchesLeft))
                throw new InfoException(MessagesConstants::INFO_NO_MORE_MATCHES_WILL_BE_PLAYED);

            return array(
                'fixtures' => $matchesLeft,
                'seasonName' => $seasonName,
                'maxAhead' => $maxAhead,
            );

        } catch (InfoException $e) {
            return $this->infoAction($e);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

}