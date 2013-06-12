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

    /**
     * @param int $leagueId
     * @param int $top
     * @return array
     */
    public function getLeagueTop($leagueId, $top) {
        $query = $this->getEntityManager()->createQuery('
            SELECT
                lu.points, lu.accuracy, lu.place, lu.previousPlace, u.displayName, c.flagImage, c.name as country, u.id as userId
            FROM
               '.$this->getRepositoryName().' as lu
            INNER JOIN lu.user u
            INNER JOIN u.country c
            WHERE lu.league = ' . $leagueId . '
            ORDER BY lu.place ASC
        ')->setMaxResults($top);
        return $query->getArrayResult();
    }

}
