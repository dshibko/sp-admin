<?php

namespace Application\Manager;

use \Application\Model\DAOs\RegionDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class RegionManager extends BasicManager {

    /**
     * @var RegionManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return RegionManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new RegionManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function getSelectedRegion() {
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $user = $applicationManager->getCurrentUser();
        $country = null;
        if ($user == null) {
            $userManager = UserManager::getInstance($this->getServiceLocator());
            $isoCode = $userManager->getUserGeoIpIsoCode();
            if ($isoCode != null)
                $country = $applicationManager->getCountryByISOCode($isoCode);
        } else
            $country = $user->getCountry();
        if ($country == null || $country->getRegion() == null)
            return $this->getDefaultRegion();
        else
            return $country->getRegion();
    }

    /**
     * @return \Application\Model\Entities\Region
     */
    public function getDefaultRegion()
    {
        return RegionDAO::getInstance($this->getServiceLocator())->getDefaultRegion();
    }

    public function getRegionById($id, $hydrate = false, $skipCache = false) {
        return RegionDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    public function getAllRegions($hydrate = false, $skipCache = false) {
        return RegionDAO::getInstance($this->getServiceLocator())->getAllRegions($hydrate, $skipCache);
    }

    private $nonHydratedRegions;

    /**
     * @param $id
     * @return bool|\Application\Model\Entities\Region
     */
    public function getNonHydratedRegionFromArray($id) {
        if ($this->nonHydratedRegions == null)
            $this->nonHydratedRegions = $this->getAllRegions(false);
        foreach ($this->nonHydratedRegions as $region)
            if ($region->getId() == $id)
                return $region;
        return false;
    }

    /**
     * @param \Application\Model\Entities\Region $region
     */
    public function setDefaultRegion($region) {
        $regionDAO = RegionDAO::getInstance($this->getServiceLocator());
        $oldDefaultRegion = $this->getDefaultRegion();
        if ($oldDefaultRegion->getId() != $region->getId()) {
            $oldDefaultRegion->setIsDefault(false);
            $region->setIsDefault(true);
            $regionDAO->save($oldDefaultRegion, false, false);
            $regionDAO->save($region, false, false);
            $regionDAO->flush();
            $regionDAO->clearCache();
        }
    }

}