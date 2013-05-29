<?php

namespace Application\Manager;

use \Application\Model\DAOs\MatchDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class FixtureManager extends BasicManager
{

    /**
     * @var FixtureManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return FixtureManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new FixtureManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }


    /**
     * @return array
     */
    public function getAllFixtures()
    {
        return MatchDAO::getInstance($this->getServiceLocator())->getAllFixtures();
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Match
     */
    public function getFixtureById($id, $hydrate = false, $skipCache = false)
    {
        return MatchDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    public function save(\Application\Model\Entities\Match $match)
    {
        MatchDAO::getInstance($this->getServiceLocator())->save($match);
    }

}