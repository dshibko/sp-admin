<?php

namespace Admin\Controller;

use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\FixtureManager;
use \Application\Manager\ImageManager;
use \Admin\Form\PlayerForm;
use \Application\Manager\SeasonManager;
use \Application\Manager\CompetitionManager;
use \Application\Manager\ApplicationManager;

class FixturesController extends AbstractActionController
{
    const PLAYERS_LIST_ROUTE = 'admin-fixtures';

    public function indexAction()
    {
        $fixtureManager = FixtureManager::getInstance($this->getServiceLocator());
        $seasonsManager = SeasonManager::getInstance($this->getServiceLocator());
        $competitionManager = CompetitionManager::getInstance($this->getServiceLocator());

        try {
            $fixtures = $fixtureManager->getAllFixtures();
            //TODO get only names and ids
            $seasons = $seasonsManager->getAllSeasons(true);
            $currentSeason = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
            $currentSeasonId = (!is_null($currentSeason) && $currentSeason instanceof \Application\Model\Entities\Season) ? $currentSeason->getId() : null;
            $competitions = $competitionManager->getAllCompetitions(true);
        } catch (\Exception $e) {
            $fixtures = array();
            $seasons = array();
            $competitions = array();
            $currentSeasonId = null;
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'fixtures' => $fixtures,
            'seasons' => $seasons,
            'competitions' => $competitions,
            'currentSeasonId' => $currentSeasonId
        );
    }


    public function editAction()
    {
        die('sss');
        /*$playerId = (string)$this->params()->fromRoute('player', '');
        if (empty($playerId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_PLAYER_ID);
            return $this->redirect()->toRoute(self::PLAYERS_LIST_ROUTE);
        }
        $params = array();
        $form = new PlayerForm();
        $playerManager = PlayerManager::getInstance($this->serviceLocator);
        try {
            $player = $playerManager->getPlayerById($playerId);
            if (null === $player) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_PLAYER);
                return $this->redirect()->toRoute(self::PLAYERS_LIST_ROUTE);
            }
            $params = array(
                'player' => $player->getId(),
                'action' => 'edit'
            );
            $isBlocked = (bool)$player->getIsBlocked();
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
                        //TODO resize images
                        //Player Avatar
                        $avatar = $form->get('imagePath')->getValue();
                        if (!array_key_exists('stored', $avatar) || $avatar['stored'] == 0) {
                            $imageManager->deleteImage($player->getImagePath());
                            $avatarPath = $imageManager->saveUploadedImage($form->get('imagePath'), ImageManager::IMAGE_PLAYER_AVATAR);
                           // $imageManager->resizeImage($logoPath, ImageManager::CLUB_LOGO_SIZE, ImageManager::CLUB_LOGO_SIZE);
                            $player->setImagePath($avatarPath);
                        }

                        //Player Background
                        $background = $form->get('backgroundImagePath')->getValue();
                        if (!array_key_exists('stored', $background) || $background['stored'] == 0) {
                            $imageManager->deleteImage($player->getBackgroundImagePath());
                            $backgroundPath = $imageManager->saveUploadedImage($form->get('backgroundImagePath'), ImageManager::IMAGE_PLAYER_BACKGROUND);
                            // $imageManager->resizeImage($logoPath, ImageManager::CLUB_LOGO_SIZE, ImageManager::CLUB_LOGO_SIZE);
                            $player->setBackgroundImagePath($backgroundPath);
                        }
                        $data = $form->getData();
                        //Check changed data  TODO check status
                        if (!$player->getIsBlocked()){
                            if ($player->getDisplayName() != $data['displayName'] || $player->getShirtNumber() != $data['shirtNumber']){
                                $player->setIsBlocked(true);
                            }
                        }
                        $player->setDisplayName($data['displayName'])
                             ->setShirtNumber($data['shirtNumber']);
                        $playerManager->save($player);
                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_PLAYER_SAVED);
                        return $this->redirect()->toUrl($this->url()->fromRoute(self::PLAYERS_LIST_ROUTE,$params));
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    }
                }else{
                    foreach ($form->getMessages() as $el => $messages) {
                        $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " . (is_array($messages) ? implode(", ", $messages) : $messages));
                    }
                }
            }

            $form->populateValues($player->getArrayCopy());

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->redirect()->toUrl($this->url()->fromRoute(self::PLAYERS_LIST_ROUTE,$params));
        }

        return array(
            'form' => $form,
            'params' => $params,
            'title' => 'Edit Player',
            'isBlocked' => $isBlocked
        ); */
    }

    public function addAction()
    {
        die('add');
    }
    public function syncWithFeedAction()
    {
        /*$playerId = (string)$this->params()->fromRoute('player', '');
        if (empty($playerId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_PLAYER_ID);
            return $this->redirect()->toRoute(self::PLAYERS_LIST_ROUTE);
        }
        $params = array();
        $playerManager = PlayerManager::getInstance($this->serviceLocator);
        try {
            $player = $playerManager->getPlayerById($playerId);
            if (null === $player) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_PLAYER);
                return $this->redirect()->toRoute(self::PLAYERS_LIST_ROUTE);
            }
            $player->setIsBlocked(false);
            $playerManager->save($player);
            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_SYNC_WITH_FEED);
            $params = array(
                'action' => 'edit',
                'player' => $player->getId()
            );
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return $this->redirect()->toUrl($this->url()->fromRoute(self::PLAYERS_LIST_ROUTE,$params)); */
    }

}
