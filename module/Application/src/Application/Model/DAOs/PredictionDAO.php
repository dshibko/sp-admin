<?php

namespace Application\Model\DAOs;

use \Application\Model\Entities\Season;
use \Doctrine\ORM\Query\ResultSetMapping;
use \Doctrine\ORM\Query\ResultSetMappingBuilder;
use \Application\Model\Entities\Match;
use \Application\Model\Entities\User;
use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class PredictionDAO extends AbstractDAO {

    /**
     * @var PredictionDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PredictionDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PredictionDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\Prediction';
    }

    /**
     * @param $matchId
     * @param $userId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Prediction
     */
    function getUserPrediction($matchId, $userId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p, pl')
            ->from($this->getRepositoryName(), 'p')
            ->join('p.match', 'm', Expr\Join::WITH, 'm.id = ' . $matchId)
            ->join('p.user', 'u', Expr\Join::WITH, 'u.id = ' . $userId)
            ->leftJoin('p.predictionPlayers', 'pl');
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \Application\Model\Entities\Season $season
     * @return integer
     */
    function getAvgNumberOfPredictions($season) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('avg', 'a');
        $query = $this->getEntityManager()
            ->createNativeQuery('SELECT AVG(pr.predictions) as avg FROM (SELECT COUNT(p.id) as predictions
             FROM `match` m
             INNER JOIN competition c ON c.id = m.competition_id AND c.season_id = ' . $season->getId() . '
             LEFT JOIN prediction p ON p.match_id = m.id
             WHERE m.start_time < NOW()
             AND m.status = \'' . Match::FULL_TIME_STATUS . '\'
             GROUP BY m.id) pr
             ', $rsm);
        return $query->getSingleScalarResult();
    }

    /**
     * @param array $predictionIds
     * @param int $limit
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getTopScorers(array $predictionIds, $limit = 5, $hydrate = false, $skipCache = false)
    {
        if (empty($predictionIds)){
            throw new \Exception('Empty prediction ids');
        }
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('
            COUNT(pp.playerId) as scorers_count,
            t.displayName as team_name,
            pl.displayName as player_name
        ');
        $qb->from('\Application\Model\Entities\PredictionPlayer','pp');
        $qb->join('pp.player','pl');
        $qb->join('pl.team','t');
        $qb->where($qb->expr()->in('pp.prediction',':ids'))->setParameter('ids', $predictionIds);
        $qb->andWhere($qb->expr()->isNotNull('pp.playerId'));
        $qb->groupBy('pp.playerId');
        $qb->orderBy('scorers_count','DESC');
        $qb->setMaxResults($limit);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }


    /**
     * @param array $predictionIds
     * @param int $limit
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getTopScores(array $predictionIds, $limit = 5, $hydrate = false, $skipCache = false)
    {
        if (empty($predictionIds)){
            throw new \Exception('Empty prediction ids');
        }
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            p.homeTeamScore as home_team_score,
            p.awayTeamScore as away_team_score,
            count(p.id) as scores
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->in('p.id',':ids'))->setParameter('ids', $predictionIds);
        $qb->groupBy('p.homeTeamScore, p.awayTeamScore');
        $qb->orderBy('scores','DESC');
        $qb->setMaxResults($limit);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param array $predictionIds
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     * @throws \Exception
     */
    public function getUsersCountWithCorrectResult(array $predictionIds, $skipCache = false)
    {
        if (empty($predictionIds)){
            throw new \Exception('Empty prediction ids');
        }
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            COUNT(p.id) as user_count
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->in('p.id',':ids'))->setParameter('ids', $predictionIds);
        $qb->andWhere('p.isCorrectResult = 1');
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param array $predictionIds
     * @param bool $skipCache
     * @return mixed
     * @throws \Exception
     */
    public function getUsersCountWithCorrectScore(array $predictionIds, $skipCache = false)
    {
        if (empty($predictionIds)){
            throw new \Exception('Empty prediction ids');
        }
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            COUNT(p.id) as user_count
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->in('p.id',':ids'))->setParameter('ids', $predictionIds);
        $qb->andWhere('p.isCorrectScore = 1');
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param array $predictionIds
     * @return array
     * @throws \Exception
     */
    public function getUsersWithPerfectResult(array $predictionIds)
    {
        if (empty($predictionIds)){
            throw new \Exception('Empty prediction ids');
        }
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('\Application\Model\Entities\User', 'u');
        $rsm->addEntityResult('\Application\Model\Entities\User', 'u');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                  SELECT
	                  u.id,
	                  u.display_name
                  FROM
	                  prediction as p
                  INNER JOIN
	                  user as u
	              ON
	                  u.id = p.user_id
                  WHERE
                      p.id in ('.implode(',', $predictionIds).')
                  AND
	                  p.is_correct_result = 1
	              AND
		              p.is_correct_score = 1
                  AND
                      p.correct_scorers_order = p.correct_scorers
                  AND
                      p.correct_scorers = (
                            SELECT
								COUNT(pp.id)
							FROM
								prediction_player as pp
							WHERE
								pp.prediction_id = p.id
                  )

            ', $rsm);

        return $query->getArrayResult();
    }

    /**
     * @param array $predictionIds
     * @param int $hoursFromNow
     * @return array
     * @throws \Exception
     */
    public function getNumberOfPredictionsPerHour(array $predictionIds, $hoursFromNow = 12)
    {
        if (empty($predictionIds)){
            throw new \Exception('Empty prediction ids');
        }
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('number','number');
        $rsm->addScalarResult('date','date');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                  SELECT
                      count(id) as number,
                      creation_date as date
                  FROM
                      prediction
                  WHERE
                      id in ('.implode(',', $predictionIds).')
                  AND
                      creation_date > DATE_SUB(NOW(), INTERVAL '.$hoursFromNow.' HOUR)
                  GROUP BY
                      HOUR(creation_date)
            ', $rsm);
        return $query->getArrayResult();
    }

    /**
     * @param array $predictionIds
     * @param bool $skipCache
     * @return mixed
     * @throws \Exception
     */
    public function getPredictionPlayersCount(array $predictionIds, $skipCache = false)
    {
        if (empty($predictionIds)){
            throw new \Exception('Empty prediction ids');
        }

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            COUNT(pp.playerId) as players_count
        ');
        $qb->from('\Application\Model\Entities\PredictionPlayer','pp');
        $qb->where($qb->expr()->in('pp.prediction',':ids'))->setParameter('ids', $predictionIds);
        $qb->andWhere($qb->expr()->isNotNull('pp.playerId'));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param array $predictionIds
     * @param bool $skipCache
     * @return mixed
     * @throws \Exception
     */
    public function getPredictionCorrectScorersSum(array $predictionIds, $skipCache = false)
    {
        if (empty($predictionIds)){
            throw new \Exception('Empty prediction ids');
        }

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            SUM(p.correctScorers) as correct_scorers
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->in('p.id',':ids'))->setParameter('ids', $predictionIds);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param array $predictionIds
     * @param bool $skipCache
     * @return mixed
     * @throws \Exception
     */
    public function getPredictionCorrectScorersOrderSum(array $predictionIds, $skipCache = false)
    {
        if (empty($predictionIds)){
            throw new \Exception('Empty prediction ids');
        }

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            SUM(p.correctScorersOrder) as correct_scorers_order
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->in('p.id',':ids'))->setParameter('ids', $predictionIds);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    function getPredictableCount($seasonId, $userId, $maxAhead) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('unp', 'u');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT count(mt.id) unp FROM (
                    SELECT m.id
                    FROM `match` m
                    INNER JOIN competition c ON c.id = m.competition_id AND c.season_id = ' . $seasonId . '
                    WHERE m.status = \'' . Match::PRE_MATCH_STATUS . '\'
                    AND m.start_time > NOW()
                    ORDER BY m.start_time ASC
                    LIMIT 0, ' . $maxAhead . ') mt
                WHERE NOT EXISTS(SELECT 1 FROM prediction p WHERE p.match_id = mt.id AND p.user_id = ' . $userId . ')
             ', $rsm);
        return $query->getSingleScalarResult();
    }

}
