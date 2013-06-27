<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class FooterPageDAO extends AbstractDAO {

    /**
     * @var FooterPageDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return FooterPageDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new FooterPageDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\FooterPage';
    }

    public function getFooterPageByTypeAndLanguage($type, $languageId, $hydrate = false, $skipCache = false)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('fp')
            ->from($this->getRepositoryName(), 'fp')
            ->where($qb->expr()->eq('fp.type',':type'))->setParameter('type', $type)
            ->andWhere($qb->expr()->eq('fp.language',':language'))->setParameter('language', $languageId);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    public function getFooterPageByType($type,$hydrate = false, $skipCache = false)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('fp, l')
            ->from($this->getRepositoryName(), 'fp')
            ->join('fp.language', 'l')
            ->where($qb->expr()->eq('fp.type',':type'))->setParameter('type', $type);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}
