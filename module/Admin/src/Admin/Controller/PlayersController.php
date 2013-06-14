<?php

namespace Admin\Controller;

use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\PlayerManager;
use \Application\Manager\ImageManager;
use \Admin\Form\PlayerForm;

class PlayersController extends AbstractActionController
{
    const PLAYERS_LIST_ROUTE = 'admin-players';

    public function indexAction()
    {

        $playerManager = PlayerManager::getInstance($this->getServiceLocator());
        try {
            $players = $playerManager->getAllPlayers(true);
        } catch (\Exception $e) {
            $players = array();
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'players' => $players
        );
    }


    public function editAction()
    {

        $playerId = (string)$this->params()->fromRoute('player', '');
        if (empty($playerId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_PLAYER_ID);
            return $this->redirect()->toRoute(self::PLAYERS_LIST_ROUTE);
        }
        $params = array();
        $form = new PlayerForm();
        $isBlocked = false;
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
                            $imageManager->resizeImage($avatarPath, ImageManager::PLAYER_FOREGROUND_WIDTH, ImageManager::PLAYER_FOREGROUND_HEIGHT);
                            $player->setImagePath($avatarPath);
                        }

                        //Player Background
                        $background = $form->get('backgroundImagePath')->getValue();
                        if (!array_key_exists('stored', $background) || $background['stored'] == 0) {
                            $imageManager->deleteImage($player->getBackgroundImagePath());

                            $backgroundPath = $imageManager->saveUploadedImage($form->get('backgroundImagePath'), ImageManager::IMAGE_PLAYER_BACKGROUND);
                            $imageManager->resizeImage($backgroundPath, ImageManager::PLAYER_BACKGROUND_WIDTH, ImageManager::PLAYER_BACKGROUND_HEIGHT);
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
        }

        return array(
            'form' => $form,
            'params' => $params,
            'title' => 'Edit Player',
            'isBlocked' => $isBlocked
        );
    }

    public function syncWithFeedAction()
    {
        $playerId = (string)$this->params()->fromRoute('player', '');
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

        return $this->redirect()->toUrl($this->url()->fromRoute(self::PLAYERS_LIST_ROUTE,$params));
    }

}
