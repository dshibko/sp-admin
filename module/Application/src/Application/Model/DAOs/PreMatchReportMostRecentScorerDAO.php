<?php

namespace Application\Model\DAOs;

use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;


class PreMatchReportMostRecentScorerDAO extends AbstractDAO {

    /**
     * @var PreMatchReportMostRecentScorerDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PreMatchReportMostRecentScorerDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PreMatchReportMostRecentScorerDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\PreMatchReportMostRecentScorer';
    }

    /**
     * @param $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    function getPreMatchReportMostRecentScorersByMatchId($matchId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('mrs', 'p', 't')
            ->from($this->getRepositoryName(), 'mrs')
            ->join('mrs.player', 'p')
            ->join('mrs.team', 't')
            ->where($qb->expr()->eq('mrs.match', ':matchId'))
            ->orderBy('mrs.place', 'ASC')
            ->setParameter("matchId", $matchId);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}