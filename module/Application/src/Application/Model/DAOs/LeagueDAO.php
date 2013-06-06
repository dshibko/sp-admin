<?php

namespace Application\Model\DAOs;

use \Doctrine\ORM\Query\ResultSetMapping;
use \Application\Model\Entities\League;
use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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
        $rsm->addScalarResult('date', 'date');
        $rsm->addScalarResult('predictions_count', 'predictions_count');
        $rsm->addScalarResult('predictions_players_count', 'predictions_players_count');
        $rsm->addScalarResult('points', 'points');
        $rsm->addScalarResult('correct_results', 'correct_results');
        $rsm->addScalarResult('correct_scores', 'correct_scores');
        $rsm->addScalarResult('correct_scorers', 'correct_scorers');
        $rsm->addScalarResult('correct_scorers_order', 'correct_scorers_order');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT lu.id, u.date,
                COUNT(p.id) as predictions_count,
                IFNULL(SUM(pp.players), 0) as predictions_players_count,
                SUM(p.points) as points,
                SUM(p.is_correct_result) as correct_results,
                SUM(p.is_correct_score) as correct_scores,
                SUM(p.correct_scorers) as correct_scorers,
                SUM(p.correct_scorers_order) as correct_scorers_order
                FROM league_user lu
                INNER JOIN user u ON u.id = lu.user_id
                INNER JOIN league l ON l.id = lu.league_id AND l.id = ' . $league->getId() . '
                INNER JOIN prediction p ON p.user_id = u.id AND p.points is not null
                INNER JOIN `match` m ON m.id = p.match_id
                LEFT OUTER JOIN (SELECT pp.prediction_id, COUNT(pp.id) players FROM prediction_player pp WHERE pp.player_id is not null GROUP BY pp.prediction_id) pp ON pp.prediction_id = p.id
                WHERE DATE(m.start_time) >= l.start_date AND DATE(m.start_time) <= l.end_date
                GROUP BY lu.id
          ', $rsm);
        return $query->getArrayResult();
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

    public function getAllLeagues($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l.id, l.type, l.displayName, l.startDate, l.endDate, s.displayName as seasonName, s.id as seasonId')
            ->from($this->getRepositoryName(), 'l')
            ->join('l.season', 's')
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
        $qb->select($qb->expr()->count('s.id'))
            ->from($this->getRepositoryName(), 's')
            ->where($qb->expr()->andX(
            $qb->expr()->andX($qb->expr()->lte('s.startDate', ":startDate"), $qb->expr()->gte('s.endDate', ":startDate")),
            $qb->expr()->andX($qb->expr()->lte('s.startDate', ":endDate"), $qb->expr()->gte('s.endDate', ":endDate"))))
            ->andWhere($qb->expr()->eq('s.id', $seasonId))
            ->setParameter("startDate", $startDate)
            ->setParameter("endDate", $endDate);
        return $this->getQuery($qb, false)->getSingleScalarResult() > 0;
    }

    public function findOneById($id, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l, lr, p, s')
            ->from($this->getRepositoryName(), 'l')
            ->join('l.leagueRegions', 'lr')
            ->join('l.prizes', 'p')
            ->join('l.season', 's')
            ->where($qb->expr()->eq('l.id', $id));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
