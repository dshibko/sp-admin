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

    public function getUsers($regionId, $hydrate = false, $skipCache = false) {
        $regionDAO = RegionDAO::getInstance($this->getServiceLocator());
        return $regionDAO->getUsersByRegion($regionId, $hydrate, $skipCache);
    }

    /**
     * @param String $fieldsetName Name of the form fieldset class
     * @return array
     */
    public function getRegionsFieldsets($fieldsetName)
    {
        $regions = $this->getAllRegions(true);
        $regionFieldsets = array();
        foreach ($regions as $region){
            $regionFieldsets[] = new $fieldsetName($region);
        }
        return $regionFieldsets;
    }


    /**
     * @param array $regionFieldsets
     * @return array
     */
    public function getFeaturedPlayerRegionsData(array $regionFieldsets)
    {
        $regionsData = array();
        //Prepare regions data
        foreach ($regionFieldsets as $fieldset) {
            $region = $fieldset->getRegion();
            $regionsData[$region['id']] = array(
                'featured_player' => array(
                    'id' => $fieldset->get('featured_player')->getValue(),
                    'goals' => $fieldset->get('player_goals')->getValue(),
                    'matches_played' => $fieldset->get('player_matches_played')->getValue(),
                    'match_starts' => $fieldset->get('player_match_starts')->getValue(),
                    'minutes_played' => $fieldset->get('player_minutes_played')->getValue(),
                ),
            );
        }
        return $regionsData;
    }

    /**
     * @param array $regionFieldsets
     * @return array
     */
    public function getFeaturedGoalkeeperRegionsData(array $regionFieldsets)
    {
        $regionsData = array();
        //Prepare regions data
        foreach ($regionFieldsets as $fieldset) {
            $region = $fieldset->getRegion();
            $regionsData[$region['id']] = array(
                'featured_goalkeeper' => array(
                    'id' => $fieldset->get('featured_goalkeeper')->getValue(),
                    'saves' => $fieldset->get('goalkeeper_saves')->getValue(),
                    'matches_played' => $fieldset->get('goalkeeper_matches_played')->getValue(),
                    'penalty_saves' => $fieldset->get('goalkeeper_penalty_saves')->getValue(),
                    'clean_sheets'  => $fieldset->get('goalkeeper_clean_sheets')->getValue()
                ),
            );
        }
        return $regionsData;
    }

    /**
     * @param array $regionFieldsets
     * @return array
     */
    public function getFeaturedPredictionRegionsData(array $regionFieldsets)
    {
        $imageManager = ImageManager::getInstance($this->getServiceLocator());
        $regionsData = array();
        //Prepare regions data
        foreach ($regionFieldsets as $fieldset) {
            $region = $fieldset->getRegion();
            $regionsData[$region['id']] = array(
                'featured_prediction' => array(
                    'name' => $fieldset->get('prediction_name')->getValue(),
                    'copy' => $fieldset->get('prediction_copy')->getValue(),
                )
            );
            $predictionImage = $fieldset->get('prediction_image')->getValue();
            $pImage = ($predictionImage['error'] != UPLOAD_ERR_NO_FILE) ? $imageManager->saveUploadedImage($fieldset->get('prediction_image'), ImageManager::IMAGE_TYPE_REPORT) : null;
            if ($pImage){
                $regionsData[$region['id']]['featured_prediction']['image'] = $pImage;
            }
        }
        return $regionsData;
    }

    /**
     * @param array $regionFieldsets
     * @return array
     */
    public function getMatchReportRegionsData(array $regionFieldsets)
    {
        $imageManager = ImageManager::getInstance($this->getServiceLocator());
        $regionsData = array();
        //Prepare regions data
        foreach ($regionFieldsets as $fieldset) {
            $region = $fieldset->getRegion();
            $regionsData[$region['id']] = array(
                'match_report' => array(
                    'title' => $fieldset->get('title')->getValue(),
                    'intro' => $fieldset->get('intro')->getValue(),
                )
            );
            $headerImage = $fieldset->get('header_image')->getValue();
            //TODO resize background
            $hImage = ($headerImage['error'] != UPLOAD_ERR_NO_FILE) ? $imageManager->saveUploadedImage($fieldset->get('header_image'), ImageManager::IMAGE_TYPE_REPORT) : null;
            if ($hImage){
                $regionsData[$region['id']]['match_report']['header_image_path'] = $hImage;
            }
        }
        return $regionsData;
    }

}