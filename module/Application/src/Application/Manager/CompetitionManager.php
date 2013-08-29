<?php

namespace Application\Manager;

use \Application\Model\DAOs\CompetitionDAO;
use Application\Model\Entities\Competition;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class CompetitionManager extends BasicManager
{

    /**
     * @var CompetitionManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CompetitionManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new CompetitionManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }


    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllCompetitions($hydrate = false, $skipCache = false)
    {
        return CompetitionDAO::getInstance($this->getServiceLocator())->getAllCompetitions($hydrate, $skipCache);
    }

    /**
     * @param array $fields
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllCompetitionsByFields(array $fields, $hydrate = false, $skipCache = false)
    {
        return CompetitionDAO::getInstance($this->getServiceLocator())->getAllCompetitionsByFields($fields, $hydrate, $skipCache);
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return Competition|array
     */
    public function getCompetitionById($id, $hydrate = false, $skipCache = false)
    {
        return CompetitionDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    public function getAllClubCompetitions($clubId, $hydrate = false, $skipCache = false)
    {
        return CompetitionDAO::getInstance($this->getServiceLocator())->getAllClubCompetitions($clubId, $hydrate, $skipCache);
    }

    public function updateCompetition(Competition $competition) {
        $competitionDAO = CompetitionDAO::getInstance($this->getServiceLocator());
        $competitionDAO->save($competition);
    }

}