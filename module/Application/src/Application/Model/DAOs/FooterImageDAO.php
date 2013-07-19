<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Application\Model\Entities\Language;
use Zend\ServiceManager\ServiceLocatorInterface;

class FooterImageDAO extends AbstractDAO {

    /**
     * @var FooterImageDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return FooterImageDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new FooterImageDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\FooterImage';
    }

    /**
     * @param \Application\Model\Entities\Language $language
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getFooterImages(Language $language, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('f')
            ->from($this->getRepositoryName(), 'f')
            ->where($qb->expr()->eq('f.language', ':language'))->setParameter('language', $language->getId())
            ->orderBy("f.id", "ASC");
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }


}
