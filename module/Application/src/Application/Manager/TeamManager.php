<?php

namespace Application\Manager;

use \Application\Model\DAOs\TeamDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class TeamManager extends BasicManager
{

    /**
     * @var TeamManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return TeamManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new TeamManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }


    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllTeams($hydrate = false, $skipCache = false)
    {
        return TeamDAO::getInstance($this->getServiceLocator())->getAllTeams($hydrate, $skipCache);
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Team
     */
    public function getTeamById($id, $hydrate = false, $skipCache = false)
    {
        return TeamDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    /**
     * @param \Application\Model\Entities\Team $team
     */
    public function save(\Application\Model\Entities\Team $team)
    {
        TeamDAO::getInstance($this->getServiceLocator())->save($team);
    }

}