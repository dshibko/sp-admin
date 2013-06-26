<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class LogotypeDAO extends AbstractDAO {

    /**
     * @var LogotypeDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LogotypeDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LogotypeDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return 'Application\Model\Entities\Logotype';
    }

    /**
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getLogotypeByLanguage($languageId, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from($this->getRepositoryName(), 'l')
            ->andWhere($qb->expr()->eq('l.language',':language'))->setParameter('language', $languageId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }
}
