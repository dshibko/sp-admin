<?php

namespace Opta\Manager;

use Application\Controller\ClearAppCacheController;
use Application\Manager\LeagueManager;
use \Application\Manager\MatchManager;
use Application\Manager\PlayerManager;
use Application\Manager\RegionManager;
use \Application\Manager\SeasonManager;
use Application\Manager\UserManager;
use Application\Model\DAOs\CompetitionSeasonDAO;
use Application\Model\DAOs\LeagueUserDAO;
use Application\Model\DAOs\LeagueUserPlaceDAO;
use Application\Model\DAOs\MessageDAO;
use Application\Model\DAOs\PreMatchReportAvgGoalsScoredDAO;
use Application\Model\DAOs\PreMatchReportFormGuideDAO;
use Application\Model\DAOs\PreMatchReportGoalsScoredDAO;
use Application\Model\DAOs\PreMatchReportHeadToHeadDAO;
use Application\Model\DAOs\PreMatchReportLastSeasonMatchDAO;
use Application\Model\DAOs\PreMatchReportMostRecentScorerDAO;
use Application\Model\DAOs\PreMatchReportTopScorerDAO;
use Application\Model\DAOs\PredictionDAO;
use Application\Model\Entities\CompetitionSeason;
use Application\Model\Entities\PreMatchReportAvgGoalsScored;
use Application\Model\Entities\PreMatchReportFormGuide;
use Application\Model\Entities\PreMatchReportGoalsScored;
use Application\Model\Entities\PreMatchReportHeadToHead;
use Application\Model\Entities\PreMatchReportLastSeasonMatch;
use Application\Model\Entities\PreMatchReportMostRecentScorer;
use Application\Model\Entities\PreMatchReportTopScorer;
use Application\Model\Entities\Season;
use Neoco\Exception\OutOfSeasonException;
use \Application\Model\DAOs\FeedDAO;
use \Application\Model\Entities\Feed;
use \Application\Model\DAOs\MatchGoalDAO;
use \Zend\Mvc\Controller\Plugin\FlashMessenger;
use \Zend\Log\Logger;
use \Application\Model\Entities\LineUpPlayer;
use \Application\Model\DAOs\LineUpPlayerDAO;
use \Application\Manager\ApplicationManager;
use \Application\Model\Entities\MatchGoal;
use \Application\Manager\LogManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Application\Model\Entities\Match;
use \Application\Model\DAOs\MatchDAO;
use \Application\Model\DAOs\PlayerDAO;
use \Application\Model\Entities\Player;
use \Application\Model\Entities\Team;
use \Application\Model\DAOs\TeamDAO;
use \Application\Model\DAOs\SeasonDAO;
use \Application\Model\Entities\Competition;
use \Application\Model\DAOs\CompetitionDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class OptaManager extends BasicManager {

    const PARSE_F7_FEEDS_DAYS_BEFORE_START = 7;

    /**
     * @var OptaManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return OptaManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new OptaManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param string $filePath
     * @param Season $season
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @return bool|\Exception
     */
    public function parseF40Feed($filePath, $season, $console = null) {

        try {
            $this->startProgress($console, 'F40', $filePath);

            $xml = $this->getXMLContent($filePath);

            if ($season != null) {

                $competitionFeederId = $this->getXmlAttribute($xml->SoccerDocument, 'competition_id');
                if (empty($competitionFeederId))
                    throw new \Exception(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'competition_id'));
                $competitionDAO = CompetitionDAO::getInstance($this->getServiceLocator());
                $competition = $competitionDAO->findOneByFeederId($competitionFeederId, false, true);
                if ($competition == null) {
                    $competition = new Competition();
                    $competition->setFeederId($competitionFeederId);
                    $competition->addSeason($season);
                }
                $competition->setDisplayName($this->getXmlAttribute($xml->SoccerDocument, 'competition_name'));
                $competitionDAO->save($competition);

                $competitionSeasonDAO = CompetitionSeasonDAO::getInstance($this->getServiceLocator());
                $competitionSeason = $competitionSeasonDAO->getCompetitionSeason($competition->getId(), $season->getId());
                if ($competitionSeason == null) {
                    $competitionSeason = new CompetitionSeason();
                    $competitionSeason->setCompetition($competition);
                    $competitionSeason->setSeason($season);
                    $competitionSeasonDAO->save($competitionSeason);
                }

                $teamDAO = TeamDAO::getInstance($this->getServiceLocator());
                $playerDAO = PlayerDAO::getInstance($this->getServiceLocator());

                $this->setProgressLength($xml->SoccerDocument->Team->count());

                foreach ($xml->SoccerDocument->Team as $teamXml) {
                    try {
                        $teamFeederId = $this->getIdFromString($this->getXmlAttribute($teamXml, 'uID'));
                        if (empty($teamFeederId)) {
                            $this->doProgress($console);
                            $this->logMessage(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'team_id'), Logger::WARN, $console);
                            continue;
                        }
                        $team = $teamDAO->findOneByFeederId($teamFeederId, false, true);
                        if ($team == null) {
                            $team = new Team();
                            $team->setFeederId($teamFeederId);
                        }
                        if (!$team->hasCompetitionSeason($competitionSeason))
                            $team->addCompetitionSeason($competitionSeason);
                        if (!$team->getIsBlocked()) {
                            $team->setDisplayName($teamXml->Name->__toString());
                            $team->setShortName($this->getNodeValue($teamXml->SYMID));
                        }
                        $team->setFounded($this->getNodeValue($teamXml->Founded));
                        $team->setStadiumName($this->getNodeValue($teamXml->Stadium, 'Name'));
                        $team->setStadiumCapacity($this->getNodeValue($teamXml->Stadium, 'Capacity'));

                        if ($teamXml->TeamOfficial != null)
                            foreach ($teamXml->TeamOfficial as $teamOfficialXml)
                                if ($this->getXmlAttribute($teamOfficialXml, 'Type') == 'Manager') {
                                    $team->setManager($teamOfficialXml->PersonName->First . ' ' . $teamOfficialXml->PersonName->Last);
                                    break;
                                }

                        foreach ($team->getPlayers() as $player)
                            if ($player->hasCompetitionSeason($competitionSeason))
                                $player->removeCompetitionSeason($competitionSeason);

                        $playerFeederIds = array();

                        foreach ($teamXml->Player as $playerXml) {
                            try {
                                $playerFeederId = $this->getIdFromString($this->getXmlAttribute($playerXml, 'uID'));
                                if (in_array($playerFeederId, $playerFeederIds))
                                    continue;
                                else
                                    $playerFeederIds[] = $playerFeederId;
                                if (empty($playerFeederId)) {
                                    $this->logMessage(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'player_id'), Logger::WARN, $console);
                                    continue;
                                }
                                $player = $playerDAO->findOneByFeederId($playerFeederId, false, true);
                                if ($player == null) {
                                    $player = new Player();
                                    $player->setFeederId($playerFeederId);
                                    $team->addPlayer($player);
                                } else if ($player->getTeam()->getId() != $team->getId()) {
                                    $player->clearCompetitionSeasons();
                                    $team->addPlayer($player);
                                }
                                if (!$player->hasCompetitionSeason($competitionSeason))
                                    $player->addCompetitionSeason($competitionSeason);

                                $player->setTeam($team);
                                if (!$player->getIsBlocked()) {
                                    $player->setDisplayName($playerXml->Name->__toString());
                                    $player->setShirtNumber($this->getNodeValueByAttribute($playerXml, 'Stat', 'Type', 'jersey_num'));
                                }
                                $player->setPosition($playerXml->Position->__toString());
                                $player->setName($playerXml->Stat->{0}->__toString());
                                $player->setSurname($playerXml->Stat->{1}->__toString());
                                $player->setBirthDate($this->getNodeValueByAttribute($playerXml, 'Stat', 'Type', 'birth_date', 'Y-m-d'));
                                $player->setWeight($this->getNodeValueByAttribute($playerXml, 'Stat', 'Type', 'weight'));
                                $player->setHeight($this->getNodeValueByAttribute($playerXml, 'Stat', 'Type', 'height'));
                                $player->setRealPosition($this->getNodeValueByAttribute($playerXml, 'Stat', 'Type', 'real_position'));
                                $player->setRealPositionSide($this->getNodeValueByAttribute($playerXml, 'Stat', 'Type', 'real_position_side'));
                                $player->setJoinDate($this->getNodeValueByAttribute($playerXml, 'Stat', 'Type', 'join_date', 'Y-m-d'));
                                $player->setCountry($this->getNodeValueByAttribute($playerXml, 'Stat', 'Type', 'country'));
                            } catch (\Exception $e) {
                                ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::ERR, $console);
                            }
                        }

                        $teamDAO->save($team);

                    } catch (\Exception $e) {
                        ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::ERR, $console);
                    }

                    $this->doProgress($console);

                }

                $playerDAO->clearCache();

            } else
                $this->logMessage(MessagesConstants::INFO_SEASON_NOT_FOUND, Logger::NOTICE, $console);

            $this->finishProgress($console, 'F40', $filePath);

            return true;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::CRIT, $console);
            return false;
        }

    }

    /**
     * @param string $filePath
     * @param Season $season
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @return bool|\Exception
     */
    public function parseF1Feed($filePath, $season, $console = null) {

        try {
            $this->startProgress($console, 'F1', $filePath);

            $xml = $this->getXMLContent($filePath);

            $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
            $edition = $applicationManager->getAppEdition();
            $optaId = $applicationManager->getAppOptaId();

            $matchDAO = MatchDAO::getInstance($this->getServiceLocator());

            if ($season != null) {

                $competitionFeederId = $this->getXmlAttribute($xml->SoccerDocument, 'competition_id');
                if (empty($competitionFeederId))
                    throw new \Exception(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'competition_id'));

                if ($edition != ApplicationManager::COMPETITION_EDITION || $optaId == $competitionFeederId) {
                    $competitionDAO = CompetitionDAO::getInstance($this->getServiceLocator());
                    $competition = $competitionDAO->findOneByFeederId($competitionFeederId, false, true);
                    if ($competition == null) {
                        $competition = new Competition();
                        $competition->setFeederId($competitionFeederId);
                        $competition->addSeason($season);
                    }
                    $competition->setDisplayName($this->getXmlAttribute($xml->SoccerDocument, 'competition_name'));
                    $competitionDAO->save($competition);

                    $competitionSeasonDAO = CompetitionSeasonDAO::getInstance($this->getServiceLocator());
                    $competitionSeason = $competitionSeasonDAO->getCompetitionSeason($competition->getId(), $season->getId());
                    if ($competitionSeason == null) {
                        $competitionSeason = new CompetitionSeason();
                        $competitionSeason->setCompetition($competition);
                        $competitionSeason->setSeason($season);
                        $competitionSeasonDAO->save($competitionSeason);
                    }

                    $teamDAO = TeamDAO::getInstance($this->getServiceLocator());

                    $this->setProgressLength($xml->SoccerDocument->MatchData->count());

                    foreach ($xml->SoccerDocument->MatchData as $matchXml) {
                        try {

                            $team1FeederId = $this->getIdFromString($this->getXmlAttribute($matchXml->TeamData->{0}, 'TeamRef'));
                            $team2FeederId = $this->getIdFromString($this->getXmlAttribute($matchXml->TeamData->{1}, 'TeamRef'));

                            if (empty($team1FeederId) || empty($team2FeederId)) {
                                $this->doProgress($console);
                                $this->logMessage(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'team_id'), Logger::WARN, $console);
                                continue;
                            }

                            if ($edition == ApplicationManager::CLUB_EDITION)
                                if ($team1FeederId != $optaId && $team2FeederId != $optaId) {
                                    $this->doProgress($console);
                                    continue;
                                }

                            $matchFeederId = $this->getIdFromString($this->getXmlAttribute($matchXml, 'uID'));
                            if (empty($matchFeederId)) {
                                $this->doProgress($console);
                                $this->logMessage(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'match_id'), Logger::WARN, $console);
                                continue;
                            }
                            $match = $matchDAO->findOneByFeederId($matchFeederId, false, true);
                            if ($match == null) {
                                $match = new Match();
                                $match->setFeederId($matchFeederId);
                            }

                            if (!$match->getIsBlocked()) {
                                $timezoneAbbr = $this->getNodeValue($matchXml->MatchInfo, 'TZ');
                                $match->setTimezone($timezoneAbbr);
                                $timezone = !empty($timezoneAbbr) ? new \DateTimeZone(timezone_name_from_abbr($timezoneAbbr)) : null;
                                $startTime = $this->getNodeValue($matchXml->MatchInfo, 'Date', 'Y-m-d G:i:s', $timezone);
                                if ($startTime->getOffset() != 0) {
                                    $offsetInterval = new \DateInterval('PT' . abs($startTime->getOffset()) . 'S');
                                    if ($startTime->getOffset() > 0)
                                        $startTime->sub($offsetInterval);
                                    else
                                        $startTime->add($offsetInterval);
                                }
                                $match->setStartTime($startTime);
                                if (!$season->getIsActive($match->getStartTime())) {
                                    $this->doProgress($console);
                                    continue;
                                }

                                $match->setCompetitionSeason($competitionSeason);
                                $match->setWeek($this->getXmlAttribute($matchXml->MatchInfo, 'MatchDay'));
                                $status = $this->getXmlAttribute($matchXml->MatchInfo, 'Period');
                                if (!in_array($status, Match::getAvailableStatuses()))
                                    $status = Match::LIVE_STATUS;

                                $match->setStatus($status);
                                $team2Side = $this->getXmlAttribute($matchXml->TeamData->{1}, 'Side');

                                if ($team2Side == 'Home') {
                                    $homeTeamFeederId = $team2FeederId;
                                    $awayTeamFeederId = $team1FeederId;
                                } else {
                                    $homeTeamFeederId = $team1FeederId;
                                    $awayTeamFeederId = $team2FeederId;
                                }

                                $homeTeam = $teamDAO->findOneByFeederId($homeTeamFeederId, false, true);
                                if ($homeTeam === null) {
                                    $homeTeamName = $xml->xpath('SoccerDocument/Team[@uID=\'t' . $homeTeamFeederId . '\']/Name');
                                    if (!empty($homeTeamName)) {
                                        $homeTeamName = $homeTeamName[0]->__toString();
                                        $homeTeam = new Team();
                                        $homeTeam->setDisplayName($homeTeamName);
                                        $homeTeam->setFeederId($homeTeamFeederId);
                                        $teamDAO->save($homeTeam);
                                    }
                                }
                                $awayTeam = $teamDAO->findOneByFeederId($awayTeamFeederId, false, true);
                                if ($awayTeam === null) {
                                    $awayTeamName = $xml->xpath('SoccerDocument/Team[@uID=\'t' . $awayTeamFeederId . '\']/Name');
                                    if (!empty($awayTeamName)) {
                                        $awayTeamName = $awayTeamName[0]->__toString();
                                        $awayTeam = new Team();
                                        $awayTeam->setDisplayName($awayTeamName);
                                        $awayTeam->setFeederId($awayTeamFeederId);
                                        $teamDAO->save($awayTeam);
                                    }
                                }

                                $match->setHomeTeam($homeTeam);
                                $match->setAwayTeam($awayTeam);

                                if ($match->getHomeTeam() == null || $match->getAwayTeam() == null) {
                                    $this->doProgress($console);
                                    $missedFeederId = $match->getHomeTeam() == null ? $homeTeamFeederId : $awayTeamFeederId;
                                    $this->logMessage(sprintf(MessagesConstants::WARNING_TEAM_NOT_FOUND, $missedFeederId), Logger::WARN, $console);
                                    continue;
                                }

                                $match->setStadiumName($this->getNodeValue($matchXml->Stat, 0));
                                $match->setCityName($this->getNodeValue($matchXml->Stat, 1));

                                $matchDAO->save($match);

                            }

                        } catch (\Exception $e) {
                            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::ERR, $console);
                        }

                        $this->doProgress($console);

                    }
                } else
                    $this->logMessage(MessagesConstants::INFO_WRONG_COMPETITION, Logger::NOTICE, $console);
            } else
                $this->logMessage(MessagesConstants::INFO_SEASON_NOT_FOUND, Logger::NOTICE, $console);

            $this->finishProgress($console, 'F1', $filePath);

            return true;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::CRIT, $console);
            return false;
        }
    }

    /**
     * @param $filePath
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @return bool|\Exception
     */
    public function parseF7Feed($filePath, $console = null) {

        try {
            $this->startProgress($console, 'F7', $filePath);

            $xml = $this->getXMLContent($filePath);

            $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
            $matchFeederId = $this->getIdFromString($this->getXmlAttribute($xml->SoccerDocument, 'uID'));
            $period = $this->getXmlAttribute($xml->SoccerDocument->MatchData->MatchInfo, 'Period');
            $match = $matchDAO->findOneByFeederId($matchFeederId, false, true);

            $cacheClearArr = array();

            if ($match->getStatus() != Match::FULL_TIME_STATUS) {
                $teamData = $xml->SoccerDocument->MatchData->TeamData;
                if ($teamData != null) {
                    $playerDAO = PlayerDAO::getInstance($this->getServiceLocator());
                    $teamDAO = TeamDAO::getInstance($this->getServiceLocator());
                    $homeTeamData = $teamData->{0};
                    $awayTeamData = $teamData->{1};
                    if (empty($homeTeamData) || empty($awayTeamData))
                        throw new \Exception(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'team_id'));
                    $teamsData = array($homeTeamData, $awayTeamData);
                    $updated = false;
                    foreach ($teamsData as $teamData) {
                        $teamFeederId = $this->getIdFromString($this->getXmlAttribute($teamData, 'TeamRef'));
                        $teamObj = $teamDAO->findOneByFeederId($teamFeederId, false, true);
                        if ($teamObj != null && $teamData->PlayerLineUp != null && $teamObj->getPlayers()->count() == 0) {
                            $updated = true;
                            $squad = $teamData->PlayerLineUp->MatchPlayer;
                            if ($squad != null)
                                foreach ($squad as $player) {
                                    $playerFeederId = $this->getIdFromString($this->getXmlAttribute($player, 'PlayerRef'));
                                    $playerObj = $playerDAO->findOneByFeederId($playerFeederId, false, true);
                                    if ($playerObj === null) {
                                        $playerObj = new Player();
                                        $playerObj->setFeederId($playerFeederId);
                                    }
                                    $playerObj->setTeam($teamObj);
                                    $playerDomObj = $xml->xpath('SoccerDocument/Team[@uID=\'t' . $teamFeederId . '\']/Player[@uID=\'p' . $playerFeederId . '\']');
                                    if (empty($playerDomObj)) continue;
                                    $firstName = $playerDomObj[0]->PersonName->First;
                                    $lastName = $playerDomObj[0]->PersonName->Last;
                                    $playerObj->setName($firstName);
                                    $playerObj->setSurname($lastName);
                                    $playerObj->setDisplayName($firstName . ' ' . $lastName);
                                    $playerObj->setPosition($this->getXmlAttribute($player, 'Position'));
                                    $playerObj->setShirtNumber($this->getXmlAttribute($player, 'ShirtNumber'));
                                    $playerObj->addCompetitionSeason($match->getCompetitionSeason());
                                    $playerDAO->save($playerObj, false, false);
                                }
                        }
                    }
                    if ($updated) {
                        $playerDAO->flush();
                        $playerDAO->clearCache();
                    }
                }
            }

            if ($period == 'FullTime' && $match != null &&
                $match->getStatus() != Match::FULL_TIME_STATUS) {

                $teamData1 = $xml->SoccerDocument->MatchData->TeamData->{0};
                $teamData2 = $xml->SoccerDocument->MatchData->TeamData->{1};

                if (empty($teamData1) || empty($teamData2))
                    throw new \Exception(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'team_id'));

                if ($this->getXmlAttribute($teamData2, 'Side') == 'Home') {
                    $homeTeamData = $teamData2;
                    $awayTeamData = $teamData1;
                } else {
                    $homeTeamData = $teamData1;
                    $awayTeamData = $teamData2;
                }

                    $homeScore = $this->getXmlAttribute($homeTeamData, 'Score');
                    $awayScore = $this->getXmlAttribute($awayTeamData, 'Score');
                    $homeExtraScore = $awayExtraScore = null;
                    $homeShootoutScore = $awayShootoutScore = null;

                    foreach ($homeTeamData->Goal as $goalData) {
                        $period = $this->getXmlAttribute($goalData, 'Period');
                        if ($period == MatchGoal::EXTRA_FIRST_HALF_PERIOD ||
                            $period == MatchGoal::EXTRA_SECOND_HALF_PERIOD) {
                            $homeScore--;
                            $homeExtraScore = $homeExtraScore == null ? 1 : $homeExtraScore + 1;
                        }
                        if ($period == MatchGoal::SHOOTOUT_PERIOD) {
                            $homeExtraScore = $homeExtraScore == null ? 0 : $homeExtraScore;
                            $homeShootoutScore =  $homeShootoutScore == null ? 1 : $homeShootoutScore + 1;
                        }
                    }

                    foreach ($awayTeamData->Goal as $goalData) {
                        $period = $this->getXmlAttribute($goalData, 'Period');
                        if ($period == MatchGoal::EXTRA_FIRST_HALF_PERIOD ||
                            $period == MatchGoal::EXTRA_SECOND_HALF_PERIOD) {
                            $awayScore--;
                            $awayExtraScore = $awayExtraScore == null ? 1 : $awayExtraScore + 1;
                        }
                        if ($period == MatchGoal::SHOOTOUT_PERIOD) {
                            $awayExtraScore = $awayExtraScore == null ? 0 : $awayExtraScore;
                            $awayShootoutScore =  $awayShootoutScore == null ? 1 : $awayShootoutScore + 1;
                        }
                    }

                    $match->setHomeTeamFullTimeScore($homeScore);
                    $match->setAwayTeamFullTimeScore($awayScore);
                    $match->setHomeTeamExtraTimeScore($homeExtraScore);
                    $match->setAwayTeamExtraTimeScore($awayExtraScore);
                    $match->setHomeTeamShootoutScore($homeShootoutScore);
                    $match->setAwayTeamShootoutScore($awayShootoutScore);

                $playerDAO = PlayerDAO::getInstance($this->getServiceLocator());
                $teamDAO = TeamDAO::getInstance($this->getServiceLocator());

                $homeTeamFeederId = $this->getIdFromString($this->getXmlAttribute($homeTeamData, 'TeamRef'));
                $homeTeam = $teamDAO->findOneByFeederId($homeTeamFeederId, false, true);
                $awayTeamFeederId = $this->getIdFromString($this->getXmlAttribute($awayTeamData, 'TeamRef'));
                $awayTeam = $teamDAO->findOneByFeederId($awayTeamFeederId, false, true);

                $players = array();

                $teamsData = array($homeTeamData, $awayTeamData);

                foreach ($teamsData as $i => $teamData) {
                    $order = 0;
                    foreach ($teamData->Goal as $goalData) {
                        $matchGoal = new MatchGoal();
                        $matchGoal->setTeam($i == 0 ? $homeTeam : $awayTeam);
                        $matchGoal->setMatch($match);
                        $matchGoal->setPeriod($this->getXmlAttribute($goalData, 'Period'));
                        $matchGoal->setType($this->getXmlAttribute($goalData, 'Type'));
                        $matchGoal->setMinute($this->getXmlAttribute($goalData, 'Time'));
                        $playerFeederId = $this->getIdFromString($this->getXmlAttribute($goalData, 'PlayerRef'));
                        if (!array_key_exists($playerFeederId, $players))
                            $players[$playerFeederId] = $playerDAO->findOneByFeederId($playerFeederId, false, true);
                        $matchGoal->setPlayer($players[$playerFeederId]);
                        $timestamp = $this->getXmlAttribute($goalData, 'TimeStamp');
                        $matchGoal->setTime(new \DateTime($timestamp));
                        $matchGoal->setOrder(++$order);
                        $match->addMatchGoal($matchGoal);
                    }
                }

                try {
                    ScoringManager::getInstance($this->getServiceLocator())->calculateMatchScores($match);
                } catch (\Exception $e) {
                    ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::EMERG, $console);
                }

                $cacheClearArr[] = $matchDAO->getRepositoryName();
                $cacheClearArr[] = MatchGoalDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                $cacheClearArr[] = PredictionDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                $cacheClearArr[] = LeagueUserDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                $cacheClearArr[] = LeagueUserPlaceDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                $cacheClearArr[] = MessageDAO::getInstance($this->getServiceLocator())->getRepositoryName();

                $match->setIsBlocked(true);
                $match->setStatus(Match::FULL_TIME_STATUS);
                $matchDAO->save($match);

                MatchGoalDAO::getInstance($this->getServiceLocator())->clearCache();

            }

            if ($period == 'PreMatch' && $match != null && !$match->getHasLineUp() &&
                $match->getStatus() == Match::PRE_MATCH_STATUS) {
                $teamData = $xml->SoccerDocument->MatchData->TeamData;
                if ($teamData != null) {
                    $playerDAO = PlayerDAO::getInstance($this->getServiceLocator());
                    $teamDAO = TeamDAO::getInstance($this->getServiceLocator());
                    $lineUpPlayerDAO = LineUpPlayerDAO::getInstance($this->getServiceLocator());
                    $homeTeamData = $teamData->{0};
                    $awayTeamData = $teamData->{1};
                    if (empty($homeTeamData) || empty($awayTeamData))
                        throw new \Exception(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'team_id'));
                    $teamsData = array($homeTeamData, $awayTeamData);
                    $hasLineUp = false;
                    foreach ($teamsData as $teamData) {
                        $teamFeederId = $this->getIdFromString($this->getXmlAttribute($teamData, 'TeamRef'));
                        $teamObj = $teamDAO->findOneByFeederId($teamFeederId, false, true);
                        if ($teamObj != null && $teamData->PlayerLineUp != null) {
                            $hasLineUp = true;
                            $squad = $teamData->PlayerLineUp->MatchPlayer;
                            if ($squad != null)
                                foreach ($squad as $player) {
                                    $lineUpPlayer = new LineUpPlayer();
                                    $playerFeederId = $this->getIdFromString($this->getXmlAttribute($player, 'PlayerRef'));
                                    $playerObj = $playerDAO->findOneByFeederId($playerFeederId, false, true);
                                    if ($playerObj === null) continue;
                                    $lineUpPlayer->setPlayer($playerObj);
                                    $lineUpPlayer->setMatch($match);
                                    $lineUpPlayer->setTeam($teamObj);
                                    $status = $this->getXmlAttribute($player, 'Status');
                                    $lineUpPlayer->setIsStart(strtolower($status) == 'start');
                                    $lineUpPlayerDAO->save($lineUpPlayer, false, false);
                                }
                        }
                    }
                    if ($hasLineUp) {
                        $lineUpPlayerDAO->flush();
                        $lineUpPlayerDAO->clearCache();
                        $match->setHasLineUp(true);
                        $matchDAO->save($match);
                    }
                }
                $cacheClearArr[] = $matchDAO->getRepositoryName();
                $cacheClearArr[] = MatchGoalDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                $cacheClearArr[] = LineUpPlayerDAO::getInstance($this->getServiceLocator())->getRepositoryName();
            }

            if (($period == 'FirstHalf' || $period == 'HalfTime' || $period == 'SecondHalf') && $match != null && $match->getStatus() == Match::PRE_MATCH_STATUS) {
                $match->setStatus(Match::LIVE_STATUS);
                $matchDAO->save($match);
                $cacheClearArr[] = $matchDAO->getRepositoryName();
            }

            $this->finishProgress($console, 'F7', $filePath);

            return $cacheClearArr;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::CRIT, $console);
            return false;
        }
    }

    /**
     * @param $filePath
     * @param \Zend\Console\Adapter\AdapterInterface|null $console
     */
    public function parseF2Feed($filePath, $console = null) {

        try {
            $this->startProgress($console, 'F2', $filePath);

            $xml = $this->getXMLContent($filePath);

            $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
            $playerDAO = PlayerDAO::getInstance($this->getServiceLocator());

            $matchData = $xml->Match;

            if ($matchData != null) {

                $matchFeederId = $this->getIdFromString($this->getXmlAttribute($matchData, 'Id'));
                $match = $matchDAO->findOneByFeederId($matchFeederId, false, true);
                if ($match == null)
                    throw new \Exception(sprintf(MessagesConstants::WARNING_MATCH_NOT_FOUND, $matchFeederId));

                $previousMeetingsNode = !empty($matchData->PreviousMeetings) ? $matchData->PreviousMeetings :
                    (!empty($matchData->PreviousMeetingsOtherComps) ? $matchData->PreviousMeetingsOtherComps : null);
                $previousMeetings = !empty($previousMeetingsNode) ? $previousMeetingsNode->MatchData : null;
                if (!empty($previousMeetings)) {

                    // head to head
                    $homeTeamWins = $draws = $awayTeamWins = 0;
                    foreach ($previousMeetings as $previousMeeting) {
                        $homeTeamSideData = $this->getNodeByAttribute($previousMeeting, 'TeamData', 'TeamRef', 't' . $match->getHomeTeam()->getFeederId());
                        $awayTeamSideData = $this->getNodeByAttribute($previousMeeting, 'TeamData', 'TeamRef', 't' . $match->getAwayTeam()->getFeederId());
                        if ($awayTeamSideData !== null && $awayTeamSideData !== null) {
                            $homeScore = $this->getXmlAttribute($homeTeamSideData, 'Score');
                            $awayScore = $this->getXmlAttribute($awayTeamSideData, 'Score');
                            if ($homeScore == $awayScore)
                                $draws++;
                            else if ($homeScore > $awayScore)
                                $homeTeamWins++;
                            else if ($homeScore < $awayScore)
                                $awayTeamWins++;
                        }
                    }
                    $headToHead = $match->getPreMatchReportHeadToHead();
                    if ($headToHead == null) {
                        $headToHead = new PreMatchReportHeadToHead();
                        $headToHead->setMatch($match);
                    }
                    $headToHead->setHomeTeamWins($homeTeamWins);
                    $headToHead->setDraws($draws);
                    $headToHead->setAwayTeamWins($awayTeamWins);
                    $match->setPreMatchReportHeadToHead($headToHead);

                    // last season match
                    foreach ($previousMeetings as $previousMeeting) {
                        $homeTeamSideData = $this->getNodeByAttribute($previousMeeting, 'TeamData', 'TeamRef', 't' . $match->getHomeTeam()->getFeederId());
                        $awayTeamSideData = $this->getNodeByAttribute($previousMeeting, 'TeamData', 'TeamRef', 't' . $match->getAwayTeam()->getFeederId());
                        if ($homeTeamSideData !== null && $this->getXmlAttribute($homeTeamSideData, 'Side') == 'Home' &&
                            $awayTeamSideData !== null && $this->getXmlAttribute($awayTeamSideData, 'Side') == 'Away') {
                            $homeTeamScoreInLastSeasonMatch = $this->getXmlAttribute($homeTeamSideData, 'Score');
                            $awayTeamScoreInLastSeasonMatch = $this->getXmlAttribute($awayTeamSideData, 'Score');
                            $lastSeasonMatch = $match->getPreMatchReportLastSeasonMatch();
                            if ($lastSeasonMatch == null) {
                                $lastSeasonMatch = new PreMatchReportLastSeasonMatch();
                                $lastSeasonMatch->setMatch($match);
                            }
                            $lastSeasonMatch->setHomeTeamScore($homeTeamScoreInLastSeasonMatch);
                            $lastSeasonMatch->setAwayTeamScore($awayTeamScoreInLastSeasonMatch);
                            $match->setPreMatchReportLastSeasonMatch($lastSeasonMatch);
                            break;
                        }
                    }

                }

                // goals scored
                $homeTeamTotalsData = !empty($matchData->Totals) ? $this->getNodeByAttribute($matchData->Totals, 'Team', 'uID', 't' . $match->getHomeTeam()->getFeederId()) : null;
                $awayTeamTotalsData = !empty($matchData->Totals) ? $this->getNodeByAttribute($matchData->Totals, 'Team', 'uID', 't' . $match->getAwayTeam()->getFeederId()) : null;
                if ($homeTeamTotalsData != null && $awayTeamTotalsData != null) {
                    $homeTeamGoals = $this->getNodeValueByAttribute($homeTeamTotalsData, 'Stat', 'Type', 'goals');
                    $awayTeamGoals = $this->getNodeValueByAttribute($awayTeamTotalsData, 'Stat', 'Type', 'goals');
                    if ($homeTeamGoals !== null && $awayTeamGoals !== null) {
                        $goalsScored = $match->getPreMatchReportGoalsScored();
                        if ($goalsScored == null) {
                            $goalsScored = new PreMatchReportGoalsScored();
                            $goalsScored->setMatch($match);
                        }
                        $goalsScored->setHomeTeamGoals($homeTeamGoals);
                        $goalsScored->setAwayTeamGoals($awayTeamGoals);
                        $match->setPreMatchReportGoalsScored($goalsScored);
                    }
                }

                // avg goals scored
                $homeTeamAveragesData = !empty($matchData->Averages) ? $this->getNodeByAttribute($matchData->Averages, 'Team', 'uID', 't' . $match->getHomeTeam()->getFeederId()) : null;
                $awayTeamAveragesData = !empty($matchData->Averages) ? $this->getNodeByAttribute($matchData->Averages, 'Team', 'uID', 't' . $match->getAwayTeam()->getFeederId()) : null;
                if ($homeTeamAveragesData != null && $awayTeamAveragesData != null) {
                    $homeTeamAvgGoals = $this->getNodeValueByAttribute($homeTeamAveragesData->Overall, 'Stat', 'Type', 'goals');
                    $awayTeamAvgGoals = $this->getNodeValueByAttribute($awayTeamAveragesData->Overall, 'Stat', 'Type', 'goals');
                    if ($homeTeamAvgGoals !== null && $awayTeamAvgGoals !== null) {
                        $avgGoalsScored = $match->getPreMatchReportAvgGoalsScored();
                        if ($avgGoalsScored == null) {
                            $avgGoalsScored = new PreMatchReportAvgGoalsScored();
                            $avgGoalsScored->setMatch($match);
                        }
                        $avgGoalsScored->setHomeTeamAvgGoals(floatval($homeTeamAvgGoals));
                        $avgGoalsScored->setAwayTeamAvgGoals(floatval($awayTeamAvgGoals));
                        $match->setPreMatchReportAvgGoalsScored($avgGoalsScored);
                    }
                }

                $homeTeamFormData = !empty($matchData->Form) ? $this->getNodeByAttribute($matchData->Form, 'Team', 'uID', 't' . $match->getHomeTeam()->getFeederId()) : null;
                $awayTeamFormData = !empty($matchData->Form) ? $this->getNodeByAttribute($matchData->Form, 'Team', 'uID', 't' . $match->getAwayTeam()->getFeederId()) : null;
                if ($homeTeamFormData != null && $awayTeamFormData != null) {

                    // form guide
                    $homeTeamFormText = $this->getNodeValue($homeTeamFormData->FormText);
                    $awayTeamFormText = $this->getNodeValue($awayTeamFormData->FormText);
                    if (!empty($homeTeamFormText) && !empty($awayTeamFormText)) {
                        $formMatchesMaxNumber = 5;
                        $formMatchesMinNumber = 3;
                        $saveFormData = true;
                        $formTexts = array($homeTeamFormText, $awayTeamFormText);
                        foreach ($formTexts as &$formText)
                            if (strlen($formText) > $formMatchesMaxNumber)
                                $formText = substr($formText, strlen($formText) - $formMatchesMaxNumber, $formMatchesMaxNumber);
                            else if (strlen($formText) < $formMatchesMinNumber) {
                                $saveFormData = false;
                                break;
                            }
                        if ($saveFormData) {
                            $formGuide = $match->getPreMatchReportFormGuide();
                            if ($formGuide == null) {
                                $formGuide = new PreMatchReportFormGuide();
                                $formGuide->setMatch($match);
                            }
                            $formGuide->setHomeTeamForm($formTexts[0]);
                            $formGuide->setAwayTeamForm($formTexts[1]);
                            $match->setPreMatchReportFormGuide($formGuide);
                        }
                    }

                    // last players to score
                    $teamsFormData = array(
                        $match->getHomeTeam()->getFeederId() => $homeTeamFormData,
                        $match->getAwayTeam()->getFeederId() => $awayTeamFormData,
                    );
                    $teamsLastScorersMaxNumber = 3;
                    $teamsLastScorersArr = array();
                    foreach($teamsFormData as $feederId => $teamFormData) {
                        $teamFormMatches = $teamFormData->MatchData;
                        $teamFormMatchesCount = count($teamFormMatches);
                        for ($i = $teamFormMatchesCount - 1; $i >= 0; $i--) {
                            $teamFormMatch = $teamFormMatches->{$i};
                            $teamMatchData = $this->getNodeByAttribute($teamFormMatch, 'TeamData', 'TeamRef', 't' . $feederId);
                            if ($teamMatchData !== null) {
                                $teamScorers = $teamMatchData->Goal;
                                $teamScorersCount = count($teamScorers);
                                for ($j = $teamScorersCount - 1; $j >= 0; $j--) {
                                    $teamScorer = $teamScorers->{$j};
                                    if ($this->getXmlAttribute($teamScorer, 'Type') != MatchGoal::OWN_TYPE) {
                                        if (!array_key_exists($feederId, $teamsLastScorersArr))
                                            $teamsLastScorersArr[$feederId] = array();
                                        $scorerFeederId = $this->getIdFromString($this->getXmlAttribute($teamScorer, 'PlayerRef'));
                                        if (!in_array($scorerFeederId, $teamsLastScorersArr[$feederId]))
                                            $teamsLastScorersArr[$feederId][] = $scorerFeederId;
                                        if (count($teamsLastScorersArr[$feederId]) == $teamsLastScorersMaxNumber)
                                            break 2;
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($teamsLastScorersArr)) {
                        $teamsFeederIds = array(
                            $match->getHomeTeam()->getFeederId() => $match->getHomeTeam(),
                            $match->getAwayTeam()->getFeederId() => $match->getAwayTeam(),
                        );
                        foreach ($teamsFeederIds as $feederId => $team) {
                            if (array_key_exists($feederId, $teamsLastScorersArr)) {
                                $teamLastScorers = $teamsLastScorersArr[$feederId];
                                foreach ($teamLastScorers as $i => $teamLastScorer) {
                                    $player = $playerDAO->findOneByFeederId($teamLastScorer);
                                    if ($player != null) {
                                        $place = $i + 1;
                                        $topScorer = $match->getPreMatchReportMostRecentScorerByTeamAndPlace($team, $place);
                                        if ($topScorer == null) {
                                            $topScorer = new PreMatchReportMostRecentScorer();
                                            $topScorer->setMatch($match);
                                            $topScorer->setTeam($team);
                                            $topScorer->setPlace($place);
                                            $match->addPreMatchReportMostRecentScorer($topScorer);
                                        }
                                        $topScorer->setPlayer($player);
                                    }
                                }
                            }
                        }
                    }
                }

                // top scorers
                if (!empty($matchData->Rankings)) {
                    $homeTeamTopScorersData = $this->getNodeByAttribute($matchData->Rankings, 'Team', 'uID', 't' . $match->getHomeTeam()->getFeederId());
                    $awayTeamTopScorersData = $this->getNodeByAttribute($matchData->Rankings, 'Team', 'uID', 't' . $match->getAwayTeam()->getFeederId());
                    $teamsTopScorersData = array(
                        $match->getHomeTeam()->getFeederId() => $homeTeamTopScorersData,
                        $match->getAwayTeam()->getFeederId() => $awayTeamTopScorersData,
                    );
                    $teamsTopScorersMaxNumber = 3;
                    $teamsTopScorersArr = array();
                    foreach($teamsTopScorersData as $feederId => $teamTopScorersData) {
                        $teamTopScorersStatsData = $this->getNodeByAttribute($teamTopScorersData->Overall, 'Stat', 'Type', 'goals_scored');
                        if ($teamTopScorersStatsData !== null) {
                            $teamTopScorersRanks = $teamTopScorersStatsData->Rank;
                            foreach ($teamTopScorersRanks as $teamTopScorersRank) {
                                $goalsScored = $this->getXmlAttribute($teamTopScorersRank, 'Total');
                                $teamTopScorers = $teamTopScorersRank->Player;
                                foreach ($teamTopScorers as $teamTopScorer) {
                                    if (!array_key_exists($feederId, $teamsTopScorersArr))
                                        $teamsTopScorersArr[$feederId] = array();
                                    $scorerFeederId = $this->getIdFromString($this->getNodeValue($teamTopScorer));
                                    $teamsTopScorersArr[$feederId][$scorerFeederId] = $goalsScored;
                                    if (count($teamsTopScorersArr[$feederId]) == $teamsTopScorersMaxNumber)
                                        break 2;
                                }
                            }
                        }
                    }
                    if (!empty($teamsTopScorersArr)) {
                        $teamsFeederIds = array(
                            $match->getHomeTeam()->getFeederId() => $match->getHomeTeam(),
                            $match->getAwayTeam()->getFeederId() => $match->getAwayTeam(),
                        );
                        foreach ($teamsFeederIds as $feederId => $team) {
                            $place = 0;
                            if (array_key_exists($feederId, $teamsTopScorersArr)) {
                                $teamTopScorers = $teamsTopScorersArr[$feederId];
                                foreach ($teamTopScorers as $teamTopScorer => $goalsScored) {
                                    $player = $playerDAO->findOneByFeederId($teamTopScorer);
                                    if ($player != null) {
                                        $topScorer = $match->getPreMatchReportTopScorerByTeamAndPlace($team, ++$place);
                                        if ($topScorer == null) {
                                            $topScorer = new PreMatchReportTopScorer();
                                            $topScorer->setMatch($match);
                                            $topScorer->setTeam($team);
                                            $topScorer->setPlace($place);
                                            $match->addPreMatchReportTopScorers($topScorer);
                                        }
                                        $topScorer->setGoals($goalsScored);
                                        $topScorer->setPlayer($player);
                                    }
                                }
                            }
                        }
                    }
                }

                $matchDAO->save($match);

            }

            $this->finishProgress($console, 'F2', $filePath);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::CRIT, $console);
        }
    }

    private function getXMLContent($filePath) {
        $fileContents = file_get_contents($filePath);
        $xml = simplexml_load_string($fileContents);
        if ($xml === false)
            throw new \Exception(MessagesConstants::ERROR_CANNOT_BE_PARSED);
        return $xml;
    }

    private function getIdFromString($str) {
        return preg_replace("/([a-z]*)/", "", $str);
    }

    /**
     * @param \SimpleXMLElement $xmlObj
     * @param $index
     * @param string|null $format
     * @param null|\DateTimeZone $tz
     * @return \DateTime|string|null
     */
    private function getNodeValue(\SimpleXMLElement $xmlObj, $index = -1, $format = null, $tz = null) {

        $xmlNode = $index != -1 ? $xmlObj->{$index} : $xmlObj;
        $xmlValue = $xmlNode != null ? $xmlNode->__toString() : null;
        if (empty($xmlValue) || $xmlValue == 'Unknown')
            return null;
        else if ($format != null) {
            if ($tz != null && $tz instanceof \DateTimeZone)
                $xmlValue = \DateTime::createFromFormat($format, $xmlValue, $tz);
            else
                $xmlValue = \DateTime::createFromFormat($format, $xmlValue);
        }
        return $xmlValue;

    }

    /**
     * @param \SimpleXMLElement $xmlObj
     * @param $nodeName
     * @param $attrName
     * @param $value
     * @param string|null $format
     * @return \DateTime|string|null
     */
    private function getNodeValueByAttribute(\SimpleXMLElement $xmlObj, $nodeName, $attrName, $value, $format = null) {
        $xmlNode = $this->getNodeByAttribute($xmlObj, $nodeName, $attrName, $value);
        $nodeValue = !empty($xmlNode) ? $xmlNode->__toString() : null;
        if (!empty($nodeValue) && $format !== null)
            $nodeValue = \DateTime::createFromFormat($format, $nodeValue);
        if ($nodeValue == 'Unknown')
            $nodeValue = null;
        return $nodeValue;
    }

    /**
     * @param \SimpleXMLElement $xmlObj
     * @param string $nodeName
     * @param string $attrName
     * @param string $value
     * @return null|\SimpleXMLElement
     */
    private function getNodeByAttribute(\SimpleXMLElement $xmlObj, $nodeName, $attrName, $value) {
        $xmlNode = $xmlObj->xpath($nodeName . '[@' . $attrName . '=\'' . $value . '\']');
        return !empty($xmlNode) ? $xmlNode[0] : null;
    }

    private function getXmlAttribute(\SimpleXMLElement $obj, $attrName) {

        $attributes = $obj->attributes();
        $attributes = iterator_to_array($attributes);
        if (array_key_exists($attrName, $attributes)) {
            $attrValue = $attributes[$attrName];
            $attrValue = $attrValue->__toString();
        } else $attrValue = '';
        return $attrValue;

    }

    /**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @param string $feedType
     * @param string $feedFilePath
     */
    private function startProgress($console, $feedType, $feedFilePath) {
        $info = sprintf(MessagesConstants::LOG_FEED_IMPORT_STARTED, $feedType, $feedFilePath);
        LogManager::getInstance($this->getServiceLocator())->logOptaMessage($info, Logger::INFO);
        if ($console != null) {
            $console->clearScreen();
            $console->writeLine("");
            $console->writeLine($info);
            $console->writeLine("");
        }
    }

    /**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @param string $feedType
     * @param string $feedFilePath
     */
    private function finishProgress($console, $feedType, $feedFilePath) {
        $info = sprintf(MessagesConstants::LOG_FEED_IMPORT_FINISHED, $feedType, $feedFilePath);
        LogManager::getInstance($this->getServiceLocator())->logOptaMessage($info, Logger::INFO);
        if ($console != null) {
            $console->writeLine("");
            $console->writeLine($info);
        }
    }

    private $progressLength;
    private $progressCounter;

    /**
     * @param int $length
     */
    private function setProgressLength($length) {
        $this->progressLength = $length;
        $this->progressCounter = 0;
    }

    /**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    private function doProgress($console) {
        if ($console != null) {
            $percentage = (int)((++$this->progressCounter / $this->progressLength) * 100);
            if ($percentage < 100)
                $console->write($percentage . "% - ");
            else
                $console->writeLine($percentage . "%");
        }
    }

    /**
     * @param string $message
     * @param int $priority
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    private function logMessage($message, $priority = Logger::INFO, $console = null) {
        LogManager::getInstance($this->getServiceLocator())->logOptaMessage($message, $priority, $console);
        if ($console != null) {
            $console->writeLine("");
            $console->writeLine($message);
        } else {
            $flashMessenger = $this->getServiceLocator()->get('ControllerPluginManager')->get('FlashMessenger');
            switch ($priority) {
                case Logger::INFO:
                case Logger::NOTICE:
                    $flashMessenger->addMessage(MessagesConstants::MESSAGE_PREFIX . $message);
                    break;
                case Logger::WARN:
                    $flashMessenger->addErrorMessage(MessagesConstants::MESSAGE_PREFIX . $message);
                    break;
            }
        }
    }

    public function dispatchFeedsByType($type, $force = false, $console = null) {

        switch ($type) {

            // export TZ=UTC;
            case Feed::F1_TYPE: // 10 10 * * * cd <APP_ROOT>; php public/index.php opta F1
            case Feed::F40_TYPE: // 0 0,12 * * * cd <APP_ROOT>; php public/index.php opta F40
                $feeds = $this->getUploadedFeedsByType($type);
                if (!empty($feeds)) {
                    $seasonManager = SeasonManager::getInstance($this->getServiceLocator());
                    $seasons = $seasonManager->getAllNotFinishedSeasons();
                    if (empty($seasons))
                        throw new OutOfSeasonException();
                    $processingStarted = false;
                    foreach ($seasons as $season) {
                        $seasonFeeds = $this->filterFeedsByParameters($feeds, $type, array('season_id' => $season->getFeederId()));
                        foreach ($seasonFeeds as $seasonFeed)
                            if ($force || $this->hasToBeProcessed($seasonFeed, $type, $season)) {
                                $processingStarted = true;
                                $this->processingStarted($seasonFeed, $type, $season);
                                $this->saveFeedsChanges();
                                $success = $type == Feed::F1_TYPE ? $this->parseF1Feed($seasonFeed, $season, $console) :
                                ($type == Feed::F40_TYPE ? $this->parseF40Feed($seasonFeed, $season, $console) : false);
                                $this->processingCompleted($seasonFeed, $type, $season, $success);
                                $this->saveFeedsChanges();
                            }
                    }
                    if ($processingStarted)
                        $this->clearAppCache($type, $console);
                }
                break;

            case Feed::F7_TYPE: // */5 * * * * cd <APP_ROOT>; php public/index.php opta F7

                $feeds = $this->getUploadedFeedsByType($type);
                if (!empty($feeds)) {
                    $currentSeason = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
                    if ($currentSeason === null)
                        throw new OutOfSeasonException();
                    $matchManager = MatchManager::getInstance($this->getServiceLocator());
                    $unfinishedAndPredictableMatches = $matchManager->getUnfinishedAndPredictableMatches($currentSeason, true);
                    $processingStarted = false;
                    $clearCacheApp = array();
                    foreach ($unfinishedAndPredictableMatches as $match) {
                        $seasonOptaId = $currentSeason->getFeederId();
                        $matchFeeds = $this->filterFeedsByParameters($feeds, $type, array(
                            'season_id' => $seasonOptaId,
                            'game_id' => $match['feederId'],
                        ));
                        foreach ($matchFeeds as $matchFeed)
                            if ($force || $this->hasToBeProcessed($matchFeed, $type, $currentSeason, array('startTime' => $match['startTime']))) {
                                $processingStarted = true;
                                $this->processingStarted($matchFeed, $type, $currentSeason);
                                $this->saveFeedsChanges();
                                $success = $this->parseF7Feed($matchFeed, $console);
                                if ($success !== false && is_array($success)) {
                                    $clearCacheApp = array_merge($clearCacheApp, $success);
                                    $success = true;
                                }
                                $this->processingCompleted($matchFeed, $type, $currentSeason, $success);
                                $this->saveFeedsChanges();
                            }
                    }
                    if ($processingStarted)
                        $this->clearAppCache($type, $console, array_unique($clearCacheApp));
                }
                break;

            case Feed::F2_TYPE: // 0 */4 * * * cd <APP_ROOT>; php public/index.php opta F2

                $feeds = $this->getUploadedFeedsByType($type);
                if (!empty($feeds)) {
                    $currentSeason = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentSeason();
                    if ($currentSeason === null)
                        throw new OutOfSeasonException();
                    $matchManager = MatchManager::getInstance($this->getServiceLocator());
                    $unfinishedAndPredictableMatches = $matchManager->getUnfinishedAndPredictableMatches($currentSeason, true);
                    $processingStarted = false;
                    foreach ($unfinishedAndPredictableMatches as $match) {
                        $matchFeeds = $this->filterFeedsByParameters($feeds, $type, array(
                            'game_id' => $match['feederId'],
                        ));
                        foreach ($matchFeeds as $matchFeed)
                            if ($force || $this->hasToBeProcessed($matchFeed, $type, $currentSeason)) {
                                $processingStarted = true;
                                $this->processingStarted($matchFeed, $type, $currentSeason);
                                $this->saveFeedsChanges();
                                $this->parseF2Feed($matchFeed, $console);
                                $this->processingCompleted($matchFeed, $type, $currentSeason);
                                $this->saveFeedsChanges();
                            }
                    }
                    if ($processingStarted)
                        $this->clearAppCache($type, $console);
                }
                break;

        }
    }

    private static $feedsPatterns = array(
        Feed::F1_TYPE => 'srml-{competition_id}-{season_id}-results.xml',
        Feed::F2_TYPE => 'opta-{game_id}-matchpreview.xml',
        Feed::F7_TYPE => 'srml-{competition_id}-{season_id}-f{game_id}-matchresults.xml',
        Feed::F40_TYPE => 'srml-{competition_id}-{season_id}-squads.xml',
    );

    public function getAvailableFeedTypes() {
        return array_keys(self::$feedsPatterns);
    }

    public function getFeedTypeByName($feedName) {
        foreach (self::$feedsPatterns as $feedType => $feedPattern) {
            $feedPattern = preg_quote(preg_replace('/(\{[^\}]*\})/', '*', $feedPattern));
            $feedPattern = '/' . preg_replace('/(\\\\\*)/', '([\d]*)', $feedPattern) . '/';
            if (preg_match($feedPattern, $feedName) > 0)
                return $feedType;
        }
        return false;
    }

    public function getUploadedFeedsByType($type) {
        $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
        $optaDirPath = $applicationManager->getAppOptaDirPath();
        $feedPattern = self::$feedsPatterns[$type];
        $feedPattern = preg_replace('/(\{[^\}]*\})/', '*', $feedPattern);
        return glob($optaDirPath . DIRECTORY_SEPARATOR . $feedPattern);
    }

    public function filterFeedsByParameters($feeds, $type, $parameters) {
        $feedPattern = self::$feedsPatterns[$type];
        foreach ($parameters as $parameterName => $parameterValue)
            $feedPattern = preg_replace("/{" . $parameterName . "}/", $parameterValue, $feedPattern);
        $feedPattern = preg_quote(preg_replace('/(\{[^\}]*\})/', '*', $feedPattern));
        $feedPattern = '/' . preg_replace('/(\\\\\*)/', '([\d]*)', $feedPattern) . '/';
        return array_filter($feeds, function($feed) use ($feedPattern) {
            return preg_match($feedPattern, $feed) > 0;
        });
    }

    /**
     * @param string $feedFilePath
     * @param string $feedType
     * @param Season $season
     * @param array $options
     * @return bool
     */
    public function hasToBeProcessed($feedFilePath, $feedType, $season, $options = array()) {
        if ($feedType == Feed::F7_TYPE && array_key_exists('startTime', $options)) {
            $matchStartTime = $options['startTime'];
            $feedLastUpdate = new \DateTime();
            $feedLastUpdate->setTimestamp(filemtime($feedFilePath));
            if ($matchStartTime->diff($feedLastUpdate)->days > self::PARSE_F7_FEEDS_DAYS_BEFORE_START)
                return false;
        }
        $feedInfo = pathinfo($feedFilePath);
        $feedName = $feedInfo['basename'];
        $feedDAO = FeedDAO::getInstance($this->getServiceLocator());
        $feed = $feedDAO->getFeedByFileNameAndSeason($feedName, $season->getId(), true, true);
        if ($feed !== null) {
            $lastFileUpdate = new \DateTime();
            $lastFileUpdate->setTimestamp(filemtime($feedFilePath));
            $lastFeedUpdate = $feed['lastUpdate'];
            return $feed['lastSyncResult'] != Feed::IN_PROGRESS_RESULT && $lastFeedUpdate < $lastFileUpdate;
        }
        return true;
    }

    /**
     * @param string $feedFilePath
     * @param string $type
     * @param Season $season
     */
    public function processingStarted($feedFilePath, $type, $season) {
        $feedInfo = pathinfo($feedFilePath);
        $feedName = $feedInfo['basename'];
        $feedDAO = FeedDAO::getInstance($this->getServiceLocator());
        $feed = $feedDAO->getFeedByFileNameAndSeason($feedName, $season->getId(), false, true);
        if ($feed === null) {
            $feed = new Feed();
            $feed->setType($type);
            $feed->setFileName($feedName);
            $feed->setSeason($season);
        }
        $lastUpdate = new \DateTime();
        $lastUpdate->setTimestamp(filemtime($feedFilePath));
        $feed->setLastUpdate($lastUpdate);
        $feed->setLastSyncResult(Feed::IN_PROGRESS_RESULT);
        $feedDAO->save($feed, false, false);
    }

    /**
     * @param string $feedFilePath
     * @param string $type
     * @param Season $season
     * @param bool $successfully
     */
    public function processingCompleted($feedFilePath, $type, $season, $successfully = true) {
        $feedInfo = pathinfo($feedFilePath);
        $feedName = $feedInfo['basename'];
        $feedDAO = FeedDAO::getInstance($this->getServiceLocator());
        $feed = $feedDAO->getFeedByFileNameAndSeason($feedName, $season->getId(), false, true);
        if ($feed === null) {
            $feed = new Feed();
            $feed->setType($type);
            $feed->setFileName($feedName);
            $feed->setSeason($season);
        }
        $lastUpdate = new \DateTime();
        $lastUpdate->setTimestamp(filemtime($feedFilePath));
        $feed->setLastUpdate($lastUpdate);
        $feed->setLastSyncResult($successfully ? Feed::SUCCESS_RESULT : Feed::ERROR_RESULT);
        $feedDAO->save($feed, false, false);
    }

    public function saveFeedsChanges() {
        $feedDAO = FeedDAO::getInstance($this->getServiceLocator());
        $feedDAO->flush();
        $feedDAO->clearCache();
    }

    public function clearAppCache($type, $console, $options = array()) {
        if ($console !== null) {
            $entitiesToBeCleared = array();
            switch ($type) {
                case Feed::F1_TYPE:
                    $entitiesToBeCleared[] = CompetitionDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = CompetitionSeasonDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = TeamDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = MatchDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    break;

                case Feed::F2_TYPE:
                    $entitiesToBeCleared[] = PreMatchReportAvgGoalsScoredDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = PreMatchReportFormGuideDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = PreMatchReportGoalsScoredDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = PreMatchReportHeadToHeadDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = PreMatchReportLastSeasonMatchDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = PreMatchReportMostRecentScorerDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = PreMatchReportTopScorerDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    break;

                case Feed::F7_TYPE:
                    $entitiesToBeCleared = $options;
                    break;

                case Feed::F40_TYPE:
                    $entitiesToBeCleared[] = CompetitionDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = CompetitionSeasonDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = TeamDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = PlayerDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    break;
            }

            if (!empty($entitiesToBeCleared)) {
                $entitiesToBeClearedQueryString = implode(",", $entitiesToBeCleared);
                $clearAppCacheUrl = ApplicationManager::getInstance($this->getServiceLocator())->getClearAppCacheUrl();
                $clearAppCacheUrl = $clearAppCacheUrl . urlencode($entitiesToBeClearedQueryString);
                $clearAppCacheResult = file_get_contents($clearAppCacheUrl);
                if ($clearAppCacheResult == ClearAppCacheController::OK_MESSAGE)
                    $this->logMessage(MessagesConstants::APP_CACHE_CLEARED, Logger::INFO, $console);
                else
                    $this->logMessage(MessagesConstants::APP_CACHE_NOT_CLEARED, Logger::WARN, $console);
            }
        }
    }

}
