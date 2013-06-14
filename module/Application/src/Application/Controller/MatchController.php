<?php

namespace Application\Controller;

use \Application\Manager\RegionManager;
use \Application\Manager\UserManager;
use \Application\Model\Entities\Match;
use \Application\Manager\MatchManager;
use \Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class MatchController extends AbstractActionController {

    public function indexAction() {

        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $matchManager = MatchManager::getInstance($this->getServiceLocator());
        $regionManager = RegionManager::getInstance($this->getServiceLocator());
        $userManager = UserManager::getInstance($this->getServiceLocator());

        try {

            $matchCode = (int) $this->params()->fromRoute('code', '');
            if (empty($matchCode))
                return $this->notFoundAction();
            $matchId = $this->decodeInt($matchCode);
            $match = $matchManager->getMatchInfo($matchId, true);
            if ($match == null)
                return $this->notFoundAction();

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();
            if ($user != null) {
                if ($match['status'] !== Match::FULL_TIME_STATUS) {
                    $ahead = $matchManager->getUpcomingMatchNumber($match['startTime'], $season);
                    return $this->redirect()->toRoute(PredictController::PREDICT_ROUTE, array('ahead' => $ahead != 1 ? $ahead - 1 : null));
                } else {
                    $back = $matchManager->getFinishedMatchNumber($user, $match['startTime'], $season);
                    return $this->redirect()->toRoute(ResultsController::RESULTS_ROUTE, array('back' => $back != 1 ? $back - 1 : null));
                }
            } else {
                // region detection
                $isCode = $userManager->getUserGeoIpIsoCode();
                if (!empty($isCode) && ($country = $applicationManager->getCountryByISOCode($isCode)) !== null &&
                    $country->getRegion() !== null)
                    $region = $country->getRegion();
                else
                    $region = $regionManager->getDefaultRegion();

                $isPreMatchReport = ($match['status'] !== Match::FULL_TIME_STATUS);

                $matchReport = $isPreMatchReport ? $matchManager->getPreMatchRegionReport($match['id'], $region->getId())
                    : $matchManager->getPostMatchRegionReport($match['id'], $region->getId());

                if ($isPreMatchReport) {

                } else {
                    $goals = $matchManager->getMatchGoals($match['id'], true);
                    $resultsController = new \Application\Controller\ResultsController();
                    $match['goals'] = $resultsController->getScorers($goals, $match);
                }

                $localStartTime = ApplicationManager::getInstance($this->getServiceLocator())->getLocalTime($match['startTime'], $match['timezone']);
                return array(
                    'current' => $match,
                    'localStartTime' => $localStartTime,
                    'isPreMatchReport' => $isPreMatchReport,
                    'matchReport' => $matchReport,
                    'matchCode' => $this->encodeInt($match['id']),
                );
            }

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array();

    }

}
