<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegionGameplayContentDAO extends AbstractDAO {

    /**
     * @var RegionGameplayContentDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return RegionGameplayContentDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new RegionGameplayContentDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return 'Application\Model\Entities\RegionGameplayContent';
    }

    /**
     * @param Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getRegionGameplayBlocks($region, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('g, f')
            ->from($this->getRepositoryName(), 'g')
            ->join('g.foregroundImage', 'f')
            ->where($qb->expr()->eq('g.region', $region->getId()))
            ->orderBy("g.order", "ASC");
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
