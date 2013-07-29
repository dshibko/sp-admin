<?php

namespace Application\Controller;

use \Neoco\Exception\InfoException;
use \Neoco\Exception\OutOfSeasonException;
use \Zend\View\Model\ViewModel;
use \Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class PrizeController extends AbstractActionController {

    public function indexAction() {

        try {

            // todo seasons prizes

            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();
            if ($season == null)
                throw new OutOfSeasonException();

            $region = $applicationManager->getUserRegion($user);
            $globalLeague = $applicationManager->getGlobalLeague($season);
            $grandPrize = $globalLeague->getLeagueLanguageByLanguageId($region->getId());

            return array(
                'grandPrize' => $grandPrize,
            );

        } catch (InfoException $e) {
            return $this->infoAction($e);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

}
