<?php

namespace Application\Manager;

use Application\Model\DAOs\LeagueUserDAO;
use \Application\Model\Entities\Prize;
use \Application\Model\DAOs\PrizeDAO;
use \Application\Model\DAOs\SeasonRegionDAO;
use \Application\Model\Entities\SeasonRegion;
use \Application\Model\DAOs\LeagueDAO;
use \Application\Model\Entities\League;
use \Application\Model\Entities\Season;
use \Application\Model\DAOs\SeasonDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class SeasonManager extends BasicManager {

    const GLOBAL_LEAGUE_PREFIX = 'Global ';
    const REGIONAL_LEAGUE_PREFIX = 'Regional ';

    /**
     * @var SeasonManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return SeasonManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new SeasonManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllSeasons($hydrate = false, $skipCache = false) {
        return SeasonDAO::getInstance($this->getServiceLocator())->getAllSeasons($hydrate, $skipCache);
    }

    public function getAllNotFinishedSeasons($hydrate = false, $skipCache = false) {
        return SeasonDAO::getInstance($this->getServiceLocator())->getAllNotFinishedSeasons($hydrate, $skipCache);
    }

    /**
     * @param array $fields
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAllSeasonsByFields(array $fields, $hydrate = false, $skipCache = false)
    {
        return SeasonDAO::getInstance($this->getServiceLocator())->getAllSeasonsByFields($fields, $hydrate, $skipCache);
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return Season
     */
    public function getSeasonById($id, $hydrate = false, $skipCache = false) {
        return SeasonDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    public function createSeason($displayName, $startDate, $endDate, $feederId, $regionsData) {
        $season = new Season();
        $season->setDisplayName($displayName);
        $season->setStartDate($startDate);
        $season->setEndDate($endDate);
        $season->setFeederId($feederId);

        $seasonDAO = SeasonDAO::getInstance($this->getServiceLocator());
        $seasonDAO->save($season, false, false);

        $globalLeague = new League();
        $globalLeague->setDisplayName(self::GLOBAL_LEAGUE_PREFIX . $season->getDisplayName());
        $globalLeague->setStartDate($season->getStartDate());
        $globalLeague->setEndDate($season->getEndDate());
        $globalLeague->setSeason($season);
        $globalLeague->setType(League::GLOBAL_TYPE);
        $globalLeague->setCreator(ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser());
        $globalLeague->setCreationDate(new \DateTime());

        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $leagueDAO->save($globalLeague, false, false);

        $seasonRegionDAO = SeasonRegionDAO::getInstance($this->getServiceLocator());
        $prizeDAO = PrizeDAO::getInstance($this->getServiceLocator());

        $regionalLeagues = array();
        foreach ($regionsData as $id => $regionRow) {
            $region = RegionManager::getInstance($this->getServiceLocator())->getNonHydratedRegionFromArray($id);
            if (!$region) continue;

            $seasonRegion = new SeasonRegion();
            $seasonRegion->setSeason($season);
            $seasonRegion->setRegion($region);
            $seasonRegion->setDisplayName($regionRow['displayName']);
            $seasonRegion->setTerms($regionRow['terms']);
            $seasonRegionDAO->save($seasonRegion, false, false);

            $prize = new Prize();
            $prize->setLeague($globalLeague);
            $prize->setRegion($region);
            $prize->setPrizeImage($regionRow['prizeImagePath']);
            $prize->setPrizeTitle($regionRow['prizeTitle']);
            $prize->setPrizeDescription($regionRow['prizeDescription']);
            $prize->setPostWinImage($regionRow['postWinImagePath']);
            $prize->setPostWinTitle($regionRow['postWinTitle']);
            $prize->setPostWinDescription($regionRow['postWinDescription']);

            $prizeDAO->save($prize, false, false);

            $regionLeague = new League();
            $regionLeague->setDisplayName(self::REGIONAL_LEAGUE_PREFIX . $season->getDisplayName() . " " . $region->getDisplayName());
            $regionLeague->setStartDate($season->getStartDate());
            $regionLeague->setEndDate($season->getEndDate());
            $regionLeague->setSeason($season);
            $regionLeague->addRegion($region);
            $regionLeague->setType(League::REGIONAL_TYPE);
            $regionLeague->setCreator(ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser());
            $regionLeague->setCreationDate(new \DateTime());

            $leagueDAO->save($regionLeague, false, false);

            $regionalLeagues [$id] = $regionLeague;
        }

        $seasonDAO->flush();

        $userManager = UserManager::getInstance($this->getServiceLocator());
        $userManager->registerLeagueUsers($globalLeague);
        foreach ($regionalLeagues as $regionId => $regionalLeague)
            $userManager->registerLeagueUsers($regionalLeague, $regionId);

        $seasonDAO->clearCache();
        $leagueDAO->clearCache();
        $seasonRegionDAO->clearCache();
        $prizeDAO->clearCache();
        LeagueUserDAO::getInstance($this->getServiceLocator())->clearCache();

        return $season;
    }

    public function updateSeason($displayName, $startDate, $endDate, $feederId, $regionsData, $id) {
        $seasonDAO = SeasonDAO::getInstance($this->getServiceLocator());
        $season = $seasonDAO->findOneById($id);
        $season->setDisplayName($displayName);
        if ($startDate != null && $endDate != null) {
            $season->setStartDate($startDate);
            $season->setEndDate($endDate);
        }
        $season->setFeederId($feederId);

        $seasonDAO->save($season, false, false);

        $globalLeague = $season->getGlobalLeague();
        $globalLeague->setDisplayName(self::GLOBAL_LEAGUE_PREFIX . $season->getDisplayName());
        $globalLeague->setStartDate($season->getStartDate());
        $globalLeague->setEndDate($season->getEndDate());

        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $leagueDAO->save($globalLeague, false, false);

        $seasonRegionDAO = SeasonRegionDAO::getInstance($this->getServiceLocator());
        $prizeDAO = PrizeDAO::getInstance($this->getServiceLocator());

        foreach ($regionsData as $id => $regionRow) {
            $region = RegionManager::getInstance($this->getServiceLocator())->getNonHydratedRegionFromArray($id);
            if (!$region) continue;

            $seasonRegion = $season->getSeasonRegionByRegionId($region->getId());
            if ($seasonRegion == null) {
                $seasonRegion = new SeasonRegion();
                $seasonRegion->setSeason($season);
                $seasonRegion->setRegion($region);
            }
            $seasonRegion->setDisplayName($regionRow['displayName']);
            $seasonRegion->setTerms($regionRow['terms']);

            $seasonRegionDAO->save($seasonRegion, false, false);

            $prize = $globalLeague->getPrizeByRegionId($region->getId());
            if ($prize == null) {
                $prize = new Prize();
                $prize->setLeague($globalLeague);
                $prize->setRegion($region);
            }
            if (!empty($regionRow['prizeImagePath']))
                $prize->setPrizeImage($regionRow['prizeImagePath']);
            $prize->setPrizeTitle($regionRow['prizeTitle']);
            $prize->setPrizeDescription($regionRow['prizeDescription']);
            if (!empty($regionRow['postWinImagePath']))
                $prize->setPostWinImage($regionRow['postWinImagePath']);
            $prize->setPostWinTitle($regionRow['postWinTitle']);
            $prize->setPostWinDescription($regionRow['postWinDescription']);

            $prizeDAO->save($prize, false, false);

            $regionLeague = $season->getRegionalLeagueByRegionId($region->getId());
            if ($regionLeague == null) {
                $regionLeague = new League();
                $regionLeague->setType(League::REGIONAL_TYPE);
                $regionLeague->addRegion($region);
                $regionLeague->setCreationDate(new \DateTime());
                $regionLeague->setCreator(ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser());
                $regionLeague->setSeason($season);
            }
            $regionLeague->setDisplayName(self::REGIONAL_LEAGUE_PREFIX . $season->getDisplayName() . " " . $region->getDisplayName());
            $regionLeague->setStartDate($season->getStartDate());
            $regionLeague->setEndDate($season->getEndDate());

            $leagueDAO->save($regionLeague, false, false);
        }

        $seasonDAO->flush();

        $seasonDAO->clearCache();
        $leagueDAO->clearCache();
        $seasonRegionDAO->clearCache();
        $prizeDAO->clearCache();

        return $season;
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param int $seasonId
     * @return bool
     */
    public function checkDates($startDate, $endDate, $seasonId = -1) {
        $seasonDAO = SeasonDAO::getInstance($this->getServiceLocator());
        return $seasonDAO->checkSeasonDatesInterval($startDate, $endDate, $seasonId);
    }

    public function deleteSeason($id) {
        $seasonDAO = SeasonDAO::getInstance($this->getServiceLocator());
        $season = $seasonDAO->findOneById($id);
        $imageManager = ImageManager::getInstance($this->getServiceLocator());
        foreach ($season->getLeagues() as $league) {
            if ($league->getLogoPath() != null)
                $imageManager->deleteImage($league->getLogoPath());
            if ($league->getPrizes() != null && is_array($league->getPrizes()) && count($league->getPrizes()) > 0) {
                foreach ($league->getPrizes() as $prize) {
                    if ($prize->getPrizeImage() != null)
                        $imageManager->deleteImage($prize->getPrizeImage());
                    if ($prize->getPostWinImage() != null)
                        $imageManager->deleteImage($prize->getPostWinImage());
                }
            }
        }
        $seasonDAO->remove($season);
    }

    public function getCurrentAndFutureSeasons($hydrate = false, $skipCache = false)
    {
        return SeasonDAO::getInstance($this->getServiceLocator())->getCurrentAndFutureSeasons($hydrate, $skipCache);
    }

}