<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchLanguageDAO extends AbstractDAO {

    /**
     * @var MatchLanguageDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return MatchLanguageDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new MatchLanguageDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\MatchLanguage';
    }

    /**
     * @param $matchId
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\MatchLanguage
     */
    public function getMatchLanguageByMatchIdAndLanguageId($matchId, $languageId, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('mr, m, p, g, pr')
            ->from($this->getRepositoryName(), 'mr')
            ->join('mr.match','m')
            ->leftJoin('mr.featuredPlayer', 'p')
            ->leftJoin('mr.featuredGoalKeeper', 'g')
            ->leftJoin('mr.featuredPrediction', 'pr')
            ->where($qb->expr()->eq('m',':matchId'))->setParameter('matchId', $matchId)
            ->andWhere($qb->expr()->eq('mr.language',':languageId'))->setParameter('languageId', $languageId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param $matchId
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getPostMatchLanguageByMatchIdAndLanguageId($matchId, $languageId, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('mr, m')
            ->from($this->getRepositoryName(), 'mr')
            ->join('mr.match','m')
            ->where($qb->expr()->eq('m',':matchId'))->setParameter('matchId', $matchId)
            ->andWhere($qb->expr()->eq('mr.language',':languageId'))->setParameter('languageId', $languageId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}