<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Doctrine\ORM\Query\ResultSetMapping;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CountryDAO extends AbstractDAO {
    const UNITED_KINGDOM_ID = 95;
    const UNITED_STATES_ID = 1;
    /**
     * @var CountryDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return CountryDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new CountryDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\Country';
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     * @throws \Exception
     */
    public function getAllCountries($hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
            ->from($this->getRepositoryName(), 'c')
            ->orderBy('c.name', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

    public function getAllCountriesOrderedByIds(array $ids = array(self::UNITED_KINGDOM_ID, self::UNITED_STATES_ID), $sorting = 'DESC')
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('name','name');
        $order  = '';
        if (!empty($ids)){
            foreach($ids as $id){
                $order .= sprintf('id = %d %s, ',$id, $sorting);
            }
        }
        $query = $this->getEntityManager()
            ->createNativeQuery("
                SELECT
                    id,
                    name
                FROM
                  country
                ORDER BY
                  {$order} name ASC

            ", $rsm);
        return $this->prepareQuery($query, array($this->getRepositoryName()))->getArrayResult();
    }

    /**
     * @param bool $isoCode
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Country
     * @throws \Exception
     */
    public function getCountryByISOCode($isoCode, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c, r, l')
            ->from($this->getRepositoryName(), 'c')
            ->leftJoin('c.region', 'r')
            ->leftJoin('c.language', 'l')
            ->where('c.isoCode = :iso_code')
            ->setParameter('iso_code', $isoCode);
        return $this->getQuery($qb, $skipCache)->getOneOrNullResult($hydrate ? \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY : null);
    }

}
