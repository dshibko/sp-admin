<?php

namespace Application\Model\DAOs;

use \Application\Model\Entities\League;
use Application\Model\DAOs\AbstractDAO;
use Application\Model\Entities\LeagueUser;
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
     * @param int $languageId
     * @param int $defaultLanguageId
     * @param bool $skipCache
     * @internal param \Application\Model\Entities\Region|null $region
     * @return array
     */
    public function getUserLeagues($user, $season, $languageId, $defaultLanguageId, $skipCache = false) {
        $nowTime = new \DateTime();
        $nowTime->setTime(0, 0, 0);
        $query = $this->getEntityManager()->createQuery('
            SELECT
                lu.points, lu.accuracy, lu.place, lu.previousPlace, l.type, ll.displayName, ldl.displayName defaultDisplayName, l.displayName internalName
            FROM
               '.$this->getRepositoryName().' as lu
            INNER JOIN lu.league l WITH l.season = ' . $season->getId() . '
            LEFT JOIN l.leagueLanguages ll WITH ll.language = ' . $languageId . '
            LEFT JOIN l.leagueLanguages ldl WITH ldl.language = ' . $defaultLanguageId . '
            WHERE lu.user = ' . $user->getId() . ' AND
                :nowTime >= l.startDate AND :nowTime <= l.endDate
        ')->setParameter('nowTime', $nowTime);
        return $this->prepareQuery($query, array(LeagueUserDAO::getInstance($this->getServiceLocator())->getRepositoryName()), $skipCache)->getArrayResult();
    }

    public function getUserLeaguesByTypes(\Application\Model\Entities\User $user, \Application\Model\Entities\Season $season, \Application\Model\Entities\Region $region, array $types, $skipCache = false)
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
                l.type
            FROM
               '.$this->getRepositoryName().' as lu
            INNER JOIN lu.league l WITH l.season = ' . $season->getId() . '
            LEFT JOIN l.leagueRegions lr WITH lr.region = ' . $regionId . '
            WHERE lu.user = ' . $user->getId() . ' AND
                :nowTime >= l.startDate AND :nowTime <= l.endDate AND l.type IN (:types)
        ')->setParameter('nowTime', $nowTime)->setParameter('types', $types);
        return $this->prepareQuery($query, array(LeagueUserDAO::getInstance($this->getServiceLocator())->getRepositoryName()), $skipCache)->getArrayResult();
    }

    /**
     * @param int $leagueId
     * @param bool $placed
     * @param bool $skipCache
     * @return int
     */
    public function getLeagueUsersCount($leagueId, $placed = true, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('lu.id'))
            ->from($this->getRepositoryName(), 'lu')
            ->where($qb->expr()->eq('lu.league', $leagueId));
        if ($placed)
            $qb->andWhere($qb->expr()->isNotNull('lu.place'));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param int $leagueId
     * @param bool $placeIsNotNull
     * @param int $top
     * @param int $offset
     * @param array|null $facebookIds
     * @param bool $skipCache
     * @return array
     */
    public function getLeagueTop($leagueId, $placeIsNotNull = true, $top = 0, $offset = 0, $facebookIds = null, $skipCache = false) {
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
        $placeIsNull = $placeIsNotNull ? 'AND lu.place IS NOT NULL' : '';
        $query = $this->getEntityManager()->createNativeQuery("
            SELECT
                IFNULL(lu.points, 0) points, lu.accuracy, lu.place, lu.previous_place, u.display_name, c.flag_image, c.name as country, u.id as user_id
            FROM
               (SELECT * FROM league_user lu
               WHERE lu.league_id = $leagueId $placeIsNull $facebookCondition
            ORDER BY lu.place is not null DESC, lu.place ASC, lu.join_date DESC
                $limit) as lu
                INNER JOIN user u ON lu.user_id = u.id
                INNER JOIN country c ON u.country_id = c.id
        ", $rsm);
        return $this->prepareQuery($query, array(LeagueUserPlaceDAO::getInstance($this->getServiceLocator())->getRepositoryName()), $skipCache)->getArrayResult();
    }

    /**
     * @param int $leagueId
     * @param int $top
     * @param int $offset
     * @param array|null $facebookIds
     * @param bool $skipCache
     * @return array
     */
    public function getPrivateLeagueTop($leagueId, $top = 0, $offset = 0, $facebookIds, $skipCache = false) {
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
                0 points, lu.accuracy, lu.place, lu.previous_place, u.display_name, c.flag_image, c.name as country, u.id as user_id
            FROM
               league_user lu
               INNER JOIN user u ON lu.user_id = u.id
               INNER JOIN country c ON u.country_id = c.id
               WHERE lu.league_id = $leagueId $facebookCondition
               ORDER BY u.display_name ASC
                $limit
        ", $rsm);
        return $this->prepareQuery($query, array(LeagueUserDAO::getInstance($this->getServiceLocator())->getRepositoryName()), $skipCache)->getArrayResult();
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
     * @return LeagueUser|array
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

    public function moveUpLeagueUserPlaces($league, $fromPlace) {
        $this->getEntityManager()->getConnection()->executeQuery("
            UPDATE league_user lu
            SET lu.place = lu.place - 1
            WHERE lu.place > $fromPlace and lu.league_id = {$league->getId()};
        ");
    }
}
