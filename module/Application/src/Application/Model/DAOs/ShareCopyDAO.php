<?php

namespace Application\Model\DAOs;

use \Application\Model\Entities\ShareCopy;
use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class ShareCopyDAO extends AbstractDAO {

    /**
     * @var ShareCopyDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ShareCopyDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ShareCopyDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\ShareCopy';
    }

    /**
     * @param string $target
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getCopyByTarget($target, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('sc')
            ->from($this->getRepositoryName(), 'sc')
            ->where($qb->expr()->eq('sc.target', ':target'))->setParameter('target', $target)
            ->orderBy('sc.weight', 'ASC')
            ->addOrderBy('sc.id', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param string $engine
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getEveryPredictionNonEmptyCopies($engine, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('sc')
            ->from($this->getRepositoryName(), 'sc')
            ->where($qb->expr()->eq('sc.target', ':target'))->setParameter('target', ShareCopy::PRE_MATCH_REPORT)
            ->andWhere($qb->expr()->neq('sc.copy', ':empty'))->setParameter('empty', '')
            ->andWhere($qb->expr()->eq('sc.engine', ':engine'))->setParameter('engine', $engine)
            ->andWhere($qb->expr()->eq('sc.weight', 3))
            ->orderBy('sc.weight', 'ASC')
            ->addOrderBy('sc.id', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param string $engine
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getFirstPredictionCopy($engine, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('sc.copy')
            ->from($this->getRepositoryName(), 'sc')
            ->where($qb->expr()->eq('sc.target', ':target'))->setParameter('target', ShareCopy::PRE_MATCH_REPORT)
            ->andWhere($qb->expr()->eq('sc.engine', ':engine'))->setParameter('engine', $engine)
            ->andWhere($qb->expr()->eq('sc.weight', 1))
            ->orderBy('sc.weight', 'ASC')
            ->addOrderBy('sc.id', 'ASC');
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

}
