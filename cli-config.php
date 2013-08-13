<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationReader;

require_once 'vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';
require_once 'vendor/Neoco/src/Neoco/Model/BasicObject.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', realpath(__DIR__ . '/vendor/doctrine/common/lib'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', realpath(__DIR__ . '/vendor/doctrine/common/lib/vendor/doctrine-dbal/lib'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', realpath(__DIR__ . '/vendor/doctrine/common/lib/vendor/doctrine-common/lib'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', realpath(__DIR__ . '/vendor/doctrine/common/lib/vendor'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('BasicObject', realpath(__DIR__ . '/vendor/Neoco/src/Neoco/Model'));
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$cache = new \Doctrine\Common\Cache\ApcCache();
$config->setMetadataCacheImpl($cache);
AnnotationRegistry::registerFile(realpath(__DIR__ . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'));
$reader = new AnnotationReader();
$driverImpl = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, array(__DIR__ . "/module/Application/src/Application/Model/Entities"));
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);
$config->setProxyDir(__DIR__ . '/data/DoctrineORMModule/Proxy');
$config->setProxyNamespace('DoctrineORMModule\Proxy');
$config->setAutoGenerateProxyClasses(false);

$connectionOptions = array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'port' => '3306',
    'user' => 'root',
    'password' => '',
    'dbname' => 'sp5'
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));


//C:\WebServers\home\zend.loc\www>"vendor/bin/doctrine.bat"  orm:convert-mapping --from-database xml module/Application/mappings --force
//C:\WebServers\home\zend.loc\www>"vendor/bin/doctrine.bat"  orm:generate-entities --generate-annotations="true" module/Application/mappings