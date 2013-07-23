<?php

namespace Custom\Model\DAOs;

use Application\Model\DAOs\LeagueDAO;
use Application\Model\Entities\League;
use Application\Model\Entities\Season;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class CustomLeagueDAO extends LeagueDAO {

    /**
     * @var CustomLeagueDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CustomLeagueDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new CustomLeagueDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param Season $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\League
     */
    public function getLastMiniLeague($season, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->where($qb->expr()->eq('l.season', ":season"))
            ->andWhere($qb->expr()->lt('l.endDate', ":now"))
            ->andWhere($qb->expr()->eq('l.type', ":miniLeagueType"))
            ->setParameter("season", $season->getId())
            ->setParameter("now", new \DateTime())
            ->setParameter("miniLeagueType", League::MINI_TYPE)
            ->orderBy("l.endDate", "DESC")
            ->setMaxResults(1);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTemporalLeagues($region, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l, lr')
            ->from($this->getRepositoryName(), 'l')
            ->join('l.leagueRegions','lr', Expr\Join::WITH, 'lr.region = ' . $region->getId())
            ->where($qb->expr()->eq('l.type', ':type'))->setParameter('type', League::MINI_TYPE)
            ->andWhere($qb->expr()->lte('l.startDate', ':now'))->setParameter('now', new \DateTime())
            ->orderBy('l.endDate', 'DESC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }
}
