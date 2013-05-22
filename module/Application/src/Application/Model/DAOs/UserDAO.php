<?php

namespace Application\Model\DAOs;

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
        $qb->select('u, r')
            ->from($this->getRepositoryName(), 'u')
            ->join('u.role', 'r');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
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

}
