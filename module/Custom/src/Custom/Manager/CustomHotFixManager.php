<?php

namespace Custom\Manager;

use Application\Manager\ApplicationManager;
use Application\Manager\ExportManager;
use Application\Manager\MatchManager;
use Application\Manager\PredictionManager;
use \Application\Model\DAOs\AvatarDAO;
use Application\Model\DAOs\LeagueDAO;
use Application\Model\DAOs\LeagueUserDAO;
use Application\Model\DAOs\LeagueUserPlaceDAO;
use Application\Model\DAOs\PredictionDAO;
use Application\Model\Entities\Prediction;
use Application\Model\Entities\PredictionPlayer;
use Custom\Model\DAOs\CustomLeagueDAO;
use Custom\Model\DAOs\CustomMatchDAO;
use Custom\Model\DAOs\CustomUserDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class CustomHotFixManager extends BasicManager {

    /**
     * @var CustomHotFixManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CustomHotFixManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new CustomHotFixManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function mergePredictions() {
        $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
        $customMatchDAO = CustomMatchDAO::getInstance($this->getServiceLocator());
        $correctMatch = $customMatchDAO->findOneById(5);
        $incorrectMatch = $customMatchDAO->findOneById(6);
        $correctMatchPredictions = $customMatchDAO->getMatchPredictions($correctMatch->getId());
        $correctMatchPredictionsUsersIds = array();
        foreach ($correctMatchPredictions as $correctMatchPrediction)
            $correctMatchPredictionsUsersIds [] = $correctMatchPrediction->getUser()->getId();
        $incorrectMatchPredictions = $customMatchDAO->getMatchPredictions($incorrectMatch->getId(), $correctMatchPredictionsUsersIds);
        foreach($incorrectMatchPredictions as $incorrectMatchPrediction) {
            $prediction = new Prediction();
            $prediction->setMatch($correctMatch);
            $prediction->setHomeTeamScore($incorrectMatchPrediction->getAwayTeamScore());
            $prediction->setAwayTeamScore($incorrectMatchPrediction->getHomeTeamScore());
            $prediction->setLastUpdateDate(new \DateTime());
            $prediction->setCreationDate(new \DateTime());
            $prediction->setUser($incorrectMatchPrediction->getUser());
            foreach ($incorrectMatchPrediction->getPredictionPlayers() as $player) {
                $pp = new PredictionPlayer();
                $pp->setTeam($player->getTeam());
                $pp->setOrder($player->getOrder());
                $pp->setPlayer($player->getPlayer());
                $pp->setPrediction($prediction);
                $prediction->addPredictionPlayer($pp);
            }
            $predictionDAO->save($prediction, false, false);
        }
        $predictionDAO->flush();
    }

    public function fixPrivateLeaguesPredictions($ids) {
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        foreach ($ids as $id) {
            $league = $leagueDAO->findOneById($id);
            $leagueUsers = $league->getLeagueUsers();
            $leagueUserIds = array();
            foreach ($leagueUsers as $leagueUser) {
                if (!in_array($leagueUser->getUser()->getId(), $leagueUserIds))
                    $leagueUserIds [] = $leagueUser->getUser()->getId();
                else {
                    var_dump($leagueUser->getUser()->getId());
                    $fromPlace = $leagueUser->getPlace();
                    if (!empty($fromPlace))
                        $leagueUserDAO->moveUpLeagueUserPlaces($league, $fromPlace);
                    $leagueUserDAO->remove($leagueUser, false, false);
                }
            }

        }
        $leagueUserDAO->flush();
    }

}