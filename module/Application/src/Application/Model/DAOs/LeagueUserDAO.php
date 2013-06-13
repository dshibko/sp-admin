<?php

namespace Application\Model\DAOs;

use \Application\Model\Entities\League;
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
     * @param \Application\Model\Entities\Region|null $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getUserLeagues($user, $season, $region, $hydrate = false, $skipCache = false) {
        $nowTime = new \DateTime();
        $nowTime->setTime(0, 0, 0);
        $regionId = $region == null ? 0 : $region->getId();
        $query = $this->getEntityManager()->createQuery('
            SELECT
                lu.points, lu.accuracy, lu.place, lu.previousPlace, l.type, lr.displayName
            FROM
               '.$this->getRepositoryName().' as lu
            INNER JOIN lu.league l WITH l.season = ' . $season->getId() . '
            LEFT JOIN l.leagueRegions lr WITH lr.region = ' . $regionId . '
            WHERE lu.user = ' . $user->getId() . ' AND
                :nowTime >= l.startDate AND :nowTime <= l.endDate
        ')->setParameter('nowTime', $nowTime);
        return $query->getArrayResult();
    }

    public function getUserLeaguesByTypes(\Application\Model\Entities\User $user, \Application\Model\Entities\Season $season, \Application\Model\Entities\Region $region, array $types)
    {
        $nowTime = new \DateTime();
        $nowTime->setTime(0, 0, 0);
        $regionId = $region == null ? 0 : $region->getId();
        $query = $this->getEntityManager()->createQuery('
            SELECT
                lu.points,
                lu.accuracy,
                lu.place,
                lu.previousPlace,
                l.type,
                lr.displayName
            FROM
               '.$this->getRepositoryName().' as lu
            INNER JOIN lu.league l WITH l.season = ' . $season->getId() . '
            LEFT JOIN l.leagueRegions lr WITH lr.region = ' . $regionId . '
            WHERE lu.user = ' . $user->getId() . ' AND
                :nowTime >= l.startDate AND :nowTime <= l.endDate AND l.type IN (:types)
        ')->setParameter('nowTime', $nowTime)->setParameter('types', $types);
        return $query->getArrayResult();
    }
    /**
     * @param int $leagueId
     * @param bool $skipCache
     * @return int
     */
    public function getLeagueUsersCount($leagueId, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('lu.id'))
            ->from($this->getRepositoryName(), 'lu')
            ->where($qb->expr()->eq('lu.league', $leagueId));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
//        return $this->getQuery($qb, $skipCache)->getSingleScalarResult() * 7;
    }

    /**
     * @param int $leagueId
     * @param int $top
     * @param int $offset
     * @param array|null $facebookIds
     * @return array
     */
    public function getLeagueTop($leagueId, $top = 0, $offset = 0, $facebookIds = null) {
        $query = $this->getEntityManager()->createQuery('
            SELECT
                lu.points, lu.accuracy, lu.place, lu.previousPlace, u.displayName, c.flagImage, c.name as country, u.id as userId
            FROM
               '.$this->getRepositoryName().' as lu
            INNER JOIN lu.user u
            INNER JOIN u.country c
            WHERE lu.league = ' . $leagueId . ($facebookIds !== null ? ' AND u.facebookId IN (' . implode(",", $facebookIds) . ')' : '') . '
            ORDER BY lu.place ASC
        ');
        if ($top > 0)
            $query->setMaxResults($top);
        if ($offset > 0)
            $query->setFirstResult($offset);
        return $query->getArrayResult();
//        $arr = $query->getArrayResult();
//        $arr = array_merge($arr, $arr, $arr, $arr, $arr, $arr, $arr);
//        $i = 0;
//        foreach ($arr as $k => $v)
//            $arr[$k]['place'] = ++$i;
//        return array_slice($arr, $offset, $top);
    }

    /**
     * @param int $leagueId
     * @param int $userId
     * @return array
     */
    public function getYourPlaceInLeague($leagueId, $userId) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('lu.place')
            ->from($this->getRepositoryName(), 'lu')
            ->where($qb->expr()->eq('lu.league', $leagueId))
            ->andWhere($qb->expr()->eq('lu.user', $userId));
        return $this->getQuery($qb)->getSingleScalarResult();
    }

    /**
     * @param int $leagueId
     * @param int $userId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getLeagueUser($leagueId, $userId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('lu')
            ->from($this->getRepositoryName(), 'lu')
            ->where($qb->expr()->eq('lu.league', $leagueId))
            ->andWhere($qb->expr()->eq('lu.user', $userId));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
