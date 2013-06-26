<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\Entities\MatchGoal;

class MatchGoalDAO extends AbstractDAO {

    /**
     * @var MatchGoalDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return MatchGoalDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new MatchGoalDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return 'Application\Model\Entities\MatchGoal';
    }

    /**
     * @param $matchId
     * @param int $limit
     * @param bool $skipCache
     * @param bool $hydrate
     * @return array
     */
    public function getMatchScorers($matchId, $limit = -1, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->distinct(true);
        $qb->select('
            mg,p
        ');
        $qb->from($this->getRepositoryName(),'mg');
        $qb->join('mg.player', 'p');
        $qb->where($qb->expr()->eq('mg.match',':matchId'))->setParameter('matchId', $matchId);
        $qb->andWhere($qb->expr()->neq('mg.type',':own'))->setParameter('own',MatchGoal::OWN_TYPE);
        $qb->andWhere($qb->expr()->in('mg.period',':period'))->setParameter('period',array(MatchGoal::FIRST_HALF_PERIOD, MatchGoal::SECOND_HALF_PERIOD));
        $qb->orderBy('mg.time', 'ASC');
        if ($limit != -1 && is_int($limit)){
            $qb->setMaxResults($limit);
        }

        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }
}