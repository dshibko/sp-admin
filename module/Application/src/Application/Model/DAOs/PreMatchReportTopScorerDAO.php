<?php

namespace Application\Model\DAOs;

use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;


class PreMatchReportTopScorerDAO extends AbstractDAO {

    /**
     * @var PreMatchReportTopScorerDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PreMatchReportTopScorerDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PreMatchReportTopScorerDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\PreMatchReportTopScorer';
    }

    /**
     * @param $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    function getPreMatchReportTopScorersMatchId($matchId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ts', 'p', 't')
            ->from($this->getRepositoryName(), 'ts')
            ->join('ts.player', 'p')
            ->join('ts.team', 't')
            ->where($qb->expr()->eq('ts.match', ':matchId'))
            ->orderBy('ts.place', 'ASC')
            ->setParameter("matchId", $matchId);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}