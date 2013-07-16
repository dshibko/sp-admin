<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Query\Expr;

class TeamDAO extends AbstractDAO {

    /**
     * @var TeamDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return TeamDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new TeamDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\Team';
    }

    /**
     * @param integer $teamId
     * @param integer $competitionId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    function getTeamSquadInCompetition($teamId, $competitionId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p.displayName, p.position, p.shirtNumber, p.id')
            ->from('\Application\Model\Entities\Player', 'p')
            ->join('p.competitions', 'cp', Expr\Join::WITH, 'cp.id = ' . $competitionId)
            ->where($qb->expr()->eq('p.team', $teamId))
            ->orderBy('p.position', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param integer $teamId
     * @param integer $seasonId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    function getTeamSquad($teamId, $seasonId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p.displayName, p.position, p.shirtNumber, p.id')
            ->from('\Application\Model\Entities\Player', 'p')
            ->join('p.competitions', 'cp', Expr\Join::WITH, 'cp.season = ' . $seasonId)
            ->where($qb->expr()->eq('p.team', $teamId))
            ->groupBy('p.id')
            ->having('count(cp.id) > 0')
            ->orderBy('p.position', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getAllTeams($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from($this->getRepositoryName(), 't')
            ->orderBy('t.displayName', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param \Application\Model\Entities\Team $club
     * @param \Application\Model\Entities\Season $season
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getClubEnemies($club, $season, $hydrate = false, $skipCache = false) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT t
                FROM ' . $this->getRepositoryName() . ' t
                LEFT JOIN t.homeMatches as hm
                LEFT JOIN hm.competition as hc
                LEFT JOIN t.awayMatches as am
                LEFT JOIN am.competition as ac
                WHERE ((hm.awayTeam = :clubId AND hc.season = :seasonId) OR (am.homeTeam = :clubId AND ac.season = :seasonId) OR t = :clubId)
                GROUP BY t.id')
            ->setParameter('seasonId', $season->getId())
            ->setParameter('clubId', $club != null ? $club->getId() : null);
        return $query->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }


}
