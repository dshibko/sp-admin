<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultReportContentDAO extends AbstractDAO {

    /**
     * @var DefaultReportContentDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return DefaultReportContentDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new DefaultReportContentDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\DefaultReportContent';
    }

    /**
     * @param $languageId
     * @param $type
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getDefaultReportContentByTypeAndLanguage($languageId, $type, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('drc')
            ->from($this->getRepositoryName(), 'drc')
            ->where($qb->expr()->eq('drc.reportType', ':type'))->setParameter('type', $type)
            ->andWhere($qb->expr()->eq('drc.language', ':language'))->setParameter('language', $languageId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}
