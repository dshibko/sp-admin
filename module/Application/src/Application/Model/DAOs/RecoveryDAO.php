<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class RecoveryDAO extends AbstractDAO {

    /**
     * @var RecoveryDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return RecoveryDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new RecoveryDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\Recovery';
    }

    /**
     * @param string $hash
     * @return \Application\Model\Entities\Recovery
     * @throws \Exception
     */
    public function checkHash($hash) {
        try {
            $now = new \DateTime();
            $nowSub3 = $now->sub(new \DateInterval('PT3H'));
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('r')
                ->from($this->getRepositoryName(), 'r')
                ->where('r.isActive = 1 and r.hash = :hash and r.date >= :date')
                ->setParameter('hash', $hash)
                ->setParameter('date', $nowSub3);
            return $qb->getQuery()->useResultCache(false)->getOneOrNullResult();
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
