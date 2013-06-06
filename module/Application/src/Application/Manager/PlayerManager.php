<?php

namespace Application\Manager;

use \Application\Model\DAOs\PlayerDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class PlayerManager extends BasicManager
{

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

    public function getPlayersSelectOptions()
    {
        $options = array();
        $players = $this->getAllPlayers(true);
        if (!empty($players)){
            foreach($players as $player){
                $options[$player['id']] = $player['displayName'];
            }
        }
        return $options;
    }

}