<?php

namespace Application\Manager;

use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class CacheManager extends BasicManager {

    /**
     * @var CacheManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CacheManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new CacheManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param bool $skipCache
     * @return bool
     */
    public function getSkipCache($skipCache) {
        $request = $this->getServiceLocator()->get('request');
        $config = $this->getServiceLocator()->get('config');
        if (!$request instanceof \Zend\Console\Request &&
            array_key_exists('skip-cache-uri-patterns', $config) && !empty($config['skip-cache-uri-patterns'])) {
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
     * @param string $cacheKey
     * @param string $entity
     * @return void
     */
    public function addCacheKeyToEntity($cacheKey, $entity) {
        $entityCacheKey = md5($entity);
        $cacheKeys = array();
        if ($this->getCacheProvider()->contains($entityCacheKey))
            $cacheKeys = $this->getCacheProvider()->fetch($entityCacheKey);
        $cacheKeys [] = $cacheKey;
        $this->getCacheProvider()->save($entityCacheKey, $cacheKeys);
    }

    public function clearEntityCache($entity) {
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
    public function generateCacheKey() {
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
     * @var \Doctrine\Common\Cache\CacheProvider
     */
    private $cacheProvider;

    /**
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    public function getCacheProvider() {
        if (!isset($this->cacheProvider))
            $this->cacheProvider = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getConfiguration()->getResultCacheImpl();
        return $this->cacheProvider;
    }

}