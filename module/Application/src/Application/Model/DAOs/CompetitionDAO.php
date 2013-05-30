<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CompetitionDAO extends AbstractDAO
{

    /**
     * @var CompetitionDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CompetitionDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new CompetitionDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    public function getRepositoryName()
    {
        return '\Application\Model\Entities\Competition';
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllCompetitions($hydrate = false, $skipCache = false)
    {
        return parent::findAll($hydrate, $skipCache);
    }

    /**
     * @param array $fields
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllCompetitionsByFields(array $fields, $hydrate = false, $skipCache = false)
    {
        return parent::findAllByFields($fields, $hydrate, $skipCache);
    }

}
