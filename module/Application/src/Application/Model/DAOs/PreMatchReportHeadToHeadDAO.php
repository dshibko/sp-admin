<?php

namespace Application\Model\DAOs;

use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;


class PreMatchReportHeadToHeadDAO extends AbstractDAO {

    /**
     * @var PreMatchReportHeadToHeadDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PreMatchReportHeadToHeadDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PreMatchReportHeadToHeadDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\PreMatchReportHeadToHead';
    }

    /**
     * @param $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    function getPreMatchReportHeadToHeadByMatchId($matchId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('hth')
            ->from($this->getRepositoryName(), 'hth')
            ->where($qb->expr()->eq('hth.match', ':matchId'))
            ->setParameter("matchId", $matchId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}