<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmblemDAO extends AbstractDAO {
    /**
     * @var EmblemDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return EmblemDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new EmblemDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return 'Application\Model\Entities\Emblem';
    }

    //TODO we have one emblem for now
    public function getEmblem($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from($this->getRepositoryName(), 'e')
            ->setMaxResults(1);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }
}
