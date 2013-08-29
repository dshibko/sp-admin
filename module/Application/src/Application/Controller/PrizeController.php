<?php

namespace Application\Controller;

use Application\Manager\ContentManager;
use Application\Manager\LanguageManager;
use Application\Manager\LeagueManager;
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

            // grand prize

            $globalLeague = $applicationManager->getGlobalLeague($season);

            $language = $user->getLanguage();
            $grandPrize = $globalLeague->getLeagueLanguageByLanguageId($language->getId());
            $grandPrize = $grandPrize->getArrayCopy();

            $defaultLanguage = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();

            if (!$language->getIsDefault()) {
                $defaultGrandPrize = $globalLeague->getLeagueLanguageByLanguageId($defaultLanguage->getId());
                $grandPrize = ContentManager::getInstance($this->getServiceLocator())->extendContent($defaultGrandPrize->getArrayCopy(), $grandPrize);
            }

            // mini league prize

            $miniLeaguesPrizes = array();
            $region = $user->getCountry()->getRegion();
            if ($region != null) {
                $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
                $temporalLeagues = $leagueManager->getTemporalLeagues($region, $season);
                foreach ($temporalLeagues as $temporalLeague) {
                    $miniLeaguePrize = $temporalLeague->getLeagueLanguageByLanguageId($language->getId());
                    $miniLeaguePrize = $miniLeaguePrize->getArrayCopy();
                    if (!$language->getIsDefault()) {
                        $defaultMiniLeaguePrize = $temporalLeague->getLeagueLanguageByLanguageId($defaultLanguage->getId());
                        $miniLeaguePrize = ContentManager::getInstance($this->getServiceLocator())->extendContent($defaultMiniLeaguePrize->getArrayCopy(), $miniLeaguePrize);
                    }
                    $miniLeaguesPrizes [] = $miniLeaguePrize;
                }
            }

            return array(
                'grandPrize' => $grandPrize,
                'miniLeaguesPrizes' => $miniLeaguesPrizes,
            );

        } catch (InfoException $e) {
            return $this->infoAction($e);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

}
