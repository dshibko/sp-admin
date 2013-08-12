<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LanguageGameplayContentDAO extends AbstractDAO {

    /**
     * @var LanguageGameplayContentDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LanguageGameplayContentDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LanguageGameplayContentDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\LanguageGameplayContent';
    }

    /**
     * @param \Application\Model\Entities\Language $language
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getLanguageGameplayBlocks($language, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('g, f')
            ->from($this->getRepositoryName(), 'g')
            ->join('g.foregroundImage', 'f')
            ->where($qb->expr()->eq('g.language', $language->getId()))
            ->orderBy("g.order", "ASC");
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
