<?php

namespace Application\Model\DAOs;

use \Application\Model\Entities\Match;
use \Application\Model\Entities\User;
use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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

    function getUserPrediction(Match $match, User $user, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from($this->getRepositoryName(), 'p')
            ->join('p.match', 'm')
            ->join('p.user', 'u')
            ->where($qb->expr()->eq('m.id', $match->getId()))
            ->where($qb->expr()->eq('u.id', $user->getId()));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
