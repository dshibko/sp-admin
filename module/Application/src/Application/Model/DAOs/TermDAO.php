<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Zend\ServiceManager\ServiceLocatorInterface;

class TermDAO extends AbstractDAO {

    /**
     * @var TermDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return TermDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new TermDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return 'Application\Model\Entities\Term';
    }

    /**
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTermsByLanguageId($languageId, $hydrate = false, $skipCache = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t.id,t.isRequired, t.isChecked,tc.copy')
            ->from($this->getRepositoryName(), 't')
            ->join('t.termCopies', 'tc')
            ->where($qb->expr()->eq('tc.language',':languageId'))->setParameter('languageId', $languageId);
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }
}
