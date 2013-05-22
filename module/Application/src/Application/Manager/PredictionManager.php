<?php

namespace Application\Manager;

use \Application\Model\DAOs\PlayerDAO;
use \Application\Model\Entities\PredictionPlayer;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\DAOs\MatchDAO;
use \Application\Model\Entities\Prediction;
use \Application\Model\DAOs\PredictionDAO;
use \Application\Model\DAOs\RegionDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class PredictionManager extends BasicManager {

    /**
     * @var PredictionManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PredictionManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PredictionManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param integer $matchId
     * @param \Application\Model\Entities\User $user
     * @param integer $homeTeamScore
     * @param integer $awayTeamScore
     * @param array $scoresData
     * @throws \Exception
     */
    public function predict($matchId, $user, $homeTeamScore, $awayTeamScore, $scoresData) {
        $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
        $match = $matchDAO->findOneById($matchId);

        if ($match != null) {

            $predictionDAO = PredictionDAO::getInstance($this->getServiceLocator());
            $playerDAO = PlayerDAO::getInstance($this->getServiceLocator());

            $prediction = $predictionDAO->getUserPrediction($match, $user);
            if ($prediction == null) {
                $prediction = new Prediction();
                $prediction->setMatch($match);
                $prediction->setUser($user);
                $prediction->setCreationDate(new \DateTime());
            } else
                $prediction->clearPredictionPlayers();

            $prediction->setLastUpdateDate(new \DateTime());
            $prediction->setHomeTeamScore($homeTeamScore);
            $prediction->setAwayTeamScore($awayTeamScore);

            foreach ($scoresData as $scoreRow) {
                $predictionScore = new PredictionPlayer();
                $predictionScore->setPrediction($prediction);
                $team = null;
                if (array_key_exists('side', $scoreRow))  {
                    $side = $scoreRow['side'];
                    if ($side == 'home')
                        $team = $match->getHomeTeam();
                    else if ($side == 'away')
                        $team = $match->getAwayTeam();
                }
                if ($team == null)
                    throw new \Exception(MessagesConstants::ERROR_TEAM_NOT_FOUND);
                $predictionScore->setTeam($team);
                $playerId = $scoreRow['scorer'];
                $player = $playerDAO->findOneById($playerId);
                if ($player == null)
                    throw new \Exception(MessagesConstants::ERROR_PLAYER_NOT_FOUND);
                $predictionScore->setPlayer($player);
                $predictionScore->setOrder($scoreRow['order']);
                $prediction->addPredictionPlayer($predictionScore);
            }

            $predictionDAO->save($prediction);

        } else
            throw new \Exception(MessagesConstants::ERROR_MATCH_NOT_FOUND);

    }

    public function getAvgNumberOfPrediction($season) {
        $avgNumberOfPrediction = PredictionDAO::getInstance($this->getServiceLocator())->getAvgNumberOfPrediction($season);
        $avgNumberOfPrediction = number_format(ceil($avgNumberOfPrediction * 100) / 100, 2);
        return $avgNumberOfPrediction;
    }

}