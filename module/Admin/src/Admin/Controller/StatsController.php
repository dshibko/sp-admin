<?php

namespace Admin\Controller;

use Application\Manager\ExportManager;
use \Application\Manager\StatsManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class StatsController extends AbstractActionController {
    
    public function exportFacebookVsDirectAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getFacebookVsDirectData();

            $exportConfig = array(
                'facebook' => 'number',
                'direct' => 'number',
            );

            $content = $exportManager->exportArrayToCSV(array($data), $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function facebookVsDirectAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getFacebookVsDirectData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportRegistrationsPerWeekAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getRegistrationsPerWeekData();

            $exportConfig = array(
                'first_week_day' => array('date' => 'j F Y'),
                'last_week_day' => array('date' => 'j F Y'),
                'registrations' => 'number',
            );

            $content = $exportManager->exportArrayToCSV($data, $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function registrationsPerWeekAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getRegistrationsPerWeekData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportActiveVsInactiveAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getActiveVsInactiveData();

            $exportConfig = array(
                'active' => 'number',
                'inactive' => 'number',
            );

            $content = $exportManager->exportArrayToCSV(array($data), $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function activeVsInactiveAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getActiveVsInactiveData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportIncompleteRegistrationsAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getIncompleteRegistrationsData();

            $exportConfig = array(
                'incomplete' => 'number',
            );

            $content = $exportManager->exportArrayToCSV(array($data), $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function incompleteRegistrationsAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getIncompleteRegistrationsData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportAccountDeletionsAction() {

        try {
            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getAccountDeletionsData();

            $exportConfig = array(
                'facebook' => 'number',
                'direct' => 'number',
            );

            $content = $exportManager->exportArrayToCSV(array($data), $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function accountDeletionsAction() {

        try {
            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getAccountDeletionsData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportUsersByRegionAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getUsersByRegionData();

            $exportConfig = array(
                'region' => 'string',
                'users' => 'number',
            );

            $content = $exportManager->exportArrayToCSV($data, $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function usersByRegionAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getUsersByRegionData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportPredictionsThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getPredictionsThisSeasonData();

            $exportConfig = array(
                'predictions' => 'number',
            );

            $content = $exportManager->exportArrayToCSV(array($data), $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function predictionsThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getPredictionsThisSeasonData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportPredictionsPerMatchOverTimeAction() {

        try {
            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getPredictionsPerDayThisSeasonData();

            $exportConfig = array(
                'predictions' => 'number',
                'date' => array('date' => 'j F Y'),
            );

            $content = $exportManager->exportArrayToCSV($data, $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function predictionsPerMatchOverTimeAction() {

        try {
            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getPredictionsPerDayThisSeasonData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportAvgPredictionsPerMatchThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getAvgPredictionsPerMatchThisSeasonData();

            $exportConfig = array(
                'avg_number_of_predictions' => 'number',
            );

            $content = $exportManager->exportArrayToCSV(array($data), $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function avgPredictionsPerMatchThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getAvgPredictionsPerMatchThisSeasonData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportHighestPredictedMatchAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getHighestPredictedMatchData();

            $exportConfig = array(
                'predictions' => 'number',
                'start_time' => array('date' => 'j F Y'),
                'competition' => 'string',
                'home_team' => 'string',
                'away_team' => 'string',
            );

            $content = $exportManager->exportArrayToCSV($data, $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function highestPredictedMatchAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getHighestPredictedMatchData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportLowestPredictedMatchAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getLowestPredictedMatchData();

            $exportConfig = array(
                'predictions' => 'number',
                'start_time' => array('date' => 'j F Y'),
                'competition' => 'string',
                'home_team' => 'string',
                'away_team' => 'string',
            );

            $content = $exportManager->exportArrayToCSV($data, $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function lowestPredictedMatchAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getLowestPredictedMatchData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportMostPopularScorersThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getMostPopularScorersThisSeasonData();

            $exportConfig = array(
                'player' => 'string',
                'team' => 'string',
                'predictions' => 'number',
            );

            $content = $exportManager->exportArrayToCSV($data, $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function mostPopularScorersThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getMostPopularScorersThisSeasonData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportMostPopularScoresThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $exportManager = ExportManager::getInstance($this->getServiceLocator());

            $data = $statsManager->getMostPopularScoresThisSeasonData();

            $exportConfig = array(
                'score' => 'string',
                'predictions' => 'number',
            );

            $content = $exportManager->exportArrayToCSV($data, $exportConfig);

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function mostPopularScoresThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $data = $statsManager->getMostPopularScoresThisSeasonData();

            return array('data' => $data);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    private function getFileName($functionName) {
        return substr($functionName, 6, strlen($functionName) - 12);
    }

    private function exportAction($content, $functionName) {

        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/csv; charset=utf-8');
        $fileName = $this->getFileName($functionName);
        $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"$fileName.csv\"");
        $headers->addHeaderLine('Accept-Ranges', 'bytes');
        $headers->addHeaderLine('Content-Length', strlen($content));

        $response->setContent($content);

        return $response;

    }

}