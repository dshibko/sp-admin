<?php

namespace Application\Controller;

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

class PredictController extends AbstractActionController {

    public function indexAction() {

        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $settingsManager = SettingsManager::getInstance($this->getServiceLocator());

        $currentMatch = array();
        $ahead = $maxAhead = 0;

        try {

            $maxAhead = $settingsManager->getSetting(SettingsManager::AHEAD_PREDICTIONS_DAYS);

            $ahead = (int) $this->params()->fromRoute('ahead', 0);

            if ($ahead > $maxAhead)
                throw new \Exception(sprintf(MessagesConstants::ERROR_PREDICT_THIS_MATCH_NOT_ALLOWED, $maxAhead));

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();
            $matchesLeft = $predictionManager->getMatchesLeftInTheSeason(new \DateTime(), $season);

            if ($matchesLeft == 0)
                throw new \Exception(MessagesConstants::ERROR_NO_MORE_MATCHES_IN_THE_SEASON);
            else
                $matchesLeft--;

            if ($ahead > $matchesLeft)
                throw new \Exception(MessagesConstants::ERROR_MATCH_NOT_FOUND);

            $currentMatch = $predictionManager->getNearestMatchWithPrediction(new \DateTime(), $ahead, $user, $season, true);

            if ($currentMatch == null)
                throw new \Exception(MessagesConstants::ERROR_MATCH_NOT_FOUND);

            if ($maxAhead > $matchesLeft)
                $maxAhead = $matchesLeft;

        } catch (\Exception $e) {
            var_dump($e->getMessage());die;
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'current' => $currentMatch,
            'ahead' => $ahead,
            'maxAhead' => $maxAhead,
        );

    }

}
