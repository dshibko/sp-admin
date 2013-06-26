<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegionContentDAO extends AbstractDAO {

    /**
     * @var RegionContentDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return RegionContentDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new RegionContentDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return 'Application\Model\Entities\RegionContent';
    }

    /**
     * @param Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return Application\Model\Entities\RegionContent|array
     */
    public function getRegionContent($region, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c, f, b')
            ->from($this->getRepositoryName(), 'c')
            ->join('c.heroForegroundImage', 'f')
            ->join('c.heroBackgroundImage', 'b')
            ->where($qb->expr()->eq('c.region', $region->getId()));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }


}
