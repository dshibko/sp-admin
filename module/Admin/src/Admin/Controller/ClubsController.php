<?php

namespace Admin\Controller;

use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\TeamManager;
use \Admin\Form\ClubForm;
use \Application\Manager\ImageManager;

class ClubsController extends AbstractActionController
{
    const CLUBS_LIST_ROUTE = 'admin-clubs';

    public function indexAction()
    {

        $teamManager = TeamManager::getInstance($this->getServiceLocator());
        try {
            $clubs = $teamManager->getAllTeams(true);
        } catch (\Exception $e) {
            $clubs = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'clubs' => $clubs,
        );
    }


    public function editAction()
    {
        $clubId = (string)$this->params()->fromRoute('club', '');
        $isBlocked = false;
        if (empty($clubId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_CLUB_ID);
            return $this->redirect()->toRoute(self::CLUBS_LIST_ROUTE);
        }
        $params = array();
        $form = new ClubForm();
        $teamManager = TeamManager::getInstance($this->serviceLocator);
        try {
            $club = $teamManager->getTeamById($clubId);
            if (null === $club) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_CLUB);
                return $this->redirect()->toRoute(self::CLUBS_LIST_ROUTE);
            }
            $params = array(
                'club' => $club->getId(),
                'action' => 'edit'
            );
            $isBlocked = (bool)$club->getIsBlocked();
            $request = $this->getRequest();
            if ($request->isPost()){
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()){
                    try {
                        $imageManager = ImageManager::getInstance($this->getServiceLocator());

                        $logoValue = $form->get('logoPath')->getValue();
                        if (!array_key_exists('stored', $logoValue) || $logoValue['stored'] == 0) {
                            $logoPath = $imageManager->saveUploadedImage($form->get('logoPath'), ImageManager::IMAGE_TYPE_CLUB);
                            $imageManager->resizeImage($logoPath, ImageManager::CLUB_LOGO_SIZE, ImageManager::CLUB_LOGO_SIZE);
                            $club->setLogoPath($logoPath);
                        }
                        $data = $form->getData();
                        //Check changed data
                        if (!$club->getIsBlocked()){
                            if ($club->getDisplayName() != $data['displayName'] || $club->getShortName() != $data['shortName']){
                                $club->setIsBlocked(true);
                            }
                        }

                        $club->setDisplayName($data['displayName'])
                             ->setShortName($data['shortName']);
                        $teamManager->save($club);
                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_CLUB_SAVED);
                        return $this->redirect()->toRoute(self::CLUBS_LIST_ROUTE);
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                }else{
                    foreach ($form->getMessages() as $el => $messages) {
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " . (is_array($messages) ? implode(", ", $messages) : $messages));
                    }
                }
            }

            $form->populateValues($club->getArrayCopy());

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'form' => $form,
            'params' => $params,
            'title' => 'Edit Club',
            'isBlocked' => $isBlocked
        );
    }

    public function syncWithFeedAction()
    {
        $clubId = (string)$this->params()->fromRoute('club', '');
        if (empty($clubId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_CLUB_ID);
            return $this->redirect()->toRoute(self::CLUBS_LIST_ROUTE);
        }
        $teamManager = TeamManager::getInstance($this->serviceLocator);
        try {
            $club = $teamManager->getTeamById($clubId);
            if (null === $club) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_CLUB);
                return $this->redirect()->toRoute(self::CLUBS_LIST_ROUTE);
            }
            $club->setIsBlocked(false);
            $teamManager->save($club);
            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_SYNC_WITH_FEED);
            return $this->redirect()->toRoute(self::CLUBS_LIST_ROUTE);
        } catch (\Exception $e) {
            $clubs = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'clubs' => $clubs,
        );
    }

}
