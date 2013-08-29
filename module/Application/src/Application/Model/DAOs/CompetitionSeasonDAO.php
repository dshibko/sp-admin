<?php

namespace Application\Model\DAOs;

use Application\Model\Entities\CompetitionSeason;
use \Doctrine\ORM\Query\ResultSetMapping;
use \Application\Model\Entities\League;
use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class CompetitionSeasonDAO extends AbstractDAO {

    /**
     * @var CompetitionSeasonDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CompetitionSeasonDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new CompetitionSeasonDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\CompetitionSeason';
    }

    /**
     * @param $competitionId
     * @param $seasonId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return CompetitionSeason|array
     */
    function getCompetitionSeason($competitionId, $seasonId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('cs')
            ->from($this->getRepositoryName(), 'cs')
            ->where($qb->expr()->eq('cs.competition', $competitionId))
            ->andWhere($qb->expr()->eq('cs.season', $seasonId));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
