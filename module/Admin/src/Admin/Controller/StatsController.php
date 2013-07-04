<?php

namespace Admin\Controller;

use \Application\Manager\StatsManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class StatsController extends AbstractActionController {
    
    public function usersAction() {

        return array();

    }

    public function predictionsAction() {

        return array();

    }

    public function exportFacebookVsDirectAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getFacebookVsDirectContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportRegistrationsPerWeekAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getRegistrationsPerWeekContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportActiveVsInactiveAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getActiveVsInactiveContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportIncompleteRegistrationsAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getIncompleteRegistrationsContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportAccountDeletionsAction() {

        try {
            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getAccountDeletions();
            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportUsersByRegionAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getUsersByRegionContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-users');
        }

    }

    public function exportPredictionsThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getPredictionsThisSeasonContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportPredictionsPerMatchOverTimeAction() {

        try {
            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getPredictionsPerDayWhileSeason();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportAvgPredictionsPerMatchThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getAvgPredictionsPerMatchThisSeasonContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportHighestPredictedMatchAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getHighestPredictedMatchContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportLowestPredictedMatchAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getLowestPredictedMatchContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportMostPopularScorersThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getMostPopularScorersThisSeasonContent();

            return $this->exportAction($content, __FUNCTION__);

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute('admin-stats-predictions');
        }

    }

    public function exportMostPopularScoresThisSeasonAction() {

        try {

            $statsManager = StatsManager::getInstance($this->getServiceLocator());
            $content = $statsManager->getMostPopularScoresThisSeasonContent();

            return $this->exportAction($content, __FUNCTION__);

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