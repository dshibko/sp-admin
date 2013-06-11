<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchRegionDAO extends AbstractDAO {

    /**
     * @var MatchRegionDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return MatchRegionDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new MatchRegionDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\MatchRegion';
    }

    /**
     * @param $matchId
     * @param $regionId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\MatchRegion
     */
    public function getMatchRegionByMatchIdAndRegionId($matchId, $regionId, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('mr, m, p, g, pr')
            ->from($this->getRepositoryName(), 'mr')
            ->join('mr.match','m')
            ->leftJoin('mr.featuredPlayer', 'p')
            ->leftJoin('mr.featuredGoalKeeper', 'g')
            ->leftJoin('mr.featuredPrediction', 'pr')
            ->where($qb->expr()->eq('m',':matchId'))->setParameter('matchId', $matchId)
            ->andWhere($qb->expr()->eq('mr.region',':regionId'))->setParameter('regionId', $regionId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}