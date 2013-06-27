<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class AchievementBlockDAO extends AbstractDAO {

    /**
     * @var AchievementBlockDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return AchievementBlockDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new AchievementBlockDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\AchievementBlock';
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getAchievementBlocks($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ab, sc')
            ->from($this->getRepositoryName(), 'ab')
            ->join('ab.shareCopies', 'sc')
            ->orderBy('ab.weight', 'ASC')
            ->addOrderBy('ab.id', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    /**
     * @param $type
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getAchievementBlockByType($type, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ab, sc')
            ->from($this->getRepositoryName(), 'ab')
            ->join('ab.shareCopies', 'sc')
            ->where($qb->expr()->eq('ab.type', ':type'))->setParameter('type', $type)
            ->orderBy('ab.weight', 'ASC')
            ->addOrderBy('ab.id', 'ASC');
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}
