<?php

namespace Application\Model\DAOs;

use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;


class PreMatchReportLastSeasonMatchDAO extends AbstractDAO {

    /**
     * @var PreMatchReportLastSeasonMatchDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PreMatchReportLastSeasonMatchDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PreMatchReportLastSeasonMatchDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\PreMatchReportLastSeasonMatch';
    }

    /**
     * @param $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    function getPreMatchReportLastSeasonResultByMatchId($matchId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('lsm')
            ->from($this->getRepositoryName(), 'lsm')
            ->where($qb->expr()->eq('lsm.match', ':matchId'))
            ->setParameter("matchId", $matchId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}