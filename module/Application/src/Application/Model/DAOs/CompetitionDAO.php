<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
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

    /**
     * @param int $clubId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllClubCompetitions($clubId, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQuery(
            'SELECT c ' .
            'FROM ' . $this->getRepositoryName() . ' c ' .
            'LEFT JOIN c.competitionSeasons cs ' .
            'LEFT JOIN cs.matches m ' .
            'WHERE m.homeTeam = :clubId OR m.awayTeam = :clubId'
        )->setParameter('clubId', $clubId);
        return $this->prepareQuery($qb, array(
            $this->getRepositoryName(),
            CompetitionSeasonDAO::getInstance($this->getServiceLocator())->getRepositoryName(),
            MatchDAO::getInstance($this->getServiceLocator())->getRepositoryName(),
        ), $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
