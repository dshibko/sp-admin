<?php

namespace Application\Model\DAOs;

use Application\Model\Entities\Season;
use \Doctrine\ORM\Query\ResultSetMapping;
use \Application\Model\Entities\League;
use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class LeagueDAO extends AbstractDAO {

    /**
     * @var LeagueDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LeagueDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LeagueDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\League';
    }

    /**
     * @param \Application\Model\Entities\League $league
     * @return array
     */
    public function getLeagueUsersScores($league) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('place', 'place');
        $rsm->addScalarResult('registration_date', 'registration_date');
        $rsm->addScalarResult('user_id', 'user_id');
        $rsm->addScalarResult('predictions_count', 'predictions_count');
        $rsm->addScalarResult('predictions_players_count', 'predictions_players_count');
        $rsm->addScalarResult('points', 'points');
        $rsm->addScalarResult('accuracy', 'accuracy');
        $rsm->addScalarResult('correct_results', 'correct_results');
        $rsm->addScalarResult('correct_scores', 'correct_scores');
        $rsm->addScalarResult('correct_scorers', 'correct_scorers');
        $rsm->addScalarResult('correct_scorers_order', 'correct_scorers_order');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT lu.id, lu.place, lu.registration_date, lu.user_id,
                lu.predictions_count,
                lu.predictions_players_count,
                lu.points,
                lu.accuracy,
                lu.correct_results,
                lu.correct_scores,
                lu.correct_scorers,
                lu.correct_scorers_order
                FROM league_user lu
                WHERE lu.league_id = ' . $league->getId() . '
          ', $rsm);
        return $query->getArrayResult();
    }

    /**
     * @param \Application\Model\Entities\Season $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getGlobalLeague($season, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->where($qb->expr()->eq('l.type', ':type'))->setParameter('type', League::GLOBAL_TYPE)
            ->andWhere($qb->expr()->eq('l.season', $season->getId()));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getGlobalLeagues($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->where($qb->expr()->eq('l.type', ':type'))->setParameter('type', League::GLOBAL_TYPE);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \Application\Model\Entities\League $league
     * @param \Application\Model\Entities\User $user
     * @param bool $skipCache
     * @return bool
     */
    public function getIsUserInLeague($league, $user, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('l.id'))
            ->from($this->getRepositoryName(), 'l')
            ->join('l.leagueUsers', 'lu')
            ->where($qb->expr()->eq('l.id', $league->getId()))
            ->andWhere($qb->expr()->eq('lu.user', $user->getId()));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult() > 0;
    }

    /**
     * @param \Application\Model\Entities\Season $season
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getRegionalLeague($season, $region, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->join('l.regions','r')
            ->where($qb->expr()->eq('l.type', ':type'))->setParameter('type', League::REGIONAL_TYPE)
            ->andWhere($qb->expr()->eq('r.id', $region->getId()))
            ->andWhere($qb->expr()->eq('l.season', $season->getId()));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getRegionalLeagues($region, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->join('l.regions','r')
            ->where($qb->expr()->eq('l.type', ':type'))->setParameter('type', League::REGIONAL_TYPE)
            ->andWhere($qb->expr()->eq('r.id', $region->getId()));
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param int $userId
     * @param int $seasonId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getPrivateLeagues($userId, $seasonId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->join('l.leagueUsers','lu', Expr\Join::WITH, 'lu.user = ' . $userId)
            ->where($qb->expr()->eq('l.type', ':type'))->setParameter('type', League::PRIVATE_TYPE)
            ->andWhere($qb->expr()->lte('l.startDate', ':today'))->setParameter('today', $today)
            ->andWhere($qb->expr()->eq('l.season', ':seasonId'))->setParameter('seasonId', $seasonId)
            ->orderBy('l.endDate', 'DESC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param int $seasonId
     * @param bool $skipCache
     * @return array
     */
    public function getPrivateLeaguesCount($seasonId, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('l.id'))
            ->from($this->getRepositoryName(), 'l')
            ->where($qb->expr()->eq('l.type', ':type'))->setParameter('type', League::PRIVATE_TYPE)
            ->andWhere($qb->expr()->eq('l.season', ':seasonId'))->setParameter('seasonId', $seasonId);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param int $seasonId
     * @param bool $skipCache
     * @return array
     */
    public function getPrivateLeaguesUsersCount($seasonId, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('distinct lu.user'))
            ->from($this->getRepositoryName(), 'l')
            ->join('l.leagueUsers', 'lu')
            ->where($qb->expr()->eq('l.type', ':type'))->setParameter('type', League::PRIVATE_TYPE)
            ->andWhere($qb->expr()->eq('l.season', ':seasonId'))->setParameter('seasonId', $seasonId);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param Season $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTemporalLeagues($region, $season, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $nowTime = new \DateTime();
        $nowTime->setTime(0, 0, 0);
        $qb->select('l, lr')
            ->from($this->getRepositoryName(), 'l')
            ->join('l.leagueRegions','lr', Expr\Join::WITH, 'lr.region = ' . $region->getId())
            ->where($qb->expr()->eq('l.type', ':type'))->setParameter('type', League::MINI_TYPE)
            ->andWhere($qb->expr()->lte('l.startDate', ':now'))->setParameter('now', $nowTime)
            ->andWhere($qb->expr()->gte('l.endDate', ':now'))->setParameter('now', $nowTime)
            ->andWhere($qb->expr()->eq('l.season', ':seasonId'))->setParameter('seasonId', $season->getId())
            ->orderBy('l.endDate', 'DESC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    public function getAllLeagues($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l.id, l.type, l.displayName, l.startDate, l.endDate, s.displayName as seasonName, s.id as seasonId, lr.regionId')
            ->from($this->getRepositoryName(), 'l')
            ->join('l.season', 's')
            ->leftJoin('l.leagueRegions', 'lr')
            ->groupBy('l.id')
            ->orderBy('l.startDate', 'DESC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param int $seasonId
     * @return bool
     */
    public function checkLeagueDatesInterval($startDate, $endDate, $seasonId) {
        $startDate->setTime(0, 0, 0);
        $endDate->setTime(0, 0, 0);
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('l.id'))
            ->from($this->getRepositoryName(), 'l')
            ->where($qb->expr()->andX(
            $qb->expr()->andX($qb->expr()->lte('l.startDate', ":startDate"), $qb->expr()->gte('l.endDate', ":startDate")),
            $qb->expr()->andX($qb->expr()->lte('l.startDate', ":endDate"), $qb->expr()->gte('l.endDate', ":endDate"))))
            ->andWhere($qb->expr()->eq('l.season', $seasonId))
            ->setParameter("startDate", $startDate)
            ->setParameter("endDate", $endDate);
        return $this->getQuery($qb, false)->getSingleScalarResult() > 0;
    }

    public function findOneById($id, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l, lr, ll, s')
            ->from($this->getRepositoryName(), 'l')
            ->leftJoin('l.leagueRegions', 'lr')
            ->leftJoin('l.leagueLanguages', 'll')
            ->join('l.season', 's')
            ->where($qb->expr()->eq('l.id', $id));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    public function getPrivateLeagueByHash($hash, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->join('l.privateLeague', 'pl')
            ->where($qb->expr()->eq('pl.uniqueHash', ':hash'))->setParameter('hash', $hash);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param int $userId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getLeaguesCreatedByUser($userId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->andWhere($qb->expr()->eq('l.creator', ':creator'))->setParameter('creator', $userId);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }
}
