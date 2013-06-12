<?php

namespace Application\Controller;

use \Zend\View\Model\ViewModel;
use \Application\Manager\LeagueManager;
use \Application\Model\DAOs\LeagueUserDAO;
use \Application\Manager\RegionManager;
use \Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class FullTableController extends AbstractActionController {

    const FULL_TABLE_ROUTE = 'full-table';
    const PER_PAGE_PLAYERS_COUNT = 20;

    public function indexAction() {

        $leagueId = (int) $this->params()->fromRoute('table', 0);
        if ($leagueId <= 0)
            return $this->notFoundAction();

        $leagueManager = LeagueManager::getInstance($this->getServiceLocator());

        $leagueUsers = array();
        $leagueUsersCount = 0;

        try {

            $league = $leagueManager->getLeagueById($leagueId, true);
            if ($league === null)
                return $this->notFoundAction();

            $offset = (int) $this->params()->fromQuery('offset', 0);
            $leagueUsersCount = $leagueManager->getLeagueUsersCount($league['id']);
            if ($offset < 0) $offset = 0;
            if ($leagueUsersCount > $offset)
                $leagueUsers = $leagueManager->getLeagueTop($league['id'], self::PER_PAGE_PLAYERS_COUNT, $offset);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        $onlyRows = (boolean) $this->params()->fromQuery('onlyRows', false);
        $viewModel = new ViewModel();
        $viewModel->setTerminal($onlyRows);
        return $viewModel->setVariables(array(
            'leagueUsers' => $leagueUsers,
            'leagueUsersCount' => $leagueUsersCount,
            'onlyRows' => $onlyRows,
            'perPage' => self::PER_PAGE_PLAYERS_COUNT,
        ));

    }

}
