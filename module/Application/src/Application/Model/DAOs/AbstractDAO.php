<?php

namespace Application\Model\DAOs;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractDAO implements ServiceLocatorAwareInterface {

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
                $this->clearEntityCache();
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
                $this->clearEntityCache();
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
            ->where($qb->expr()->eq('e.id', $id));
        return $this->getQuery($qb, __FUNCTION__, func_get_args(), $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
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
        return $this->getQuery($qb, __FUNCTION__, func_get_args(), $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
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
            return $this->getQuery($qb, __FUNCTION__, func_get_args(), $skipCache)->getSingleScalarResult();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param bool $skipCache
     * @return bool
     */
    private function getSkipCache($skipCache) {
        $request = $this->getServiceLocator()->get('request');
        $config = $this->getServiceLocator()->get('config');
        if (array_key_exists('skip-cache-uri-patterns', $config) && !empty($config['skip-cache-uri-patterns'])) {
            $skipCacheUriPatterns = $config['skip-cache-uri-patterns'];
            if (!is_array($skipCacheUriPatterns))
                $skipCacheUriPatterns = array($skipCacheUriPatterns);
            foreach ($skipCacheUriPatterns as $aSkipCacheUriPattern) {
                preg_match("/" . str_replace("*", ".*", str_replace("/", "\\/", $aSkipCacheUriPattern)) . "/", $request->getRequestUri(), $match);
                if (!empty($match) && array_shift($match) == $request->getRequestUri())
                    return true;
            }
        }
        return $skipCache;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param bool $skipCache
     * @return \Doctrine\ORM\Query
     */
    protected function getQuery(\Doctrine\ORM\QueryBuilder $qb, $skipCache = false) {
        $cacheKey = $this->generateCacheKey();
        $allEntities = $this->getInvolvedEntities($qb);
        if (!($skipCache = $this->getSkipCache($skipCache)) && !$this->getCacheProvider()->contains($cacheKey))
            foreach ($allEntities as $entity)
                $this->addCacheKeyToEntity($cacheKey, $entity);
        return $qb->getQuery()->useResultCache(!$skipCache, null, $cacheKey);
    }

    /**
     * @param string $cacheKey
     * @param string $entity
     * @return void
     */
    private function addCacheKeyToEntity($cacheKey, $entity) {
        $entityCacheKey = md5($entity);
        $cacheKeys = array();
        if ($this->getCacheProvider()->contains($entityCacheKey))
            $cacheKeys = $this->getCacheProvider()->fetch($entityCacheKey);
        $cacheKeys [] = $cacheKey;
        $this->getCacheProvider()->save($entityCacheKey, $cacheKeys);
    }

    protected function clearEntityCache($entity = null) {
        if ($entity == null) $entity = $this->getRepositoryName();
        $entityCacheKey = md5($entity);
        if ($this->getCacheProvider()->contains($entityCacheKey)) {
            $cacheKeys = $this->getCacheProvider()->fetch($entityCacheKey);
            if ($cacheKeys !== false && is_array($cacheKeys))
                foreach ((array)$cacheKeys as $cacheKey)
                    if ($this->getCacheProvider()->contains($cacheKey))
                        $this->getCacheProvider()->delete($cacheKey);
        }
        if ($this->getCacheProvider()->contains($entityCacheKey))
            $this->getCacheProvider()->delete($entityCacheKey);
    }

    /**
     * @return string
     */
    private function generateCacheKey() {
        $backTrace = debug_backtrace();
        if (count($backTrace) < 3)
            throw new \Exception('AbstractDAO::generateCacheKey was called from wrong place');
        $caller = array_shift((array_slice($backTrace, 2, 1)));
        $class = get_class($caller['object']);
        $method = $caller['function'];
        $args = $caller['args'];
        $key = md5($class . $method . serialize($args));
        return $key;
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
     * @var \Doctrine\Common\Cache\CacheProvider
     */
    private $cacheProvider;

    /**
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    private function getCacheProvider() {
        if (!isset($this->cacheProvider))
            $this->cacheProvider = $this->getEntityManager()->getConfiguration()->getResultCacheImpl();
        return $this->cacheProvider;
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
    protected function getRepository() {
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

}