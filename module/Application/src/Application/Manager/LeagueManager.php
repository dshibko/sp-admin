<?php

namespace Application\Manager;

use Application\Model\DAOs\LeagueLanguageDAO;
use \Application\Model\DAOs\LeagueUserPlaceDAO;
use Application\Model\DAOs\PrivateLeagueDAO;
use \Application\Model\Entities\LeagueUserPlace;
use \Application\Model\Entities\LeagueUser;
use Application\Model\Entities\PrivateLeague;
use Application\Model\Entities\Season;
use Application\Model\Entities\User;
use \Doctrine\Common\Collections\ArrayCollection;
use \Application\Model\Entities\LeagueRegion;
use \Application\Model\Entities\LeagueLanguage;
use \Application\Model\DAOs\RegionDAO;
use \Application\Model\Entities\League;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\DAOs\LeagueUserDAO;
use \Application\Model\DAOs\SeasonDAO;
use \Application\Model\DAOs\LeagueDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class LeagueManager extends BasicManager {

    const GLOBAL_LEAGUE_NAME = 'Global';
    const REGIONAL_LEAGUE_NAME = 'Regional';
    const USER_LEAGUE_MOVEMENT_UP = 'up';
    const USER_LEAGUE_MOVEMENT_DOWN  = 'down';
    const USER_LEAGUE_MOVEMENT_SAME = 'same';

    /**
     * @var LeagueManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return LeagueManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new LeagueManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function getLeagueDisplayName($leagueId) {
        $leagueLanguageDAO = LeagueLanguageDAO::getInstance($this->getServiceLocator());
        $language = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser()->getLanguage();
        $displayName = $leagueLanguageDAO->getLeagueDisplayName($leagueId, $language->getId());
        if (empty($displayName) && !$language->getIsDefault()) {
            $defaultLanguage = LanguageManager::getInstance($this->getServiceLocator())->getDefaultLanguage();
            $displayName = $leagueLanguageDAO->getLeagueDisplayName($leagueId, $defaultLanguage->getId());
        }
        return $displayName;
    }

    public function recalculateLeaguesTables(\Application\Model\Entities\Match $match, array $predictions) {

        $season = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();

        if ($season != null) {

            $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
            $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());

            $leagueUserDAO->beginLeagueUsersUpdate();

            foreach ($season->getLeagues() as $league)
                if ($league->getType() != League::MINI_TYPE || $league->getIsActive($match->getStartTime())) {
                    $usersData = $leagueDAO->getLeagueUsersScores($league);

                    $leagueUsers = array();
                    foreach ($usersData as $userRow) {
                        if (array_key_exists($userRow['user_id'], $predictions)) {
                            $prediction = $predictions[$userRow['user_id']];
                            $userRow['predictions_players_count'] += $prediction['predictions_players_count'];
                            $userRow['predictions_count']++;
                            $userRow['correct_results'] += $prediction['is_correct_result'];
                            $userRow['correct_scores'] += $prediction['is_correct_score'];
                            $userRow['correct_scorers'] += $prediction['correct_scorers'];
                            $userRow['correct_scorers_order'] += $prediction['correct_scorers_order'];
                            $userRow['accuracy'] = $userRow['correct_results'] / $userRow['predictions_count'] + $userRow['correct_scores'] / $userRow['predictions_count'];
                            $divider = 2;
                            if ($userRow['predictions_players_count'] > 0) {
                                $userRow['accuracy'] += $userRow['correct_scorers'] / $userRow['predictions_players_count'] + $userRow['correct_scorers_order'] / $userRow['predictions_players_count'];
                                $divider = 4;
                            }
                            $userRow['accuracy'] /= $divider;
                            if ($userRow['points'] === null)
                                $userRow['points'] = $prediction['points'];
                            else
                                $userRow['points'] += $prediction['points'];
                        }
                        $userRow['date'] = new \DateTime($userRow['registration_date']);
                        if ($userRow['points'] !== null)
                            $leagueUsers[] = $userRow;
                    }

                    usort($leagueUsers, function($u2, $u1) {
                        $res = $u1['points'] != $u2['points'] ? $u1['points'] - $u2['points'] :
                            (floor(100 * $u1['accuracy']) != floor(100 * $u2['accuracy']) ? $u1['accuracy'] - $u2['accuracy'] :
                            ($u1['predictions_count'] != $u2['predictions_count'] ? $u1['predictions_count'] - $u2['predictions_count'] :
                            ($u1['correct_results'] != $u2['correct_results'] ? $u1['correct_results'] - $u2['correct_results'] :
                            ($u1['correct_scores'] != $u2['correct_scores'] ? $u1['correct_scores'] - $u2['correct_scores'] :
                            ($u1['correct_scorers'] != $u2['correct_scorers'] ? $u1['correct_scorers'] - $u2['correct_scorers'] :
                            ($u1['date']->getTimestamp() != $u2['date']->getTimestamp() ? $u2['date']->getTimestamp() - $u1['date']->getTimestamp() :
                            ($u2['user_id'] - $u1['user_id'])))))));
                        return (int)($res/abs($res));
                    });

                    foreach ($leagueUsers as $i => $userRow) {
                        $leagueUserDAO->appendLeagueUsersUpdate($userRow, $i + 1);
                        $leagueUserDAO->appendLeagueUserPlace($userRow, $i + 1, $match->getId());
                    }

                }

            $leagueUserDAO->commitLeagueUsersUpdate();
            $leagueUserDAO->clearCache();

        } else
            throw new \Exception(MessagesConstants::INFO_OUT_OF_SEASON);

    }

    public function getAllLeagues($hydrate = false, $skipCache = false) {
        return LeagueDAO::getInstance($this->getServiceLocator())->getAllLeagues($hydrate, $skipCache);
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param int $seasonId
     * @return bool
     */
    public function checkDates($startDate, $endDate, $seasonId) {
        return LeagueDAO::getInstance($this->getServiceLocator())->checkLeagueDatesInterval($startDate, $endDate, $seasonId);
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return League
     */
    public function getLeagueById($id, $hydrate = false, $skipCache = false) {
        return LeagueDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    /**
     * @param League $league
     */
    public function deleteLeague($league) {
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $leagueDAO->remove($league);
    }

    public function saveMiniLeague($displayName, $seasonId, $startDate, $endDate, $regionsArr, $languagesData, $leagueId = -1, $editableLeague = true) {
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        if ($leagueId == -1) {
            $league = new League();
            $season = SeasonDAO::getInstance($this->getServiceLocator())->findOneById($seasonId);
            $league->setSeason($season);
            $league->setCreationDate(new \DateTime());
            $league->setCreator(ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser());
        } else
            $league = $leagueDAO->findOneById($leagueId);
        if ($startDate != null && $endDate != null) {
            $league->setStartDate($startDate);
            $league->setEndDate($endDate);
        }
        $league->setType(League::MINI_TYPE);
        $league->setDisplayName($displayName);

        if ($editableLeague) {
            foreach ($league->getLeagueRegions() as $leagueRegion)
                if (!in_array($leagueRegion->getRegion()->getId(), $regionsArr))
                    $league->removeLeagueRegion($leagueRegion);

            foreach ($regionsArr as $regionId) {
                $region = RegionManager::getInstance($this->getServiceLocator())->getNonHydratedRegionFromArray($regionId);
                if ($region) {
                    $leagueRegion = $league->getLeagueRegionByRegionId($regionId);
                    if ($leagueRegion === null) {
                        $leagueRegion = new LeagueRegion();
                        $leagueRegion->setLeague($league);
                        $leagueRegion->setRegion($region);
                        $league->addLeagueRegion($leagueRegion);
                    }
                }
            }
        }

        foreach ($league->getLeagueLanguages() as $leagueLanguage)
            if (!array_key_exists($leagueLanguage->getLanguage()->getId(), $languagesData))
                $league->removeLeagueLanguage($leagueLanguage);

        foreach ($languagesData as $languageId => $languageRow) {
            $language = LanguageManager::getInstance($this->getServiceLocator())->getNonHydratedLanguageFromArray($languageId);
            if (!$language) continue;
            $leagueLanguage = $league->getLeagueLanguageByLanguageId($languageId);
            $add = false;
            if ($leagueLanguage === null) {
                $leagueLanguage = new LeagueLanguage();
                $leagueLanguage->setLeague($league);
                $leagueLanguage->setLanguage($language);
                $add = true;
            }
            $leagueLanguage->setDisplayName($languageRow['leagueDisplayName']);
            if (!empty($languageRow['prizeImagePath']))
                $leagueLanguage->setPrizeImage($languageRow['prizeImagePath']);
            $leagueLanguage->setPrizeDescription($languageRow['prizeDescription']);
            $leagueLanguage->setPrizeTitle($languageRow['prizeTitle']);
            if (!empty($languageRow['postWinImagePath']))
                $leagueLanguage->setPostWinImage($languageRow['postWinImagePath']);
            $leagueLanguage->setPostWinDescription($languageRow['postWinDescription']);
            $leagueLanguage->setPostWinTitle($languageRow['postWinTitle']);
            if ($add)
                $league->addLeagueLanguage($leagueLanguage);
        }

        $leagueDAO->save($league);
        if ($editableLeague && $leagueId === -1) {
            $userManager = UserManager::getInstance($this->getServiceLocator());
            foreach ($league->getLeagueRegions() as $leagueRegion)
                $userManager->registerLeagueUsers($league, $leagueRegion->getRegion()->getId());
        }
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTemporalLeagues($region, $hydrate = false, $skipCache = false) {
        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        return $leagueDAO->getTemporalLeagues($region, $hydrate, $skipCache);
    }

    /**
     * @param int $leagueId
     * @param int $top
     * @param int $offset
     * @param array|null $facebookIds
     * @return array
     */
    public function getLeagueTop($leagueId, $top = 0, $offset = 0, $facebookIds = null) {
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        return $leagueUserDAO->getLeagueTop($leagueId, $top, $offset, $facebookIds);
    }

    /**
     * @param int $leagueId
     * @param bool $skipCache
     * @return int
     */
    public function getLeagueUsersCount($leagueId, $skipCache = false) {
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        return $leagueUserDAO->getLeagueUsersCount($leagueId, $skipCache);
    }

    /**
     * @param int $leagueId
     * @param int $userId
     * @return int
     */
    public function getYourPlaceInLeague($leagueId, $userId) {
        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());
        return $leagueUserDAO->getYourPlaceInLeague($leagueId, $userId);
    }

    /**
     * @param string $name
     * @param Season $season
     * @param User $creator
     * @throws \Exception
     */
    public function createPrivateLeague($name, $season, $creator) {

        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $privateLeagueDAO = PrivateLeagueDAO::getInstance($this->getServiceLocator());

        $hash = $this->generatePrivateLeagueKey($creator);

        $league = new League();
        $league->setDisplayName($name);
        $league->setSeason($season);
        $league->setCreator($creator);
        $league->setCreationDate(new \DateTime());
        $today = new \DateTime();
        $league->setStartDate($today->setTime(0, 0, 0));
        $league->setEndDate($season->getEndDate());
        $league->setType(League::PRIVATE_TYPE);

        $leagueUser = new LeagueUser();
        $leagueUser->setUser($creator);
        $leagueUser->setJoinDate(new \DateTime());
        $leagueUser->setRegistrationDate($creator->getDate());
        $leagueUser->setLeague($league);
        $league->addLeagueUser($leagueUser);

        $privateLeague = new PrivateLeague();
        $privateLeague->setLeague($league);
        $privateLeague->setUniqueHash($hash);
        $league->setPrivateLeague($privateLeague);

        $leagueDAO->save($league, true, false);
        $privateLeagueDAO->clearCache();

        return $hash;

    }

    private function generatePrivateLeagueKey($user) {
        return (string) hash('crc32', microtime() . $user->getId());
    }

    /**
     * @param string $hash
     * @param Season $season
     * @param User $user
     * @throws \Exception
     */
    public function joinPrivateLeague($hash, $season, $user) {

        $leagueDAO = LeagueDAO::getInstance($this->getServiceLocator());
        $privateLeagueDAO = PrivateLeagueDAO::getInstance($this->getServiceLocator());

        $privateLeague = $leagueDAO->getPrivateLeagueByHash($hash, $season->getId());
        if ($privateLeague === null)
            throw new \Exception(sprintf(MessagesConstants::ERROR_UNKNOWN_PRIVATE_LEAGUE, $hash));

        if ($leagueDAO->getIsUserInLeague($privateLeague, $user))
            throw new \Exception(MessagesConstants::ERROR_YOU_JOINED_LEAGUE_EARLIER);

        $leagueUser = new LeagueUser();
        $leagueUser->setUser($user);
        $leagueUser->setJoinDate(new \DateTime());
        $leagueUser->setRegistrationDate($user->getDate());
        $leagueUser->setLeague($privateLeague);
        $privateLeague->addLeagueUser($leagueUser);

        $leagueDAO->save($privateLeague, true, false);
        $privateLeagueDAO->clearCache();

    }

    /**
     * @param League $league
     * @param User $user
     * @throws \Exception
     */
    public function leavePrivateLeague($league, $user) {

        $leagueUserDAO = LeagueUserDAO::getInstance($this->getServiceLocator());

        $leagueUser = $leagueUserDAO->getLeagueUser($league->getId(), $user->getId());
        if ($leagueUser === null)
            throw new \Exception(MessagesConstants::ERROR_NOT_MEMBER_OF_LEAGUE);

        $fromPlace = $leagueUser->getPlace();

        $leagueUserDAO->remove($leagueUser);

        $leagueUserDAO->moveUpLeagueUserPlaces($league, $fromPlace);

    }

}