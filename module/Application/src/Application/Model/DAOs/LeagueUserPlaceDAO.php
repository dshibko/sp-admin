<?php

namespace Application\Model\DAOs;

use Application\Model\Entities\League;
use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class LeagueUserPlaceDAO extends AbstractDAO {

    /**
     * @var LeagueUserPlaceDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LeagueUserPlaceDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LeagueUserPlaceDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return 'Application\Model\Entities\LeagueUserPlace';
    }

    /**
     * @param $leagueUserId
     * @param $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getLeagueUserPlace($leagueUserId, $matchId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('lup')
            ->from($this->getRepositoryName(), 'lup')
            ->where($qb->expr()->eq('lup.leagueUser', $leagueUserId))
            ->andWhere($qb->expr()->eq('lup.match', $matchId));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
