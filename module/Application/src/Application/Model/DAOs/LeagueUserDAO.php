<?php

namespace Application\Model\DAOs;

use \Application\Model\Entities\League;
use Application\Model\DAOs\AbstractDAO;
use Doctrine\ORM\Query\ResultSetMapping;
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
            ->where($qb->expr()->isNotNull('lu.place'))
            ->andWhere($qb->expr()->eq('lu.league', $leagueId));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    // todo refactoring asap

    /**
     * @param int $leagueId
     * @param int $top
     * @param int $offset
     * @param array|null $facebookIds
     * @param bool $skipCache
     * @return array
     */
    public function getLeagueTop($leagueId, $top = 0, $offset = 0, $facebookIds = null, $skipCache = false) {
        if ($facebookIds !== null) {
            if (empty($facebookIds)) return array();
            $userDAO = UserDAO::getInstance($this->getServiceLocator());
            $usersIdsArr = $userDAO->getUserIdsByFacebookIds($facebookIds, $skipCache);
            $usersIds = array();
            foreach ($usersIdsArr as $userId)
                $usersIds [] = $userId['id'];
            $facebookCondition = ' AND lu.user_id IN (' . implode(",", $usersIds) . ')';
        } else $facebookCondition = '';
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('points','points');
        $rsm->addScalarResult('accuracy','accuracy');
        $rsm->addScalarResult('place','place');
        $rsm->addScalarResult('previous_place','previousPlace');
        $rsm->addScalarResult('display_name','displayName');
        $rsm->addScalarResult('flag_image','flagImage');
        $rsm->addScalarResult('country','country');
        $rsm->addScalarResult('user_id','userId');
        $limit = '';
        if ($top > 0)
            $limit = "LIMIT $offset, $top";
        $query = $this->getEntityManager()->createNativeQuery("
            SELECT
                lu.points, lu.accuracy, lu.place, lu.previous_place, u.display_name, c.flag_image, c.name as country, u.id as user_id
            FROM
               (SELECT * FROM league_user lu
               WHERE lu.league_id = $leagueId AND lu.place IS NOT NULL $facebookCondition
            ORDER BY lu.place ASC
                $limit) as lu
                INNER JOIN user u ON lu.user_id = u.id
                INNER JOIN country c ON u.country_id = c.id
        ", $rsm);
        return $this->prepareQuery($query, array(LeagueUserPlaceDAO::getInstance($this->getServiceLocator())->getRepositoryName()), $skipCache)->getArrayResult();
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

    public function beginLeagueUsersUpdate() {
        $this->getEntityManager()->getConnection()->beginTransaction();
    }

    public function appendLeagueUsersUpdate($leagueUser, $place) {
        $points = $leagueUser['points'];
        $previousPlace = $leagueUser['place'] !== null ? $leagueUser['place'] : 'null';
        $accuracy = floor(100 * $leagueUser['accuracy']);
        $id = $leagueUser['id'];
        $correctResults = $leagueUser['correct_results'];
        $correctScores = $leagueUser['correct_scores'];
        $correctScorers = $leagueUser['correct_scorers'];
        $correctScorersOrder = $leagueUser['correct_scorers_order'];
        $predictionsPlayersCount = $leagueUser['predictions_players_count'];
        $predictionsCount = $leagueUser['predictions_count'];

        $this->getEntityManager()->getConnection()->executeQuery("
            UPDATE league_user lu
            SET lu.correct_results = $correctResults, lu.correct_scores = $correctScores,
            lu.correct_scorers = $correctScorers, lu.correct_scorers_order = $correctScorersOrder,
            lu.predictions_players_count = $predictionsPlayersCount, lu.predictions_count = $predictionsCount,
            lu.points = $points, lu.place = $place, lu.previous_place = $previousPlace, lu.accuracy = $accuracy
            WHERE lu.id = $id;
        ");
    }

    public function appendLeagueUserPlace($leagueUser, $place, $matchId) {
        $leagueUserId = $leagueUser['id'];
        $previousPlace = $leagueUser['place'] !== null ? $leagueUser['place'] : 'null';
        $this->getEntityManager()->getConnection()->executeQuery("
            INSERT INTO league_user_place (league_user_id, match_id, place, previous_place)
            VALUES($leagueUserId, $matchId, $place, $previousPlace)
        ");
    }

    public function commitLeagueUsersUpdate() {
        $this->getEntityManager()->getConnection()->commit();
    }
}
