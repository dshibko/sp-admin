<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LeagueDAO extends AbstractDAO {

    /**
     * @var LeagueDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LeagueDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LeagueDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\League';
    }

    /**
     * @param \Application\Model\Entities\League $league
     * @return array
     */
    public function getLeagueUsersScores($league) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT lu, u, SUM(p.points) as points,
             ((SUM(p.isCorrectResult) / COUNT(p)) + (SUM(p.isCorrectScore) / COUNT(p)) + (SUM(p.correctScorers) / SUM(p.homeTeamScore + p.awayTeamScore)) + (SUM(p.correctScorersOrder) / SUM(p.homeTeamScore + p.awayTeamScore))) / 4 as accuracy,
             COUNT(p.id) as predictions_count, SUM(p.isCorrectResult) as correct_results, SUM(p.isCorrectScore) as correct_scores, SUM(p.correctScorers) as correct_scorers
             FROM \Application\Model\Entities\LeagueUser lu
             JOIN lu.user u
             JOIN lu.league l WITH l.id = ' . $league->getId() . '
             JOIN u.predictions p WITH p.points is not null
             JOIN p.match m
             WHERE m.startTime >= :startDate
             AND m.startTime <= :endDate
             GROUP BY lu.id
             ')
            ->setParameter('startDate', $league->getStartDate()->format('Y-m-d'))
            ->setParameter('endDate', $league->getEndDate()->format('Y-m-d'));
        return $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getGlobalLeagues($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->where($qb->expr()->eq('l.isGlobal', 1));
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \Application\Model\Entities\League $league
     * @param \Application\Model\Entities\User $user
     * @param bool $skipCache
     * @return bool
     */
    public function getIsUserInLeague($league, $user, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('l.id'))
            ->from($this->getRepositoryName(), 'l')
            ->join('l.leagueUsers', 'lu')
            ->where($qb->expr()->eq('l.id', $league->getId()))
            ->andWhere($qb->expr()->eq('lu.user', $user->getId()));
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult() > 0;
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getRegionalLeagues($region, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->where($qb->expr()->eq('l.isGlobal', 0))
            ->andWhere($qb->expr()->eq('l.isPrivate', 0))
            ->andWhere($qb->expr()->eq('l.region', $region->getId()));
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
