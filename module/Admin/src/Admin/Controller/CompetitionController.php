<?php

namespace Admin\Controller;

use Admin\Form\CompetitionForm;
use Admin\Form\LeagueLanguageFieldset;
use Admin\Form\SeasonLanguageFieldset;
use Application\Manager\ApplicationManager;
use Application\Manager\CompetitionManager;
use Application\Manager\LanguageManager;
use Application\Manager\MatchManager;
use Application\Model\Entities\League;
use Application\Model\Entities\Region;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\Entities\Season;
use \Application\Manager\ImageManager;
use \Admin\Form\SeasonRegionLanguageFieldset;
use \Application\Manager\RegionManager;
use \Admin\Form\SeasonForm;
use \Application\Manager\SeasonManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CompetitionController extends AbstractActionController {

    const COMPETITIONS_INDEX_ROUTE = 'admin-competitions';

    public function indexAction() {

        $competitions = array();

        try {

            $club = ApplicationManager::getInstance($this->getServiceLocator())->getAppClub();
            $competitions = CompetitionManager::getInstance($this->getServiceLocator())->getAllClubCompetitions($club->getId());

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array(
            'competitions' => $competitions
        ));

    }

    public function editAction() {
        try {
            $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id)
                return $this->redirect()->toRoute(self::COMPETITIONS_INDEX_ROUTE);

            $competitionManager = CompetitionManager::getInstance($this->getServiceLocator());

            $competition = $competitionManager->getCompetitionById($id);
            if ($competition == null)
                return $this->redirect()->toRoute(self::COMPETITIONS_INDEX_ROUTE);

            $form = new CompetitionForm();

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()) {
                    try {

                        $imageManager = ImageManager::getInstance($this->getServiceLocator());
                        $logoPath = $imageManager->saveUploadedImage($form->get('logo'), ImageManager::IMAGE_TYPE_CONTENT);
                        $imageManager->resizeImage($logoPath, ImageManager::COMPETITION_LOGO_SIZE, ImageManager::COMPETITION_LOGO_SIZE);

                        $competition->setLogoPath($logoPath);
                        $competitionManager->updateCompetition($competition);

                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_COMPETITION_UPDATED);
                        return $this->redirect()->toRoute(self::COMPETITIONS_INDEX_ROUTE);

                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                } else
                    foreach ($form->getMessages() as $elementName => $messages)
                        foreach ($messages as $message)
                            $this->flashMessenger()->addErrorMessage($form->get($elementName)->getLabel() . ": " . $message);
            }

            $form->initFormByObject($competition);

            return array(
                'id' => $id,
                'form' => $form,
            );

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toRoute(self::COMPETITIONS_INDEX_ROUTE);
        }
    }

}
