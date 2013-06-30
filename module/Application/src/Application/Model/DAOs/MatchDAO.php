<?php

namespace Application\Model\DAOs;

use \Application\Model\Entities\User;
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
            ->where($qb->expr()->orX($qb->expr()->eq('m.status', ":status1"), $qb->expr()->eq('m.status', ":status2")))
            ->setParameter("status1", Match::PRE_MATCH_STATUS)
            ->setParameter("status2", Match::LIVE_STATUS)
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
            ->where($qb->expr()->eq('m.status', ":status"))
            ->setParameter("status", Match::FULL_TIME_STATUS)
            ->orderBy("m.startTime", "DESC")
            ->setMaxResults(1);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param integer $offset
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return integer
     */
    function getNearestMatch($offset, Season $season, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m.id as matchId, c.id as competitionId, c.displayName as competitionName')
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->join('m.homeTeam', 'h')
            ->where($qb->expr()->orX($qb->expr()->eq('m.status', ':status1'), $qb->expr()->eq('m.status', ':status2')))
            ->orderBy('m.startTime', 'ASC')
            ->addOrderBy('h.displayName', 'ASC')
            ->setParameter("status1", Match::PRE_MATCH_STATUS)
            ->setParameter("status2", Match::LIVE_STATUS)
            ->setFirstResult($offset)
            ->setMaxResults(1);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    /**
     * @param integer $offset
     * @param \Application\Model\Entities\User $user
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return integer
     */
    function getLastMatch($offset, User $user, Season $season, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m.id as matchId, c.id as competitionId, c.displayName as competitionName')
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->innerJoin('m.predictions', 'p', Expr\Join::WITH, 'p.user = ' . $user->getId())
            ->join('m.homeTeam', 'h')
            ->where($qb->expr()->eq('m.status', ':status'))
            ->orderBy('m.startTime', 'DESC')
            ->addOrderBy('h.displayName', 'DESC')
            ->setParameter("status", Match::FULL_TIME_STATUS)
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
     * @param \Application\Model\Entities\User $user
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return integer
     */
    function getFinishedMatchesInTheSeasonNumber(User $user, Season $season, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('m.id'))
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->innerJoin('m.predictions', 'p', Expr\Join::WITH, 'p.user = ' . $user->getId())
            ->where($qb->expr()->eq('m.status', ':status'))
            ->setParameter("status", Match::FULL_TIME_STATUS);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return integer
     */
    function getBlockedFinishedMatchesInTheSeasonNumber(Season $season, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('m.id'))
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->where($qb->expr()->eq('m.status', ':status'))
            ->andWhere($qb->expr()->eq('m.isBlocked', 1))
            ->setParameter("status", Match::FULL_TIME_STATUS);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param \Application\Model\Entities\User $user
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return integer
     */
    function getFinishedNotViewedMatchesInTheSeasonNumber(User $user, Season $season, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('m.id'))
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->innerJoin('m.predictions', 'p', Expr\Join::WITH, 'p.user = ' . $user->getId())
            ->andWhere($qb->expr()->eq('m.status', ':status'))
            ->andWhere($qb->expr()->eq('p.wasViewed', 0))
            ->setParameter("status", Match::FULL_TIME_STATUS);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param \Application\Model\Entities\Season $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    function getMatchesLeftInTheSeason(Season $season, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('m.id, m.feederId, m.status, m.startTime, m.timezone, h.displayName as homeName, h.logoPath as homeLogo, a.displayName as awayName, a.logoPath as awayLogo, c.displayName as competitionName')
            ->from($this->getRepositoryName(), 'm')
            ->join('m.homeTeam', 'h')
            ->join('m.awayTeam', 'a')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->where($qb->expr()->orX($qb->expr()->eq('m.status', ':status1'), $qb->expr()->eq('m.status', ':status2')))
            ->setParameter("status1", Match::PRE_MATCH_STATUS)
            ->setParameter("status2", Match::LIVE_STATUS)
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
        $qb->select('m.id, m.status, m.startTime, m.timezone, m.isDoublePoints, m.hasLineUp, h.id as homeId, h.displayName as homeName,
            h.logoPath as homeLogo, a.id as awayId, a.displayName as awayName, a.logoPath as awayLogo,
            c.displayName as competitionName, c.id as competitionId, m.homeTeamFullTimeScore, m.awayTeamFullTimeScore')
            ->from($this->getRepositoryName(), 'm')
            ->join('m.homeTeam', 'h')
            ->join('m.awayTeam', 'a')
            ->join('m.competition', 'c')
            ->where($qb->expr()->eq('m.id', $matchId));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param integer $matchId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return Match|array
     */
    function getMatchGoals($matchId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('mg.order, t.id teamId, p.id as playerId, p.displayName')
            ->from('\Application\Model\Entities\MatchGoal', 'mg')
            ->join('mg.player', 'p')
            ->join('mg.team', 't')
            ->where($qb->expr()->eq('mg.match', $matchId));
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
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
                m.status,
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

    function getMatchTeamSquad($matchId, $teamId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p.displayName, p.position, p.shirtNumber, p.id, lup.isStart')
            ->from('\Application\Model\Entities\LineUpPlayer', 'lup')
            ->join('lup.player', 'p')
            ->where($qb->expr()->eq('lup.team', $teamId))
            ->andWhere($qb->expr()->eq('lup.match', $matchId));
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \DateTime $fromTime
     * @param \DateTime $tillTime
     * @param \Application\Model\Entities\Season $season
     * @param bool $skipCache
     * @return integer
     */
    function getUpcomingMatchNumber(\DateTime $fromTime, \DateTime $tillTime, Season $season, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('m.id'))
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->join('m.homeTeam', 'h')
            ->where($qb->expr()->orX($qb->expr()->andX($qb->expr()->gt('m.startTime', ":fromTime"), $qb->expr()->lte('m.startTime', ":tillTime"), $qb->expr()->eq('m.status', ':status1')), $qb->expr()->eq('m.status', ':status2')))
            ->orderBy('m.startTime', 'ASC')
            ->addOrderBy('h.displayName', 'ASC')
            ->setParameter("fromTime", $fromTime)
            ->setParameter("tillTime", $tillTime)
            ->setParameter("status1", Match::PRE_MATCH_STATUS)
            ->setParameter("status2", Match::LIVE_STATUS);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param \Application\Model\Entities\User $user
     * @param \Application\Model\Entities\Season $season
     * @param \DateTime $fromTime
     * @param bool $skipCache
     * @return integer
     */
    function getFinishedMatchNumber(User $user, Season $season, \DateTime $fromTime, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('m.id'))
            ->from($this->getRepositoryName(), 'm')
            ->innerJoin('m.competition', 'c', Expr\Join::WITH, 'c.season = ' . $season->getId())
            ->innerJoin('m.predictions', 'p', Expr\Join::WITH, 'p.user = ' . $user->getId())
            ->join('m.homeTeam', 'h')
            ->where($qb->expr()->eq('m.status', ':status'))
            ->andWhere($qb->expr()->gte('m.startTime', ":fromTime"))
            ->orderBy('m.startTime', 'DESC')
            ->addOrderBy('h.displayName', 'DESC')
            ->setParameter("status", Match::FULL_TIME_STATUS)
            ->setParameter("fromTime", $fromTime);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

}
