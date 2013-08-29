<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class FeedDAO extends AbstractDAO {

    /**
     * @var FeedDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return FeedDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new FeedDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\Feed';
    }

    /**
     * @param string $fileName
     * @param int $seasonId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Feed|array
     */
    public function getFeedByFileNameAndSeason($fileName, $seasonId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('f')
            ->from($this->getRepositoryName(), 'f')
            ->where($qb->expr()->eq('f.fileName', ':fileName'))
            ->andWhere($qb->expr()->eq('f.season', ':seasonId'))
            ->setParameter('fileName', $fileName)
            ->setParameter('seasonId', $seasonId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}