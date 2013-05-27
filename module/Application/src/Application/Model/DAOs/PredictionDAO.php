<?php

namespace Application\Model\DAOs;

use \Application\Model\Entities\Season;
use \Doctrine\ORM\Query\ResultSetMapping;
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
    function getAvgNumberOfPrediction($season) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('avg', 'a');
        $query = $this->getEntityManager()
            ->createNativeQuery('SELECT AVG(pr.predictions) as avg FROM (SELECT COUNT(p.id) as predictions
             FROM `match` m
             INNER JOIN competition c ON c.id = m.competition_id AND c.season_id = ' . $season->getId() . '
             LEFT JOIN prediction p ON p.match_id = m.id
             WHERE m.start_time < NOW()
             GROUP BY m.id) pr
             ', $rsm);
        return $query->getSingleScalarResult();
    }

}
