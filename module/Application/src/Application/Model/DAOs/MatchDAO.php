<?php

namespace Application\Model\DAOs;

use \Application\Model\Entities\Match;
use \Application\Model\Entities\Season;
use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class MatchDAO extends AbstractDAO {

    /**
     * @var MatchDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return MatchDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new MatchDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\Match';
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Match
     */
    public function getNextMatch($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m')
            ->from($this->getRepositoryName(), 'm')
            ->where($qb->expr()->gt('m.startTime', ":now"))
            ->setParameter("now", new \DateTime())
            ->orderBy("m.startTime", "ASC")
            ->setMaxResults(1);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Match
     */
    public function getPrevMatch($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m')
            ->from($this->getRepositoryName(), 'm')
            ->where($qb->expr()->lt('m.startTime', ":now"))
            ->setParameter("now", new \DateTime())
            ->orderBy("m.startTime", "DESC")
            ->setMaxResults(1);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \DateTime $fromTime
     * @param integer $offset
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return integer
     */
    function getNearestMatch(\DateTime $fromTime, $offset, Season $season, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m.id as matchId, c.id as competitionId, c.displayName as competitionName')
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->join('m.homeTeam', 'h')
            ->where($qb->expr()->gt('m.startTime', ":fromTime"))
            ->andWhere($qb->expr()->orX($qb->expr()->eq('m.status', ':status1'), $qb->expr()->eq('m.status', ':status2')))
            ->orderBy('m.startTime', 'ASC')
            ->addOrderBy('h.displayName', 'ASC')
            ->setParameter("fromTime", $fromTime)
            ->setParameter("status1", Match::PRE_MATCH_STATUS)
            ->setParameter("status2", Match::LIVE_STATUS)
            ->setFirstResult($offset)
            ->setMaxResults(1);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    /**
     * @param \DateTime $fromTime
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return integer
     */
    function getLiveMatchesNumber(\DateTime $fromTime, Season $season, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('m.id'))
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->where($qb->expr()->andX($qb->expr()->lt('m.startTime', ":fromTime"), $qb->expr()->eq('m.status', ":status1")))
            ->orWhere($qb->expr()->eq('m.status', ':status2'))
            ->setParameter("fromTime", $fromTime)
            ->setParameter("status1", Match::PRE_MATCH_STATUS)
            ->setParameter("status2", Match::LIVE_STATUS);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param \DateTime $fromTime
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return integer
     */
    function getMatchesLeftInTheSeasonNumber(\DateTime $fromTime, Season $season, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('m.id'))
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->where($qb->expr()->gt('m.startTime', ":fromTime"))
            ->andWhere($qb->expr()->eq('m.status', ':status'))
            ->setParameter("fromTime", $fromTime)
            ->setParameter("status", Match::PRE_MATCH_STATUS);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param \DateTime $fromTime
     * @param \Application\Model\Entities\Season $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    function getMatchesLeftInTheSeason(\DateTime $fromTime, Season $season, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m.id, m.status, m.startTime, m.timezone, h.displayName as homeName, h.logoPath as homeLogo, a.displayName as awayName, a.logoPath as awayLogo, c.displayName as competitionName')
            ->from($this->getRepositoryName(), 'm')
            ->join('m.homeTeam', 'h')
            ->join('m.awayTeam', 'a')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->where($qb->expr()->gt('m.startTime', ":fromTime"))
            ->andWhere($qb->expr()->orX($qb->expr()->eq('m.status', ':status1'), $qb->expr()->eq('m.status', ':status2')))
            ->setParameter("status1", Match::PRE_MATCH_STATUS)
            ->setParameter("status2", Match::LIVE_STATUS)
            ->setParameter("fromTime", $fromTime)
            ->orderBy('m.startTime', 'ASC')
            ->addOrderBy('h.displayName', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param integer $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return Match|array
     */
    function getMatchInfo($matchId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m.id, m.status, m.startTime, m.timezone, h.id as homeId, h.displayName as homeName, h.logoPath as homeLogo, a.id as awayId, a.displayName as awayName, a.logoPath as awayLogo, c.displayName as competitionName')
            ->from($this->getRepositoryName(), 'm')
            ->join('m.homeTeam', 'h')
            ->join('m.awayTeam', 'a')
            ->join('m.competition', 'c')
            ->where($qb->expr()->eq('m.id', $matchId));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllMatches() {
        $query = $this->getEntityManager()->createQuery('
            SELECT
                m.id,
                m.startTime as date_and_time,
                c.displayName as competition_name,
                s.displayName as season_name,
                at.displayName as away_team,
                ht.displayName as home_team
            FROM
               '.$this->getRepositoryName().' as m
            INNER JOIN
                    m.competition c
            INNER JOIN
                    c.season s
            INNER JOIN
                    m.awayTeam at
            INNER JOIN
                    m.homeTeam ht
            ORDER BY
                    date_and_time DESC
        ');
        return $query->getArrayResult();
    }


}
