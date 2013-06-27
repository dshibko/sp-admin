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


}
