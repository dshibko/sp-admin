<?php

namespace Application\Manager;

use \Application\Model\DAOs\PlayerDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class PlayerManager extends BasicManager
{

    const GOALKEEPER_POSITION = 'Goalkeeper';
    const DEFENDER_POSITION = 'Defender';
    const FORWARD_POSITION = 'Forward';
    const MIDFIELDER_POSITION = 'Midfielder';

    /**
     * @var PlayerManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PlayerManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new PlayerManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }


    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllPlayers($hydrate = false, $skipCache = false)
    {
        return PlayerDAO::getInstance($this->getServiceLocator())->getAllPlayers($hydrate, $skipCache);
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Player
     */
    public function getPlayerById($id, $hydrate = false, $skipCache = false)
    {
        return PlayerDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    /**
     * @param \Application\Model\Entities\Player $player
     */
    public function save(\Application\Model\Entities\Player $player)
    {
        PlayerDAO::getInstance($this->getServiceLocator())->save($player);
    }

    /**
     * @param array $players
     * @return array
     */
    public function getPlayersSelectOptions(array $players)
    {
        $options = array();
        if (!empty($players)){
            foreach($players as $player){
                $options[$player['id']] = $player['displayName'];
            }
        }
        return $options;
    }


    /**
     * @param array $positions
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getPlayersByPositions(array $positions, $hydrate = false, $skipCache = false)
    {
        return PlayerDAO::getInstance($this->getServiceLocator())->getPlayersByPositions($positions, $hydrate, $skipCache);
    }

    /**
     * @param array $positions
     * @param array $teamIds
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getPlayersByPositionsFromTeams(array $positions, array $teamIds, $hydrate = false, $skipCache = false)
    {
        return PlayerDAO::getInstance($this->getServiceLocator())->getPlayersByPositionsFromTeams($positions, $teamIds,$hydrate, $skipCache);
    }


}