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
     * @param int $matchId
     * @param int $limit
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTopScorers($matchId, $limit = 5, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('
            COUNT(pp.playerId) as scorers_count,
            t.displayName as team_name,
            t.id as team_id,
            pl.displayName as player_name,
            pl.backgroundImagePath,
            pl.imagePath
        ');
        $qb->from('\Application\Model\Entities\PredictionPlayer','pp');
        $qb->join('pp.prediction', 'p');
        $qb->join('pp.player','pl');
        $qb->join('pl.team','t');
        $qb->where($qb->expr()->eq('p.match',':id'))->setParameter('id', $matchId);
        $qb->andWhere($qb->expr()->isNotNull('pp.playerId'));
        $qb->groupBy('pp.playerId');
        $qb->orderBy('scorers_count','DESC');
        if ($limit != -1){
            $qb->setMaxResults($limit);
        }
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);

    }


    /**
     * @param $matchId
     * @param int $limit
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTopScores($matchId, $limit = 5, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            p.homeTeamScore as home_team_score,
            p.awayTeamScore as away_team_score,
            count(p.id) as scores_count
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->eq('p.match',':id'))->setParameter('id', $matchId);
        $qb->groupBy('p.homeTeamScore, p.awayTeamScore');
        $qb->orderBy('scores_count','DESC');
        $qb->setMaxResults($limit);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getUsersCountWithCorrectResult($matchId, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            COUNT(p.id) as user_count
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->eq('p.match',':id'))->setParameter('id', $matchId);
        $qb->andWhere('p.isCorrectResult = 1');
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param int $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionsCorrectScoreCount($matchId, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            COUNT(p.id) as user_count
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->in('p.match',':id'))->setParameter('id', $matchId);
        $qb->andWhere('p.isCorrectScore = 1');
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param $matchId
     * @return array
     */
    public function getUsersWithPerfectResult($matchId)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('\Application\Model\Entities\User', 'u');
        $rsm->addEntityResult('\Application\Model\Entities\User', 'u');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                  SELECT
	                  u.id,
	                  u.display_name
                  FROM
	                  `prediction` as p
                  INNER JOIN
	                  `user` as u
	              ON
	                  u.id = p.user_id
	              INNER JOIN
	                  `match` as m
	              ON
	                    m.id = p.match_id
                  WHERE
                      m.id = '.(int)$matchId.'
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
     * @param int $matchId
     * @param int $hoursFromNow
     * @return array
     */
    public function getNumberOfPredictionsPerHour($matchId, $hoursFromNow = 12)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('number','number');
        $rsm->addScalarResult('date','date');

        $query = $this->getEntityManager()
            ->createNativeQuery('
                  SELECT
                      count(p.id) as number,
                      p.creation_date as date
                  FROM
                      `prediction` as p
                  INNER JOIN
                      `match` as m ON m.id = p.match_id
                  WHERE
                      m.id = '.(int)$matchId.'
                  AND
                      p.creation_date > DATE_SUB(NOW(), INTERVAL '.$hoursFromNow.' HOUR)
                  GROUP BY
                      HOUR(p.creation_date)
            ', $rsm);
        return $query->getArrayResult();
    }

    /**
     * @param $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionPlayersCount($matchId, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            COUNT(pp.playerId) as players_count
        ');
        $qb->from('\Application\Model\Entities\PredictionPlayer','pp');
        $qb->join('pp.prediction','p');
        $qb->where($qb->expr()->eq('p.match',':id'))->setParameter('id', $matchId);
        $qb->andWhere($qb->expr()->isNotNull('pp.playerId'));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionCorrectScorersSum($matchId, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            SUM(p.correctScorers) as correct_scorers
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->in('p.match',':id'))->setParameter('id', $matchId);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }


    /**
     * @param $matchId
     * @param bool $skipCache
     * @return mixed
     */
    public function getPredictionCorrectScorersOrderSum($matchId, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            SUM(p.correctScorersOrder) as correct_scorers_order
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->eq('p.match',':id'))->setParameter('id', $matchId);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param $seasonId
     * @param $userId
     * @param $maxAhead
     * @return mixed
     */
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

    /**
     * @param $matchId
     * @param array $scorersIds
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getCorrectScorersPredictionsCount($matchId, array $scorersIds, $hydrate = true, $skipCache = false)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            COUNT(pp.prediction) as predictions_count,
            pl.displayName as player_name,
            t.id as teamId,
            pl.backgroundImagePath,
            pl.imagePath
        ');
        $qb->from('\Application\Model\Entities\PredictionPlayer','pp');
        $qb->join('pp.prediction','p');
        $qb->join('pp.player','pl');
        $qb->join('pl.team','t');
        $qb->where($qb->expr()->in('p.match',':id'))->setParameter('id', $matchId);
        $qb->andWhere($qb->expr()->in('pp.player',':playerIds'))->setParameter('playerIds', $scorersIds);
        $qb->groupBy('pp.player');
        $qb->orderBy('predictions_count', 'DESC');

        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \Application\Model\Entities\Season $season
     * @param \Application\Model\Entities\User $user
     * @return integer
     */
    public function getUserPredictionsNumber($season, $user) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT COUNT(p.id)
             FROM ' . $this->getRepositoryName() . ' p
             JOIN p.match m
             JOIN m.competition c
             JOIN c.season s WITH s.id = ' . $season->getId() . '
             WHERE p.user = ' . $user->getId()
             );
        return $query->getSingleScalarResult();
    }

    /**
     * @param \Application\Model\Entities\Season $season
     * @param \Application\Model\Entities\User $user
     * @param \DateTime $beforeTime
     * @return integer
     */
    public function getUserCorrectScorerPredictionsNumber($season, $user, $beforeTime) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT SUM(p.correctScorers)
             FROM ' . $this->getRepositoryName() . ' p
             JOIN p.match m
             JOIN m.competition c
             JOIN c.season s WITH s.id = ' . $season->getId() . '
             WHERE p.user = ' . $user->getId() . ' AND m.startTime < :beforeTime'
             )->setParameter('beforeTime', $beforeTime);
        return $query->getSingleScalarResult();
    }

    /**
     * @param \Application\Model\Entities\Season $season
     * @param \Application\Model\Entities\User $user
     * @param \DateTime $beforeTime
     * @return integer
     */
    public function hasUserCorrectResults($season, $user, $beforeTime) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT 1
             FROM ' . $this->getRepositoryName() . ' p
             JOIN p.match m
             JOIN m.competition c
             JOIN c.season s WITH s.id = ' . $season->getId() . '
             WHERE p.user = ' . $user->getId() . ' AND m.startTime < :beforeTime AND p.isCorrectResult = 1'
             )->setParameter('beforeTime', $beforeTime)->setMaxResults(1);
        return $query->getOneOrNullResult();
    }

    /**
     * @param $seasonId
     * @return mixed
     */
    function getPredictionsCount($seasonId) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('predictions', 'predictions', 'integer');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT count(p.id) predictions
                FROM `prediction` p
                INNER JOIN `match` m ON m.id = p.match_id
                INNER JOIN competition c ON c.id = m.competition_id AND c.season_id = ' . $seasonId . '
             ', $rsm);
        return $query->getSingleScalarResult();
    }

    public function getPredictionsPerDayWhileSeason($seasonId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('predictions','predictions');
        $rsm->addScalarResult('date','date','date');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT
                    count(p.id) predictions,
                    DATE(p.creation_date) as date
                FROM
                    `prediction` p
                INNER JOIN
                    `match` m ON m.id = p.match_id
                INNER JOIN
                    competition c ON c.id = m.competition_id AND c.season_id = ' . $seasonId . '
                GROUP BY
                      date
                ORDER BY
                    date
            ', $rsm);
        return $query->getArrayResult();
    }
    /**
     * @param $seasonId
     * @return mixed
     */
    function getHighestPredictedMatches($seasonId) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('max_predictions', 'max_predictions', 'integer');
        $maxPredictions = (int)
            $this->getEntityManager()
                ->createNativeQuery('
                    SELECT MAX(pr.predictions) as max_predictions FROM (
                        SELECT count(p.id) as predictions
                        FROM `match` m
                        LEFT OUTER JOIN `prediction` p ON p.match_id = m.id
                        INNER JOIN competition c ON c.id = m.competition_id AND c.season_id = ' . $seasonId . '
                        GROUP BY m.id
                        ORDER BY predictions DESC) pr
                 ', $rsm)->getSingleScalarResult();
        return $this->getMatchesByPredictionsNumber($maxPredictions, $seasonId);
    }

    /**
     * @param $seasonId
     * @return mixed
     */
    function getLowestPredictedMatches($seasonId) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('max_predictions', 'max_predictions', 'integer');
        $maxPredictions = (int)
            $this->getEntityManager()
                ->createNativeQuery('
                    SELECT MIN(pr.predictions) as max_predictions FROM (
                        SELECT count(p.id) as predictions
                        FROM `match` m
                        LEFT OUTER JOIN `prediction` p ON p.match_id = m.id
                        INNER JOIN competition c ON c.id = m.competition_id AND c.season_id = ' . $seasonId . '
                        WHERE m.status = \'' . Match::FULL_TIME_STATUS . '\'
                        GROUP BY m.id
                        ORDER BY predictions DESC) pr
                 ', $rsm)->getSingleScalarResult();
        return $this->getMatchesByPredictionsNumber($maxPredictions, $seasonId, true);
    }

    /**
     * @param $predictions
     * @param $seasonId
     * @param bool $fullTimeOnly
     * @return mixed
     */
    function getMatchesByPredictionsNumber($predictions, $seasonId, $fullTimeOnly = false) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('start_time', 'start_time', 'date');
        $rsm->addScalarResult('competition', 'competition');
        $rsm->addScalarResult('home_team', 'home_team');
        $rsm->addScalarResult('away_team', 'away_team');
        $rsm->addScalarResult('preds', 'predictions', 'integer');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT DATE(m.start_time) as start_time, c.display_name as competition, t1.display_name as home_team, t2.display_name as away_team, ' . $predictions . ' as preds
                FROM `match` m
                INNER JOIN team t1 ON t1.id = m.home_team_id
                INNER JOIN team t2 ON t2.id = m.away_team_id
                INNER JOIN competition c ON c.id = m.competition_id AND c.season_id = ' . $seasonId . '
                WHERE ' . ($fullTimeOnly ? 'm.status = \'' . Match::FULL_TIME_STATUS . '\' AND ' : '') . '
                (SELECT IFNULL(count(p.id), 0) as predictions
                FROM `prediction` p
                WHERE p.match_id = m.id) = ' . $predictions . '
                ORDER BY m.start_time DESC
             ', $rsm);
        return $query->getArrayResult();
    }

    /**
     * @param int $seasonId
     * @param int $limit
     * @return array
     * @throws \Exception
     */
    public function getTopScorersThisSeason($seasonId, $limit = 5)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT
                COUNT(pp.playerId) as predictions,
                t.displayName as team,
                pl.displayName as player
             FROM \Application\Model\Entities\PredictionPlayer pp
             JOIN pp.prediction p
             JOIN p.match m
             JOIN m.competition c
             JOIN c.season s WITH s.id = ' . $seasonId . '
             JOIN pp.player pl
             JOIN pl.team t
             WHERE pp.playerId IS NOT NULL
             GROUP BY pp.playerId
             ORDER BY predictions DESC
             '
        )->setMaxResults($limit);

        return $query->getArrayResult();
    }

    /**
     * @param int $seasonId
     * @param int $limit
     * @return array
     * @throws \Exception
     */
    public function getTopScoresThisSeason($seasonId, $limit = 5)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT
                p.homeTeamScore as home_team_score,
                p.awayTeamScore as away_team_score,
                count(p.id) as predictions
             FROM ' . $this->getRepositoryName() . ' p
             JOIN p.match m
             JOIN m.competition c
             JOIN c.season s WITH s.id = ' . $seasonId . '
             GROUP BY p.homeTeamScore, p.awayTeamScore
             ORDER BY predictions DESC
             '
        )->setMaxResults($limit);

        return $query->getArrayResult();
    }

    /**
     * @param int $matchId
     * @return mixed
     */
    public function getMatchPredictions($matchId) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('user_id', 'user_id');
        $rsm->addScalarResult('home_team_score', 'home_team_score');
        $rsm->addScalarResult('away_team_score', 'away_team_score');
        $rsm->addScalarResult('players', 'players');
        $rsm->addScalarResult('teams', 'teams');
        $rsm->addScalarResult('orders', 'orders');
        $query = $this->getEntityManager()
            ->createNativeQuery('
             SELECT p.id, p.user_id, p.home_team_score, p.away_team_score, GROUP_CONCAT(IFNULL(pp.player_id, "") ORDER BY pp.order, pp.team_id SEPARATOR \',\') players, GROUP_CONCAT(IFNULL(pp.team_id, "") ORDER BY pp.order, pp.team_id SEPARATOR \',\') teams, GROUP_CONCAT(IFNULL(pp.order, "") ORDER BY pp.order, pp.team_id SEPARATOR \',\') orders
             FROM `prediction` p
             LEFT OUTER JOIN `prediction_player` pp ON pp.prediction_id = p.id
             WHERE p.match_id = ' . $matchId . '
             GROUP BY p.id', $rsm);
        return $query->getArrayResult();
    }

    /**
     * @param int $matchId
     * @return mixed
     */
    public function getPredictionsByMatchId($matchId) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('user_id', 'user_id');
        $rsm->addScalarResult('is_correct_result', 'is_correct_result');
        $rsm->addScalarResult('is_correct_score', 'is_correct_score');
        $rsm->addScalarResult('correct_scorers', 'correct_scorers');
        $rsm->addScalarResult('correct_scorers_order', 'correct_scorers_order');
        $rsm->addScalarResult('predictions_players_count', 'predictions_players_count');
        $query = $this->getEntityManager()
            ->createNativeQuery('
             SELECT p.id, p.user_id, p.is_correct_result, p.is_correct_score, p.correct_scorers, p.correct_scorers_order, count(pp.id) predictions_players_count
             FROM `prediction` p
             LEFT OUTER JOIN `prediction_player` pp ON pp.prediction_id = p.id
             WHERE p.match_id = ' . $matchId . '
             GROUP BY p.id', $rsm);
        return $query->getArrayResult();
    }

    public function beginPredictionsUpdate() {
        $this->getEntityManager()->getConnection()->beginTransaction();
    }

    public function appendPredictionsUpdate($prediction) {
        $isCorrectResult = $prediction['is_correct_result'];
        $isCorrectScore = $prediction['is_correct_score'];
        $correctScorers = $prediction['correct_scorers'];
        $correctScorersOrder = $prediction['correct_scorers_order'];
        $points = $prediction['points'];
        $id = $prediction['id'];
        $this->getEntityManager()->getConnection()->executeQuery("
            UPDATE prediction p
            SET p.is_correct_result = $isCorrectResult, p.is_correct_score = $isCorrectScore, p.correct_scorers = $correctScorers, p.correct_scorers_order = $correctScorersOrder, p.points = $points
            WHERE p.id = $id;
        ");
    }

    public function commitPredictionsUpdate() {
        $this->getEntityManager()->getConnection()->commit();
    }

    public function getMatchPredictionsCount($matchId, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            COUNT(p.id) as predictions
        ');
        $qb->from($this->getRepositoryName(),'p');
        $qb->where($qb->expr()->eq('p.match',':id'))->setParameter('id', $matchId);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }
}
