<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SeasonDAO extends AbstractDAO {

    /**
     * @var SeasonDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return SeasonDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new SeasonDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\Season';
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getAllSeasons($hydrate = false, $skipCache = false) {
        return parent::findAll($hydrate, $skipCache);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array|\Application\Model\Entities\Season
     */
    public function getCurrentSeason($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $qb->select('s')
            ->from($this->getRepositoryName(), 's')
            ->where($qb->expr()->lte('s.startDate', ":today"))
            ->andWhere($qb->expr()->gte('s.endDate', ":today"))
            ->setParameter("today", $today);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param int $seasonId
     * @return bool
     */
    public function checkSeasonDatesInterval($startDate, $endDate, $seasonId = -1) {
        $startDate->setTime(0, 0, 0);
        $endDate->setTime(0, 0, 0);
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('s.id'))
            ->from($this->getRepositoryName(), 's')
            ->where($qb->expr()->orx(
            $qb->expr()->andX($qb->expr()->lte('s.startDate', ":startDate"), $qb->expr()->gte('s.endDate', ":startDate")),
            $qb->expr()->andX($qb->expr()->lte('s.startDate', ":endDate"), $qb->expr()->gte('s.endDate', ":endDate"))))
            ->andWhere($qb->expr()->neq('s.id', $seasonId))
            ->setParameter("startDate", $startDate)
            ->setParameter("endDate", $endDate);
        return $this->getQuery($qb, false)->getSingleScalarResult() == 0;
    }

}
