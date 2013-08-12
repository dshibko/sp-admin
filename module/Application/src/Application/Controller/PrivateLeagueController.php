<?php

namespace Application\Controller;

use Application\Manager\LeagueManager;
use Application\Manager\UserManager;
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
    const LEAGUE_TABLES_ROUTE = 'tables';
    const FULL_LEAGUE_TABLES_ROUTE = 'full-table';

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
                            $leagueCode = $leagueManager->createPrivateLeague(htmlspecialchars($displayName), $season, $user);
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
                        $leagueManager->joinPrivateLeague($leagueCode, $user);
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

    public function deleteAction() {

        try {

            $leagueCode = $this->params()->fromRoute('code', '');
            if (empty($leagueCode))
                return $this->notFoundAction();

            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
            $league = $leagueManager->getPrivateLeagueByCode($leagueCode);
            if (empty($league))
                return $this->notFoundAction();

            $user = $applicationManager->getCurrentUser();

            if ($league->getCreator()->getId() == $user->getId()) {
                $leagueManager->deleteLeague($league);
                $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_DELETED_PRIVATE_LEAGUE);
            } else
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_ADMIN_DELETES_PRIVATE_LEAGUE);

            return $this->redirect()->toRoute(self::LEAGUE_TABLES_ROUTE);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

    public function leaveAction() {

        try {

            $leagueCode = $this->params()->fromRoute('code', '');
            if (empty($leagueCode))
                return $this->notFoundAction();

            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());

            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
            $league = $leagueManager->getPrivateLeagueByCode($leagueCode);
            if (empty($league))
                return $this->notFoundAction();

            $user = $applicationManager->getCurrentUser();

            if (!$leagueManager->getIsUserInLeague($league, $user))
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_NOT_MEMBER_OF_LEAGUE);
            else if ($league->getCreator()->getId() == $user->getId())
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_ADMIN_CANNOT_LEAVE_PRIVATE_LEAGUE);
            else {
                $leagueManager->leavePrivateLeague($league, $user);
                $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_LEFT_PRIVATE_LEAGUE);
            }

            return $this->redirect()->toRoute(self::LEAGUE_TABLES_ROUTE);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

    public function removeUserAction() {

        try {

            $leagueCode = $this->params()->fromRoute('code', '');
            $userId = $this->params()->fromRoute('id', 0);
            if (empty($leagueCode) || empty($userId))
                return $this->notFoundAction();

            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
            $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
            $userManager = UserManager::getInstance($this->getServiceLocator());

            $league = $leagueManager->getPrivateLeagueByCode($leagueCode);
            if (empty($league))
                return $this->notFoundAction();

            $user = $applicationManager->getCurrentUser();
            $targetUser = $userManager->getUserById($userId);
            if (empty($targetUser))
                return $this->notFoundAction();

            if ($league->getCreator()->getId() != $user->getId())
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_ADMIN_REMOVES_USERS_FROM_PRIVATE_LEAGUE);
            else if ($league->getCreator()->getId() != $user->getId())
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_ADMIN_CANNOT_LEAVE_PRIVATE_LEAGUE);
            else if (!$leagueManager->getIsUserInLeague($league, $targetUser))
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_NOT_MEMBER_OF_LEAGUE);
            else {
                $leagueManager->leavePrivateLeague($league, $targetUser);
                $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_REMOVED_USER_FROM_PRIVATE_LEAGUE);
            }

            return $this->redirect()->toRoute(self::FULL_LEAGUE_TABLES_ROUTE, array('table' => $league->getId()));

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }

    }

}