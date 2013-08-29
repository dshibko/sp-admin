<?php

namespace Application\Model\DAOs;

use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;


class PreMatchReportGoalsScoredDAO extends AbstractDAO {

    /**
     * @var PreMatchReportGoalsScoredDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PreMatchReportGoalsScoredDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PreMatchReportGoalsScoredDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\PreMatchReportGoalsScored';
    }

    /**
     * @param $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    function getPreMatchReportGoalsScoredByMatchId($matchId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ags')
            ->from($this->getRepositoryName(), 'ags')
            ->where($qb->expr()->eq('ags.match', ':matchId'))
            ->setParameter("matchId", $matchId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}