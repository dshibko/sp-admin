<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RoleDAO
 * @package Application\Model\DAOs
 */
class RoleDAO extends AbstractDAO {

    /**
     * @var RoleDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return RoleDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new RoleDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\Role';
    }

    /**
     * @param $name
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getRoleByName($name, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('r')
            ->from($this->getRepositoryName(), 'r')
            ->where($qb->expr()->eq('r.name',':name'))
            ->setParameter('name', $name);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }
}
