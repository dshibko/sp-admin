<?php

namespace Application\Controller;

use Application\Manager\LeagueManager;
use \Neoco\Exception\OutOfSeasonException;
use \Neoco\Exception\InfoException;
use \Application\Manager\ShareManager;
use Neoco\Validator\BadWordValidator;
use \Opta\Manager\ScoringManager;
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

class PrivateLeagueController extends AbstractActionController {

    const CREATE_PRIVATE_LEAGUE_ROUTE = 'create-private-league';
    const JOIN_PRIVATE_LEAGUE_ROUTE = 'join-private-league';

    const DISPLAY_NAME_MIN_LENGTH = 5;
    const DISPLAY_NAME_MAX_LENGTH = 25;

    public function createAction() {

        try {

            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

            $season = $applicationManager->getCurrentSeason();
            if ($season === null)
                throw new OutOfSeasonException();

            $user = $applicationManager->getCurrentUser();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost()->toArray();
                $this->checkSecurityKey(array($user->getId(), __CLASS__, __FUNCTION__), $post);
                if (array_key_exists('display-name', $post) && !empty($post['display-name']) &&
                    strlen($post['display-name']) >= self::DISPLAY_NAME_MIN_LENGTH && strlen($post['display-name']) <= self::DISPLAY_NAME_MAX_LENGTH) {
                    $displayName = $post['display-name'];

                    $badWordsValidator = new BadWordValidator();
                    $badWordsValidator->setServiceLocator($this->getServiceLocator());
                    if ($badWordsValidator->isValid($displayName)) {
                        try {
                            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
                            $leagueCode = $leagueManager->createPrivateLeague($displayName, $season, $user);
                            $this->flashMessenger()->addSuccessMessage(sprintf(MessagesConstants::SUCCESS_PRIVATE_LEAGUE_CREATED, $leagueCode));
                        } catch (\Exception $e) {
                            $this->flashMessenger()->addErrorMessage($e->getMessage());
                        }
                    } else
                        $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_DISPLAY_NAME_BAD_WORDS);
                } else
                    $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_DISPLAY_NAME);
                return $this->redirect()->toRoute(self::CREATE_PRIVATE_LEAGUE_ROUTE);
            }

            $securityKey = $this->generateSecurityKey(array($user->getId(), __CLASS__, __FUNCTION__));

            return array(
                'securityKey' => $securityKey,
                'minLength' => self::DISPLAY_NAME_MIN_LENGTH,
                'maxLength' => self::DISPLAY_NAME_MAX_LENGTH,
            );

        } catch (InfoException $e) {
            return $this->infoAction($e);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

    public function joinAction() {

        try {

            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

            $season = $applicationManager->getCurrentSeason();
            if ($season === null)
                throw new OutOfSeasonException();

            $user = $applicationManager->getCurrentUser();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost()->toArray();
                $this->checkSecurityKey(array($user->getId(), __CLASS__, __FUNCTION__), $post);
                if (array_key_exists('league-code', $post) && !empty($post['league-code'])) {
                    $leagueCode = $post['league-code'];
                    try {
                        $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
                        $leagueManager->joinPrivateLeague($leagueCode, $season, $user);
                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_JOINED_PRIVATE_LEAGUE);
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_LEAGUE_CODE);
                return $this->redirect()->toRoute(self::JOIN_PRIVATE_LEAGUE_ROUTE);
            }

            $securityKey = $this->generateSecurityKey(array($user->getId(), __CLASS__, __FUNCTION__));

            return array(
                'securityKey' => $securityKey,
            );

        } catch (InfoException $e) {
            return $this->infoAction($e);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

}