<?php

namespace Application\Model\DAOs;

use Application\Model\DAOs\AbstractDAO;
use Application\Model\Entities\Language;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class HowToPlayContentDAO
 * @package Application\Model\DAOs
 */
class HowToPlayContentDAO extends AbstractDAO {

    /**
     * @var HowToPlayContentDAO
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return HowToPlayContentDAO
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new HowToPlayContentDAO();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    function getRepositoryName() {
        return '\Application\Model\Entities\HowToPlayContent';
    }


    /**
     * @param $language
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getLanguageHowToPlayBlocks(Language $language, $hydrate = false, $skipCache = false) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('h, f')
            ->from($this->getRepositoryName(), 'h')
            ->leftJoin('h.foregroundImage', 'f')
            ->where($qb->expr()->eq('h.language', $language->getId()))
            ->orderBy('h.order', 'ASC');
        return $this->getQuery($qb, $skipCache)->getResult($hydrate ? \Doctrine\ORM\Query::HYDRATE_ARRAY : null);
    }

}
