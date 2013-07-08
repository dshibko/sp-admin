<?php

namespace Application\Model\DAOs;

use \Doctrine\ORM\Query\ResultSetMapping;
use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserDAO extends AbstractDAO {

    /**
     * @var UserDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return UserDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new UserDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\User';
    }

    /**
     * @param $identity
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\User
     * @throws \Exception
     */
    public function findOneByIdentity($identity, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u, r, a')
            ->from($this->getRepositoryName(), 'u')
            ->join('u.role', 'r')
            ->join('u.avatar', 'a')
            ->where('u.email = :identity')
            ->setParameter('identity', $identity);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param int $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\User
     * @throws \Exception
     */
    public function findOneById($id, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u, r, a')
            ->from($this->getRepositoryName(), 'u')
            ->join('u.role', 'r')
            ->join('u.avatar', 'a')
            ->where('u.id = :id')
            ->setParameter('id', $id);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param int $days
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getUsersRegisteredInPastDays($days, $hydrate = false, $skipCache = false) {
        $now = new \DateTime();
        $subDays = $now->sub(new \DateInterval('P' . $days . 'D'));
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from($this->getRepositoryName(), 'u')
            ->where('u.date >= :date')
            ->setParameter('date', $subDays);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getAllUsers($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u.id, u.displayName, u.email, u.date')
            ->from($this->getRepositoryName(), 'u')
            ->orderBy('u.date', 'DESC')
            ->setMaxResults(100);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    public function getUsersByRoles(array $roles, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u, r')
            ->from($this->getRepositoryName(), 'u')
            ->join('u.role', 'r')
            ->where($qb->expr()->in('r.name',':roles'))->setParameter('roles', $roles)
            ->orderBy('u.firstName','ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getExportUsers($skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u.id, u.displayName, u.email, u.date, u.facebookId, u.facebookAccessToken, count(p.id) as predictions, u.term1, u.term2')
            ->from($this->getRepositoryName(), 'u')
            ->leftJoin('u.predictions', 'p')
            ->groupBy('u.id');
        return $this->getQuery($qb, $skipCache)->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
    }

    /**
     * @param bigint $facebook_id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\User
     * @throws \Exception
     */
    public function getUserByFacebookId($facebook_id,$hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u, r, a')
            ->from($this->getRepositoryName(), 'u')
            ->join('u.role', 'r')
            ->join('u.avatar', 'a')
            ->where('u.facebookId = :id')
            ->setParameter('id', $facebook_id);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param \Application\Model\Entities\Season $season
     * @return integer
     */
    public function getActiveUsersNumber($season) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT COUNT(u)
             FROM ' . $this->getRepositoryName() . ' u
             WHERE EXISTS(SELECT 1 FROM \Application\Model\Entities\Prediction p
             JOIN p.match m
             JOIN m.competition c
             JOIN c.season s WITH s.id = ' . $season->getId() . '
             WHERE p.user = u.id)
             ');
        return $query->getSingleScalarResult();
    }

    /**
     * @param bool $skipCache
     * @return int
     * @throws \Exception
     */
    public function getFacebookUsersNumber($skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('u.id'))
            ->from($this->getRepositoryName(), 'u')
            ->where($qb->expr()->isNotNull('u.facebookId'));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @param bool $skipCache
     * @return int
     * @throws \Exception
     */
    public function getDirectUsersNumber($skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('u.id'))
            ->from($this->getRepositoryName(), 'u')
            ->where($qb->expr()->isNull('u.facebookId'));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getPerWeekRegistrations() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('number','number', 'integer');
        $rsm->addScalarResult('first_day','first_day', 'date');
        $rsm->addScalarResult('last_day','last_day', 'date');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT count(id) as number,
                    DATE(DATE_ADD(`date`, INTERVAL(1-DAYOFWEEK(`date`)) DAY)) as first_day,
                    DATE(DATE_ADD(`date`, INTERVAL(7-DAYOFWEEK(`date`)) DAY)) as last_day
                FROM user
                GROUP BY YEAR(`date`), WEEK(`date`)
                ORDER BY YEAR(`date`), WEEK(`date`)
            ', $rsm);
        return $query->getArrayResult();
    }

    /**
     * @param int $days
     * @return int
     * @throws \Exception
     */
    public function getNumberOfUsersPredictedLastNDays($days = 30)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('number','number', 'integer');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT count(u.id) as number
                FROM user u
                WHERE EXISTS (SELECT 1 FROM
                prediction p
                WHERE p.creation_date > DATE_SUB(NOW(), INTERVAL ' . $days . ' DAY)
                AND p.user_id = u.id)
            ', $rsm);
        $result = $query->getSingleScalarResult();
        return !empty($result) ? (int)$result : 0;
    }

    /**
     * @param int $days
     * @return int
     * @throws \Exception
     */
    public function getNumberOfUsersNotPredictedLastNDays($days = 30)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('number','number', 'integer');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT count(u.id) as number
                FROM user u
                WHERE NOT EXISTS (SELECT 1 FROM
                prediction p
                WHERE p.creation_date > DATE_SUB(NOW(), INTERVAL ' . $days . ' DAY)
                AND p.user_id = u.id)
            ', $rsm);
        $result = $query->getSingleScalarResult();
        return !empty($result) ? (int)$result : 0;
    }

    /**
     * @param bool $skipCache
     * @return int
     * @throws \Exception
     */
    public function getIncompleteUsersNumber($skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('u.id'))
            ->from($this->getRepositoryName(), 'u')
            ->where($qb->expr()->eq('u.isActive', 0));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getUsersByRegion() {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('region','region');
        $rsm->addScalarResult('users','users', 'integer');
        $query = $this->getEntityManager()
            ->createNativeQuery('
                SELECT r.display_name as region, count(u.id) as users
                FROM region as r
                LEFT OUTER JOIN country as c ON c.region_id = r.id
                LEFT OUTER JOIN user as u ON u.country_id = c.id
                GROUP BY r.id
                ORDER BY r.id
            ', $rsm);
        return $query->getArrayResult();
    }

    public function registerLeagueUsers($leagueId, $regionId = null) {
        $conn = $this->getEntityManager()->getConnection();
        $now = new \DateTime();
        $now = date("Y-m-d H:i:s", $now->getTimestamp());
        if ($regionId === null)
            $conn->executeQuery('
                INSERT INTO league_user (user_id, league_id, registration_date, join_date)
                SELECT u.id, ?, u.date, ?
                FROM user u
                WHERE u.is_active = 1
            ', array($leagueId, $now));
        else {
            $conn->executeQuery('
                INSERT INTO league_user (user_id, league_id, registration_date, join_date)
                SELECT u.id, ?, u.date, ?
                FROM user u
                INNER JOIN country c ON c.id = u.country_id AND c.region_id = ?
                WHERE u.is_active = 1
            ', array($leagueId, $now, $regionId));
        }
    }

    /**
     * @param array $facebookIds
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getUserIdsByFacebookIds($facebookIds, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u.id')
            ->from($this->getRepositoryName(), 'u')
            ->where($qb->expr()->in('u.facebookIds', ':facebookIds'))
            ->setParameter('facebookIds', $facebookIds);
        return $this->getQuery($qb, $skipCache)->getScalarResult();
    }

}
