<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SeasonLanguageDAO extends AbstractDAO {

    /**
     * @var SeasonLanguageDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return SeasonLanguageDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new SeasonLanguageDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\SeasonLanguage';
    }

    /**
     * @param int $seasonId
     * @param int $languageId
     * @param bool $skipCache
     * @return string
     */
    public function getSeasonDisplayName($seasonId, $languageId, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('sl.displayName')
            ->from($this->getRepositoryName(), 'sl')
            ->where($qb->expr()->eq('sl.season', ':seasonId'))->setParameter('seasonId', $seasonId)
            ->andWhere($qb->expr()->eq('sl.language', ':languageId'))->setParameter('languageId', $languageId);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

}