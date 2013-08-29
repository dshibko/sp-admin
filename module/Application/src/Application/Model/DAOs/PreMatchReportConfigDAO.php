<?php

namespace Application\Model\DAOs;

use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;


class PreMatchReportConfigDAO extends AbstractDAO {

    /**
     * @var PreMatchReportConfigDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PreMatchReportConfigDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PreMatchReportConfigDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\PreMatchReportConfig';
    }

    /**
     * @param int $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getConfigByMatchId($matchId, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c.weight, c.displayIndex')
            ->from($this->getRepositoryName(), 'c')
            ->where($qb->expr()->eq('c.match', ':matchId'))
            ->setParameter('matchId', $matchId);
        return $this->getQuery($qb, $skipCache)->getArrayResult();
    }

}