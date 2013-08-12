<?php

namespace Application\Controller;

use Application\Manager\ContentManager;
use Application\Manager\LanguageManager;
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

            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

            $user = $applicationManager->getCurrentUser();
            $season = $applicationManager->getCurrentSeason();
            if ($season == null)
                throw new OutOfSeasonException();

            $language = $user->getLanguage();
            $globalLeague = $applicationManager->getGlobalLeague($season);
            $grandPrize = $globalLeague->getLeagueLanguageByLanguageId($language->getId());
            $grandPrize = $grandPrize->getArrayCopy();
            if (!$language->getIsDefault()) {
                $defaultLanguage = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
                $defaultGrandPrize = $globalLeague->getLeagueLanguageByLanguageId($defaultLanguage->getId());
                $grandPrize = ContentManager::getInstance($this->getServiceLocator())->extendContent($defaultGrandPrize->getArrayCopy(), $grandPrize);
            }

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
