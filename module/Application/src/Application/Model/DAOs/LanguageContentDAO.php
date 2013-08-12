<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LanguageContentDAO extends AbstractDAO {

    /**
     * @var LanguageContentDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LanguageContentDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LanguageContentDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\LanguageContent';
    }

    /**
     * @param \Application\Model\Entities\Language $language
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\LanguageContent|array
     */
    public function getLanguageContent($language, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c, f, b')
            ->from($this->getRepositoryName(), 'c')
            ->leftJoin('c.heroForegroundImage', 'f')
            ->leftJoin('c.heroBackgroundImage', 'b')
            ->where($qb->expr()->eq('c.language', $language->getId()));
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }


}
