<?php

namespace Application\Manager;

use \Application\Model\DAOs\ShareCopyDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class ShareManager extends BasicManager
{

    /**
     * @var ShareManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ShareManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new ShareManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param string $target
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getCopyByTarget($target, $hydrate = false, $skipCache = false)
    {
        return ShareCopyDAO::getInstance($this->getServiceLocator())->getCopyByTarget($target, $hydrate, $skipCache);
    }

    public function saveShareCopy($id, $value, $flush = true, $clearCache = true) {
        $shareCopyDAO = ShareCopyDAO::getInstance($this->getServiceLocator());
        $shareCopy = $shareCopyDAO->findOneById($id);
        if ($shareCopy !== null) {
            $shareCopy->setCopy($value);
            $shareCopyDAO->save($shareCopy, $flush, $clearCache);
        }
    }

    public function flushAndClearCache() {
        $shareCopyDAO = ShareCopyDAO::getInstance($this->getServiceLocator());
        $shareCopyDAO->flush();
        $shareCopyDAO->clearCache();
    }

//    /**
//     * @param array $fields
//     * @param bool $hydrate
//     * @param bool $skipCache
//     * @return array
//     */
//    public function getAllCompetitionsByFields(array $fields, $hydrate = false, $skipCache = false)
//    {
//        return CompetitionDAO::getInstance($this->getServiceLocator())->getAllCompetitionsByFields($fields, $hydrate, $skipCache);
//    }
//
//    /**
//     * @param $id
//     * @param bool $hydrate
//     * @param bool $skipCache
//     * @return mixed
//     */
//    public function getCompetitionById($id, $hydrate = false, $skipCache = false)
//    {
//        return CompetitionDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
//    }
//

}