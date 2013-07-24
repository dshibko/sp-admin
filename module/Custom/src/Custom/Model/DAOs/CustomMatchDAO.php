<?php

namespace Custom\Model\DAOs;

use Application\Model\DAOs\MatchDAO;
use Application\Model\DAOs\PredictionDAO;
use Application\Model\Entities\Match;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class CustomMatchDAO extends MatchDAO {

    /**
     * @var CustomMatchDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CustomMatchDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new CustomMatchDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Match
     */
    public function getSecondMatch($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m')
            ->from($this->getRepositoryName(), 'm')
            ->where($qb->expr()->orX($qb->expr()->eq('m.status', ":status1"), $qb->expr()->eq('m.status', ":status2")))
            ->setParameter("status1", Match::PRE_MATCH_STATUS)
            ->setParameter("status2", Match::LIVE_STATUS)
            ->orderBy("m.startTime", "ASC")
            ->setFirstResult(1)
            ->setMaxResults(1);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    public function getMatchPredictions($matchId, $excludeUsersIds = null, $hydrate = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from(PredictionDAO::getInstance($this->getServiceLocator())->getRepositoryName(), 'p')
            ->where($qb->expr()->eq('p.match', ":match"))
            ->setParameter("match", $matchId);
        if ($excludeUsersIds !== null)
            $qb->andWhere($qb->expr()->notIn('p.user', ":exclude"))
                ->setParameter("exclude", $excludeUsersIds);
        return $qb->getQuery()->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
