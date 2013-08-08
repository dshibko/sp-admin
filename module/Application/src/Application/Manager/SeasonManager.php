<?php

namespace Application\Manager;

use Application\Model\DAOs\LeagueUserDAO;
use \Application\Model\Entities\LeagueLanguage;
use \Application\Model\DAOs\LeagueLanguageDAO;
use \Application\Model\DAOs\SeasonLanguageDAO;
use \Application\Model\Entities\SeasonLanguage;
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

    public function createSeason($displayName, $startDate, $endDate, $feederId, $regionalLeaguesData, $globalLeagueData, $seasonData) {
        $season = new Season();
        $season->setDisplayName($displayName);
        $season->setStartDate($startDate);
        $season->setEndDate($endDate);
        $season->setFeederId($feederId);

        foreach ($seasonData as $languageId => $seasonLanguageArr) {
            $language = LanguageManager::getInstance($this->getServiceLocator())->getNonHydratedLanguageFromArray($languageId);
            if (!$language) continue;
            $seasonLanguage = new SeasonLanguage();
            $seasonLanguage->setLanguage($language);
            $seasonLanguage->setSeason($season);
            $seasonLanguage->setDisplayName($seasonLanguageArr['seasonDisplayName']);
            $seasonLanguage->setTerms($seasonLanguageArr['terms']);
            $season->addSeasonLanguage($seasonLanguage);
        }

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

        foreach ($globalLeagueData as $languageId => $globalLeagueLanguage) {
            $language = LanguageManager::getInstance($this->getServiceLocator())->getNonHydratedLanguageFromArray($languageId);
            if (!$language) continue;
            $leagueLanguage = new LeagueLanguage();
            $leagueLanguage->setLanguage($language);
            $leagueLanguage->setLeague($globalLeague);
            $this->fillInLeagueLanguage($leagueLanguage, $globalLeagueLanguage);
            $globalLeague->addLeagueLanguage($leagueLanguage);
        }

        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $leagueDAO->save($globalLeague, false, false);

        $seasonLanguageDAO = SeasonLanguageDAO::getInstance($this->getServiceLocator());
        $leagueLanguageDAO = LeagueLanguageDAO::getInstance($this->getServiceLocator());

        $regionalLeagues = array();
        foreach ($regionalLeaguesData as $regionId => $regionRow) {
            $region = RegionManager::getInstance($this->getServiceLocator())->getNonHydratedRegionFromArray($regionId);
            if (!$region) continue;

            $regionalLeague = new League();
            $regionalLeague->setDisplayName(self::REGIONAL_LEAGUE_PREFIX . $season->getDisplayName() . " " . $region->getDisplayName());
            $regionalLeague->setStartDate($season->getStartDate());
            $regionalLeague->setEndDate($season->getEndDate());
            $regionalLeague->setSeason($season);
            $regionalLeague->addRegion($region);
            $regionalLeague->setType(League::REGIONAL_TYPE);
            $regionalLeague->setCreator(ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser());
            $regionalLeague->setCreationDate(new \DateTime());

            foreach ($regionRow as $languageId => $languageRow) {
                $language = LanguageManager::getInstance($this->getServiceLocator())->getNonHydratedLanguageFromArray($languageId);
                if (!$language) continue;
                $regionalLeagueLanguage = new LeagueLanguage();
                $regionalLeagueLanguage->setLeague($regionalLeague);
                $regionalLeagueLanguage->setLanguage($language);
                $this->fillInLeagueLanguage($regionalLeagueLanguage, $languageRow);
                $regionalLeague->addLeagueLanguage($regionalLeagueLanguage);
            }

            $leagueDAO->save($regionalLeague, false, false);

            $regionalLeagues [$regionId] = $regionalLeague;
        }

        $seasonDAO->flush();

        $seasonDAO->clearCache();
        $leagueDAO->clearCache();
        $seasonLanguageDAO->clearCache();
        $leagueLanguageDAO->clearCache();
        LeagueUserDAO::getInstance($this->getServiceLocator())->clearCache();

        return $season;
    }

    public function updateSeason($displayName, $startDate, $endDate, $feederId, $regionalLeaguesData, $globalLeagueData, $seasonData, $id) {
        $seasonDAO = SeasonDAO::getInstance($this->getServiceLocator());
        $season = $seasonDAO->findOneById($id);
        $season->setDisplayName($displayName);
        if ($startDate != null && $endDate != null) {
            $season->setStartDate($startDate);
            $season->setEndDate($endDate);
        }
        $season->setFeederId($feederId);

        foreach ($seasonData as $languageId => $seasonLanguageArr) {
            $language = LanguageManager::getInstance($this->getServiceLocator())->getNonHydratedLanguageFromArray($languageId);
            if (!$language) continue;
            $seasonLanguage = $season->getSeasonLanguageByLanguageId($languageId);
            if ($seasonLanguage === null) {
                $seasonLanguage = new SeasonLanguage();
                $seasonLanguage->setLanguage($language);
                $seasonLanguage->setSeason($season);
                $season->addSeasonLanguage($seasonLanguage);
            }
            $seasonLanguage->setDisplayName($seasonLanguageArr['seasonDisplayName']);
            $seasonLanguage->setTerms($seasonLanguageArr['terms']);
        }

        $seasonDAO->save($season, false, false);

        $globalLeague = $season->getGlobalLeague();
        $globalLeague->setDisplayName(self::GLOBAL_LEAGUE_PREFIX . $season->getDisplayName());
        $globalLeague->setStartDate($season->getStartDate());
        $globalLeague->setEndDate($season->getEndDate());

        foreach ($globalLeagueData as $languageId => $globalLeagueLanguageRow) {
            $language = LanguageManager::getInstance($this->getServiceLocator())->getNonHydratedLanguageFromArray($languageId);
            if (!$language) continue;
            $leagueLanguage = $globalLeague->getLeagueLanguageByLanguageId($languageId);
            if ($leagueLanguage === null) {
                $leagueLanguage = new LeagueLanguage();
                $leagueLanguage->setLeague($globalLeague);
                $leagueLanguage->setLanguage($language);
                $globalLeague->addLeagueLanguage($leagueLanguage);
            }
            $this->fillInLeagueLanguage($leagueLanguage, $globalLeagueLanguageRow);
        }

        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $leagueDAO->save($globalLeague, false, false);

        $seasonLanguageDAO = SeasonLanguageDAO::getInstance($this->getServiceLocator());
        $leagueLanguageDAO = LeagueLanguageDAO::getInstance($this->getServiceLocator());

        foreach ($regionalLeaguesData as $regionId => $regionRow) {
            $regionalLeague = $season->getRegionalLeagueByRegionId($regionId);
            if ($regionalLeague === null) continue;

            foreach ($regionRow as $languageId => $languageRow) {

                $language = LanguageManager::getInstance($this->getServiceLocator())->getNonHydratedLanguageFromArray($languageId);
                if (!$language) continue;
                $regionalLeagueLanguage = $regionalLeague->getLeagueLanguageByLanguageId($languageId);
                if ($regionalLeagueLanguage === null) {
                    $regionalLeagueLanguage = new LeagueLanguage();
                    $regionalLeagueLanguage->setLeague($regionalLeague);
                    $regionalLeagueLanguage->setLanguage($language);
                    $regionalLeague->addLeagueLanguage($regionalLeagueLanguage);
                }
                $this->fillInLeagueLanguage($regionalLeagueLanguage, $languageRow);
            }
            $regionalLeague->setDisplayName(self::REGIONAL_LEAGUE_PREFIX . $season->getDisplayName() . " " . $regionalLeague->getRegion()->getDisplayName());
            $regionalLeague->setStartDate($season->getStartDate());
            $regionalLeague->setEndDate($season->getEndDate());

            $leagueDAO->save($regionalLeague, false, false);
        }

        $seasonDAO->flush();

        $seasonDAO->clearCache();
        $leagueDAO->clearCache();
        $seasonLanguageDAO->clearCache();
        $leagueLanguageDAO->clearCache();

        return $season;
    }

    private function fillInLeagueLanguage(LeagueLanguage $leagueLanguage, array $dataArr) {
        $leagueLanguage->setDisplayName($dataArr['leagueDisplayName']);
        $leagueLanguage->setPrizeTitle($dataArr['prizeTitle']);
        if (!empty($dataArr['prizeImagePath']))
            $leagueLanguage->setPrizeImage($dataArr['prizeImagePath']);
        $leagueLanguage->setPrizeDescription($dataArr['prizeDescription']);
        $leagueLanguage->setPostWinTitle($dataArr['postWinTitle']);
        if (!empty($dataArr['postWinImagePath']))
            $leagueLanguage->setPostWinImage($dataArr['postWinImagePath']);
        $leagueLanguage->setPostWinDescription($dataArr['postWinDescription']);
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
            if ($league->getLeagueLanguages() != null && is_array($league->getLeagueLanguages()) && count($league->getLeagueLanguages()) > 0) {
                foreach ($league->getLeagueLanguages() as $leagueLanguage) {
                    if ($leagueLanguage->getPrizeImage() != null)
                        $imageManager->deleteImage($leagueLanguage->getPrizeImage());
                    if ($leagueLanguage->getPostWinImage() != null)
                        $imageManager->deleteImage($leagueLanguage->getPostWinImage());
                }
            }
        }
        $seasonDAO->remove($season);
    }

    public function getCurrentAndFutureSeasons($hydrate = false, $skipCache = false)
    {
        return SeasonDAO::getInstance($this->getServiceLocator())->getCurrentAndFutureSeasons($hydrate, $skipCache);
    }

    public function getSeasonDisplayName($seasonId) {
        $seasonLanguageDAO = SeasonLanguageDAO::getInstance($this->getServiceLocator());
        $language = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser()->getLanguage();
        $displayName = $seasonLanguageDAO->getSeasonDisplayName($seasonId, $language->getId());
        if (empty($displayName) && !$language->getIsDefault()) {
            $defaultLanguage = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
            $displayName = $seasonLanguageDAO->getSeasonDisplayName($seasonId, $defaultLanguage->getId());
        }
        return $displayName;
    }

}