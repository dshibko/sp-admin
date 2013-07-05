<?php

namespace Application\Model\DAOs;

use \Application\Manager\CacheManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractDAO implements ServiceLocatorAwareInterface {

    public function flush() {
        $this->getEntityManager()->flush();
    }

    public function detach($entity) {
        $this->getEntityManager()->detach($entity);
    }

    public function clearCache() {
        $this->getCacheManager()->clearEntityCache($this->getRepositoryName());
    }

    /**
     * @throws \Exception
     * @param object $entity
     * @param bool $flush
     * @param bool $clearCache
     */
    public function remove($entity, $flush = true, $clearCache = true) {
        try {
            $this->getEntityManager()->remove($entity);
            if ($flush)
                $this->getEntityManager()->flush();
            if ($clearCache)
                $this->clearCache();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws \Exception
     * @param object $entity
     * @param bool $flush
     * @param bool $clearCache
     */
    public function save($entity, $flush = true, $clearCache = true) {
        try {
            $this->getEntityManager()->persist($entity);
            if ($flush)
                $this->getEntityManager()->flush();
            if ($clearCache)
                $this->clearCache();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws \Exception
     * @param int $id
     * @param bool $clearCache
     */
    public function removeById($id, $clearCache = true) {
        try {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->delete($this->getRepositoryName(), 'e')
                ->where('e.id = :e_id')
                ->setParameter('e_id', $id);
            $qb->getQuery()->execute();
            if ($clearCache)
                $this->clearCache();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function findOneById($id, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from($this->getRepositoryName(), 'e')
            ->where($qb->expr()->eq('e.id', ':id'))->setParameter('id',$id);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param int $feederId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function findOneByFeederId($feederId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from($this->getRepositoryName(), 'e')
            ->where($qb->expr()->eq('e.feederId', ':feederId'))->setParameter('feederId',$feederId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function findAll($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from($this->getRepositoryName(), 'e');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param array $fields
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function findAllByFields(array $fields, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $selectArr = array();
        foreach ($fields as $field)
            $selectArr[] = 'e.' . $field;
        $qb->select(implode(',', $selectArr))
            ->from($this->getRepositoryName(), 'e');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param bool $skipCache
     * @return int
     * @throws \Exception
     */
    public function count($skipCache = false) {
        try {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('count(e.id)')
                ->from($this->getRepositoryName(), 'e');
            return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param bool $skipCache
     * @return \Doctrine\ORM\Query
     */
    protected function getQuery(\Doctrine\ORM\QueryBuilder $qb, $skipCache = false) {
        $cacheKey = $this->getCacheManager()->generateCacheKey();
        $allEntities = $this->getInvolvedEntities($qb);
        if (!($skipCache = $this->getCacheManager()->getSkipCache($skipCache)) && !$this->getCacheManager()->getCacheProvider()->contains($cacheKey))
            foreach ($allEntities as $entity)
                $this->getCacheManager()->addCacheKeyToEntity($cacheKey, $entity);
        return $qb->getQuery()->useResultCache(!$skipCache, null, $cacheKey);
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @return array
     */
    private function getInvolvedEntities(\Doctrine\ORM\QueryBuilder $qb) {
        $rootEntities = $qb->getRootEntities();
        $rootAliases = $qb->getRootAliases();
        $allEntities = array();
        $namespace = "\\";
        for ($i = 0; $i < count($rootEntities); $i++) {
            $entity = $rootEntities[$i];
            if ($namespace == "\\" && strpos($entity, "\\") !== false)
                $namespace = substr($entity, 0, strrpos($entity, "\\") + 1);
            $alias = $rootAliases[$i];
            $allEntities[$alias] = $entity;
        }
        $joins = $qb->getDQLPart('join');
        if (!empty($joins)) {
            $annotationReader = new \Doctrine\Common\Annotations\AnnotationReader();
            foreach ($joins as $key => $keyJoins)
                foreach ($keyJoins as $join)
                    if (array_key_exists($key, $allEntities)) {
                        $sourceEntity = $allEntities[$key];
                        $targetProperty = $join->getJoin();
                        if (strpos($targetProperty, ".") !== false)
                            $targetProperty = array_pop(explode(".", $targetProperty));
                        $propertyAnnotations = $annotationReader->getPropertyAnnotations(new \ReflectionProperty($sourceEntity, $targetProperty));
                        $propertyAnnotation = is_array($propertyAnnotations) ? array_shift($propertyAnnotations) : $propertyAnnotations;
                        if (property_exists($propertyAnnotation, "targetEntity")) {
                            $allEntities[$join->getAlias()] = $propertyAnnotation->targetEntity;
                            if (strpos($allEntities[$join->getAlias()], "\\") === false)
                                $allEntities[$join->getAlias()] = $namespace . $allEntities[$join->getAlias()];
                        }
                    }
        }
        return $allEntities;
    }

    /**
     * @static
     * @abstract
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return \Application\Model\DAOs\AbstractDAO
     */
    abstract static function getInstance(ServiceLocatorInterface $serviceLocatorInterface);

    /**
     * @abstract
     * @return string
     */
    abstract function getRepositoryName();

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository() {
        return $this->getEntityManager()->getRepository($this->getRepositoryName());
    }

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager() {

        if( !isset($this->em) )
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        return $this->em;

    }

    public function clearEntityManager() {
        $this->getEntityManager()->clear();
    }

    private function getCacheManager() {
        return CacheManager::getInstance($this->getServiceLocator());
    }

}