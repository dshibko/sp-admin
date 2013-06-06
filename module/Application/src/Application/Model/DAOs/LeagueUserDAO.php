<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class LeagueUserDAO extends AbstractDAO {

    /**
     * @var LeagueUserDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LeagueUserDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LeagueUserDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\LeagueUser';
    }

    /**
     * @param \Application\Model\Entities\User $user
     * @param \Application\Model\Entities\Season $season
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getUserLeagues($user, $season, $region, $hydrate = false, $skipCache = false) {

        // TODO add custom qb with left join to get name, add time conditions
        $nowTime = new \DateTime();
        $nowTime->setTime(0, 0, 0);
        $query = $this->getEntityManager()->createQuery('
            SELECT
                lu.points, lu.accuracy, lu.place, lu.previousPlace, l.type, lr.displayName
            FROM
               '.$this->getRepositoryName().' as lu
            INNER JOIN lu.league l WITH l.season = ' . $season->getId() . '
            LEFT JOIN l.leagueRegions lr WITH lr.region = ' . $region->getId() . '
            WHERE lu.user = ' . $user->getId() . ' AND
                :nowTime >= l.startDate AND :nowTime <= l.endDate
        ')->setParameter('nowTime', $nowTime);
        return $query->getArrayResult();

//        $qb->select('lu.points, lu.accuracy, lu.place, lu.previousPlace, l.type')
//            ->from($this->getRepositoryName(), 'lu')
//            ->join('lu.league', 'l', Expr\Join::WITH, 'l.season = ' . $season->getId())
//            ->where($qb->expr()->eq('lu.user', $user->getId()));
//        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
