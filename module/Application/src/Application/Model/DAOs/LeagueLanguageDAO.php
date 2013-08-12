<?php

namespace Application\Model\DAOs;

use \Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LeagueLanguageDAO extends AbstractDAO {

    /**
     * @var LeagueLanguageDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LeagueLanguageDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LeagueLanguageDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\LeagueLanguage';
    }

    /**
     * @param int $leagueId
     * @param int $languageId
     * @param bool $skipCache
     * @return string
     */
    public function getLeagueDisplayName($leagueId, $languageId, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ll.displayName')
            ->from($this->getRepositoryName(), 'll')
            ->where($qb->expr()->eq('ll.league', ':leagueId'))->setParameter('leagueId', $leagueId)
            ->andWhere($qb->expr()->eq('ll.language', ':languageId'))->setParameter('languageId', $languageId);
        return $this->getQuery($qb, $skipCache)->getSingleScalarResult();
    }

}