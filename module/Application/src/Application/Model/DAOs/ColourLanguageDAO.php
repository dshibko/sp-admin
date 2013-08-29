<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class ColourLanguageDAO extends AbstractDAO {
    /**
     * @var ColourLanguageDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ColourLanguageDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ColourLanguageDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\ColourLanguage';
    }

    /**
     * @param $type
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getColourByTypeAndLanguageId($type, $languageId, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('cl')
            ->from($this->getRepositoryName(), 'cl')
            ->where($qb->expr()->eq('cl.type', ':type'))->setParameter('type', $type)
            ->andWhere($qb->expr()->eq('cl.language', ':languageId'))->setParameter('languageId', $languageId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

    /**
     * @param $type
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getDefaultColoursByType($type, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('cl')
            ->from($this->getRepositoryName(), 'cl')
            ->where($qb->expr()->eq('cl.type', ':type'))->setParameter('type', $type);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }
}
