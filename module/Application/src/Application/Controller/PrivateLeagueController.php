<?php

namespace Application\Controller;

use Application\Manager\LeagueManager;
use \Neoco\Exception\OutOfSeasonException;
use \Neoco\Exception\InfoException;
use \Application\Model\Entities\AchievementBlock;
use \Application\Manager\ShareManager;
use \Application\Model\Entities\MatchGoal;
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
use Zend\View\Model\ViewModel;

class PrivateLeagueController extends AbstractActionController {

    const CREATE_PRIVATE_LEAGUE_ROUTE = 'create-private-league';

    const DISPLAY_NAME_MIN_LENGTH = 5;
    const DISPLAY_NAME_MAX_LENGTH = 15;

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
                    // todo bad words validation
                    try {
                        $leagueManager = LeagueManager::getInstance($this->getServiceLocator());
                        $leagueCode = $leagueManager->createPrivateLeague($displayName, $season, $user);
                        $this->flashMessenger()->addSuccessMessage(sprintf(MessagesConstants::SUCCESS_PRIVATE_LEAGUE_CREATED, $leagueCode));
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
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

}