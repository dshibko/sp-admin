<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlayerDAO extends AbstractDAO {
    /**
     * @var PlayerDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return PlayerDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new PlayerDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return 'Application\Model\Entities\Player';
    }

    /**
     * @param int $teamId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getAllClubPlayers($teamId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
                    p.id,
                    p.displayName,
                    p.position,
                    p.shirtNumber,
                    p.imagePath,
                    p.backgroundImagePath
        ')
            ->from($this->getRepositoryName(), 'p')
            ->where($qb->expr()->eq('p.team', $teamId))
            ->orderBy('p.displayName', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }


    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getAllPlayers($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
                    p.id,
                    p.displayName,
                    p.position,
                    p.shirtNumber,
                    p.imagePath,
                    p.backgroundImagePath,
                    t.displayName as team_name
        ')
            ->from($this->getRepositoryName(), 'p')
            ->join('p.team','t')
            ->orderBy('p.displayName', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }


    /**
     * @param array $positions
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getPlayersByPositions(array $positions, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
                    p.id,
                    p.displayName
        ')
            ->from($this->getRepositoryName(), 'p')
            ->where($qb->expr()->in('p.position',':position'))->setParameter('position', $positions)
            ->orderBy('p.displayName', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param array $positions
     * @param array $teamIds
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getPlayersByPositionsFromTeams(array $positions, array $teamIds, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
                    p.id,
                    p.displayName
        ')
        ->from($this->getRepositoryName(), 'p')
        ->where($qb->expr()->in('p.team',':teamIds'))->setParameter('teamIds', $teamIds)
        ->andWhere($qb->expr()->in('p.position',':position'))->setParameter('position', $positions)
        ->orderBy('p.displayName', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
