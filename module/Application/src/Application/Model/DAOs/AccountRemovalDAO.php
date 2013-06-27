<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class AccountRemovalDAO extends AbstractDAO {
    /**
     * @var AccountRemovalDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return AccountRemovalDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new AccountRemovalDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return 'Application\Model\Entities\AccountRemoval';
    }

    /**
     * @param $accountType
     * @param bool $skipCache
     * @return mixed
     */
    public function getDeletionsCountByType($accountType, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('
            COUNT(d.id) as deletions_count
        ');
        $qb->from($this->getRepositoryName(),'d');
        $qb->where($qb->expr()->eq('d.accountType',':accountType'))->setParameter('accountType', $accountType);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }


}
