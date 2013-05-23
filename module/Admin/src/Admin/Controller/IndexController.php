<?php

namespace Admin\Controller;

use \Application\Manager\MatchManager;
use \Application\Manager\PredictionManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Application\Manager\UserManager;
use \Application\Manager\ApplicationManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    const ADMIN_LOGIN_PAGE_ROUTE = 'admin-login';

    public function indexAction() {

        $config = $this->getServiceLocator()->get('config');
        $rules = $config['zfcrbac']['firewalls']['ZfcRbac\Firewall\Route'];
        $rbac = $this->getServiceLocator()->get('ZfcRbac\Service\Rbac');
        $router = new \ZfcRbac\Firewall\Route($rules);
        $router->setRbac($rbac);
        if (!$router->isGranted('admin'))
            return $this->redirect()->toRoute(self::ADMIN_LOGIN_PAGE_ROUTE);

        try {
            $userManager = UserManager::getInstance($this->getServiceLocator());
            $registeredUsersNumber = $userManager->getRegisteredUsersNumber();
            $usersRegisteredInPast7Days = $userManager->getUsersRegisteredInPastDays(7, true);
            $currentSeason = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
            if ($currentSeason == null) {
                $activeUsersNumber = $inactiveUsersNumber = $avgNumberOfPredictions =
                $nextMatchPredictions = $prevMatchPredictions = MessagesConstants::INFO_ADMIN_OUT_OF_SEASON;
            } else {
                $activeUsersNumber = $userManager->getActiveUsersNumber($currentSeason);
                $inactiveUsersNumber = $registeredUsersNumber - $activeUsersNumber;

                $predictionManager = PredictionManager::getInstance($this->getServiceLocator());
                $avgNumberOfPredictions = $predictionManager->getAvgNumberOfPrediction($currentSeason);
                $matchManager = MatchManager::getInstance($this->getServiceLocator());
                $nextMatch = $matchManager->getNextMatch();
                if ($nextMatch != null)
                    $nextMatchPredictions = $nextMatch->getPredictions()->count();
                else
                    $nextMatchPredictions = MessagesConstants::INFO_ADMIN_NO_NEXT_MATCH;
                $prevMatch = $matchManager->getPrevMatch();
                if ($prevMatch != null)
                    $prevMatchPredictions = $prevMatch->getPredictions()->count();
                else
                    $prevMatchPredictions = MessagesConstants::INFO_ADMIN_NO_PREV_MATCH;
            }

        } catch(\Exception $e) {
            $registeredUsersNumber = $activeUsersNumber = $inactiveUsersNumber =
                $avgNumberOfPredictions = $nextMatchPredictions = $prevMatchPredictions = 'N\A';
            $usersRegisteredInPast7Days = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array(
            'registeredUsersNumber' => $registeredUsersNumber,
            'activeUsersNumber' => $activeUsersNumber,
            'inactiveUsersNumber' => $inactiveUsersNumber,
            'avgNumberOfPredictions' => $avgNumberOfPredictions,
            'nextMatchPredictions' => $nextMatchPredictions,
            'prevMatchPredictions' => $prevMatchPredictions,
            'usersRegisteredInPast7Days' => $usersRegisteredInPast7Days
        ));

    }

    public function myAccountAction() {
        return new ViewModel(array(
        ));
    }

}
