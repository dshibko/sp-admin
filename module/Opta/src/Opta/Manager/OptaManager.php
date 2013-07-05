<?php

namespace Opta\Manager;

use Application\Manager\LeagueManager;
use \Application\Manager\MatchManager;
use Application\Manager\PlayerManager;
use Application\Manager\RegionManager;
use \Application\Manager\SeasonManager;
use Application\Manager\UserManager;
use Application\Model\Entities\Season;
use Neoco\Exception\OutOfSeasonException;
use \Opta\Controller\ClearAppCacheController;
use \Application\Model\DAOs\FeedDAO;
use \Application\Model\Entities\Feed;
use \Application\Model\DAOs\MatchGoalDAO;
use Zend\Mvc\Application;
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

                $competitionDAO = CompetitionDAO::getInstance($this->getServiceLocator());
                $competitionFeederId = $this->getXmlAttribute($xml->SoccerDocument, 'competition_id');
                if (empty($competitionFeederId))
                    throw new \Exception(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'competition_id'));
                $competition = $season->getCompetitionByFeederId($competitionFeederId);
                if ($competition == null) {
                    $competition = new Competition();
                    $competition->setFeederId($competitionFeederId);
                    $competition->setSeason($season);
                }
                $competition->setDisplayName($this->getXmlAttribute($xml->SoccerDocument, 'competition_name'));

                $competitionDAO->save($competition);

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
                        $team = $teamDAO->findOneByFeederId($teamFeederId);
                        if ($team == null) {
                            $team = new Team();
                            $team->setFeederId($teamFeederId);
                        }
                        if (!$team->hasCompetition($competition))
                            $team->addCompetition($competition);
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
                            if ($player->hasCompetition($competition))
                                $player->removeCompetition($competition);

                        foreach ($teamXml->Player as $playerXml) {
                            try {
                                $playerFeederId = $this->getIdFromString($this->getXmlAttribute($playerXml, 'uID'));
                                if (empty($playerFeederId)) {
                                    $this->logMessage(sprintf(MessagesConstants::ERROR_FIELD_IS_EMPTY, 'player_id'), Logger::WARN, $console);
                                    continue;
                                }
                                $player = $playerDAO->findOneByFeederId($playerFeederId);
                                if ($player == null) {
                                    $player = new Player();
                                    $player->setFeederId($playerFeederId);
                                    $team->addPlayer($player);
                                } else if ($player->getTeam()->getId() != $team->getId()) {
                                    $player->clearCompetitions();
                                    $team->addPlayer($player);
                                }
                                if (!$player->hasCompetition($competition))
                                    $player->addCompetition($competition);

                                $player->setTeam($team);
                                if (!$player->getIsBlocked()) {
                                    $player->setDisplayName($playerXml->Name->__toString());
                                    $player->setShirtNumber($this->getNodeValue($playerXml->Stat, 6));
                                }
                                $player->setPosition($playerXml->Position->__toString());
                                $player->setName($playerXml->Stat->{0}->__toString());
                                $player->setSurname($playerXml->Stat->{1}->__toString());
                                $player->setBirthDate($this->getNodeValue($playerXml->Stat, 3, 'Y-m-d'));
                                $player->setWeight($this->getNodeValue($playerXml->Stat, 4));
                                $player->setHeight($this->getNodeValue($playerXml->Stat, 5));
                                $player->setRealPosition($this->getNodeValue($playerXml->Stat, 7));
                                $player->setRealPositionSide($this->getNodeValue($playerXml->Stat, 8));
                                $player->setJoinDate($this->getNodeValue($playerXml->Stat, 9, 'Y-m-d'));
                                $player->setCountry($this->getNodeValue($playerXml->Stat, 10));
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

                    $competition = $season->getCompetitionByFeederId($competitionFeederId);

                    if ($competition == null) {
                        $competition = new Competition();
                        $competition->setFeederId($competitionFeederId);
                        $competition->setSeason($season);
                    }
                    $competition->setDisplayName($this->getXmlAttribute($xml->SoccerDocument, 'competition_name'));

                    $competitionDAO = CompetitionDAO::getInstance($this->getServiceLocator());
                    $competitionDAO->save($competition);

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
                            $match = $matchDAO->findOneByFeederId($matchFeederId);
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

                                $match->setCompetition($competition);
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

                                $homeTeam = $teamDAO->findOneByFeederId($homeTeamFeederId);
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
                                $awayTeam = $teamDAO->findOneByFeederId($awayTeamFeederId);
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
            $type = $this->getXmlAttribute($xml->SoccerDocument, 'Type');
            $match = $matchDAO->findOneByFeederId($matchFeederId);

            if ($type == 'Result' && $match != null &&
                $match->getStatus() != Match::FULL_TIME_STATUS) {

                $result = $this->getXmlAttribute($xml->SoccerDocument->MatchData->MatchInfo->Result, 'Type');

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

                if ($result == 'NormalResult') {
                    $match->setHomeTeamFullTimeScore($this->getXmlAttribute($homeTeamData, 'Score'));
                    $match->setAwayTeamFullTimeScore($this->getXmlAttribute($awayTeamData, 'Score'));
                } else {
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

                }

                $playerDAO = PlayerDAO::getInstance($this->getServiceLocator());
                $teamDAO = TeamDAO::getInstance($this->getServiceLocator());

                $homeTeamFeederId = $this->getIdFromString($this->getXmlAttribute($homeTeamData, 'TeamRef'));
                $homeTeam = $teamDAO->findOneByFeederId($homeTeamFeederId);
                $awayTeamFeederId = $this->getIdFromString($this->getXmlAttribute($awayTeamData, 'TeamRef'));
                $awayTeam = $teamDAO->findOneByFeederId($awayTeamFeederId);

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
                            $players[$playerFeederId] = $playerDAO->findOneByFeederId($playerFeederId);
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

                $match->setStatus(Match::FULL_TIME_STATUS);
                $matchDAO->save($match);

                MatchGoalDAO::getInstance($this->getServiceLocator())->clearCache();

            }

            $period = $this->getXmlAttribute($xml->SoccerDocument->MatchData->MatchInfo, 'Period');

            if ($type == 'Latest' && $period == 'PreMatch' && $match != null && !$match->getHasLineUp() &&
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
                        $teamObj = $teamDAO->findOneByFeederId($teamFeederId);
                        if ($teamObj != null && $teamData->PlayerLineUp != null) {
                            $hasLineUp = true;
                            $squad = $teamData->PlayerLineUp->MatchPlayer;
                            if ($squad != null)
                                foreach ($squad as $player) {
                                    $lineUpPlayer = new LineUpPlayer();
                                    $playerFeederId = $this->getIdFromString($this->getXmlAttribute($player, 'PlayerRef'));
                                    $playerObj = $playerDAO->findOneByFeederId($playerFeederId);
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
                        LineUpPlayerDAO::getInstance($this->getServiceLocator())->clearCache();
                    }
                }
            }

            if ($type == 'Latest' && ($period == 'FirstHalf' || $period == 'HalfTime' && $period == 'SecondHalf') && $match != null && $match->getStatus() == Match::PRE_MATCH_STATUS) {
                $match->setStatus(Match::LIVE_STATUS);
                $matchDAO->save($match);
            }

            $this->finishProgress($console, 'F7', $filePath);

            return true;

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e, Logger::CRIT, $console);
            return false;
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
            echo "\r";
            $percentage = (int)((++$this->progressCounter / $this->progressLength) * 100);
            if ($percentage < 100)
                $console->writeAt($percentage . "%", 0, 0);
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
                    $applicationManager = ApplicationManager::getInstance($this->getServiceLocator());
                    $currentSeason = $applicationManager->getCurrentSeason();
                    if ($currentSeason === null)
                        throw new OutOfSeasonException();
                    $processingStarted = false;
                    $seasonFeeds = $this->filterFeedsByParameters($feeds, $type, array('season_id' => $currentSeason->getFeederId()));
                    foreach ($seasonFeeds as $seasonFeed)
                        if ($force || $this->hasToBeProcessed($seasonFeed)) {
                            $processingStarted = true;
                            $this->processingStarted($seasonFeed, $type);
                            $this->saveFeedsChanges();
                            $success = $type == Feed::F1_TYPE ? $this->parseF1Feed($seasonFeed, $currentSeason, $console) :
                                ($type == Feed::F40_TYPE ? $this->parseF40Feed($seasonFeed, $currentSeason, $console) : false);
                            $this->processingCompleted($seasonFeed, $type, $success);
                            $this->saveFeedsChanges();
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
                    $unfinishedAndPredictableMatches = $matchManager->getUnfinishedAndPredictableMatches($currentSeason, true, true);
                    $processingStarted = false;
                    foreach ($unfinishedAndPredictableMatches as $match) {
                        $seasonOptaId = $currentSeason->getFeederId();
                        $matchFeeds = $this->filterFeedsByParameters($feeds, $type, array(
                            'season_id' => $seasonOptaId,
                            'game_id' => $match['feederId'],
                        ));
                        foreach ($matchFeeds as $matchFeed)
                            if ($force || $this->hasToBeProcessed($matchFeed)) {
                                $processingStarted = true;
                                $this->processingStarted($matchFeed, $type);
                                $this->saveFeedsChanges();
                                $success = $this->parseF7Feed($matchFeed, $console);
                                $this->processingCompleted($matchFeed, $type, $success);
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

    public function hasToBeProcessed($feedFilePath) {
        $feedInfo = pathinfo($feedFilePath);
        $feedName = $feedInfo['basename'];
        $feedDAO = FeedDAO::getInstance($this->getServiceLocator());
        $feed = $feedDAO->getFeedByFileName($feedName, true, true);
        if ($feed !== null) {
            $lastFileUpdate = new \DateTime();
            $lastFileUpdate->setTimestamp(filemtime($feedFilePath));
            $lastFeedUpdate = $feed['lastUpdate'];
            return $feed['lastSyncResult'] != Feed::IN_PROGRESS_RESULT && $lastFeedUpdate < $lastFileUpdate;
        }
        return true;
    }

    public function processingStarted($feedFilePath, $type) {
        $feedInfo = pathinfo($feedFilePath);
        $feedName = $feedInfo['basename'];
        $feedDAO = FeedDAO::getInstance($this->getServiceLocator());
        $feed = $feedDAO->getFeedByFileName($feedName, false, true);
        if ($feed === null) {
            $feed = new Feed();
            $feed->setType($type);
            $feed->setFileName($feedName);
        }
        $lastUpdate = new \DateTime();
        $lastUpdate->setTimestamp(filemtime($feedFilePath));
        $feed->setLastUpdate($lastUpdate);
        $feed->setLastSyncResult(Feed::IN_PROGRESS_RESULT);
        $feedDAO->save($feed, false, false);
    }

    public function processingCompleted($feedFilePath, $type, $successfully = true) {
        $feedInfo = pathinfo($feedFilePath);
        $feedName = $feedInfo['basename'];
        $feedDAO = FeedDAO::getInstance($this->getServiceLocator());
        $feed = $feedDAO->getFeedByFileName($feedName, false, true);
        if ($feed === null) {
            $feed = new Feed();
            $feed->setType($type);
            $feed->setFileName($feedName);
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

    public function clearAppCache($type, $console) {
        if ($console !== null) {
            $entitiesToBeCleared = array();
            switch ($type) {
                case Feed::F1_TYPE:
                    $entitiesToBeCleared[] = CompetitionDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = TeamDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = MatchDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    break;

                case Feed::F7_TYPE:
                    $entitiesToBeCleared[] = MatchDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = MatchGoalDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = LineUpPlayerDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    break;

                case Feed::F40_TYPE:
                    $entitiesToBeCleared[] = CompetitionDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = TeamDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    $entitiesToBeCleared[] = PlayerDAO::getInstance($this->getServiceLocator())->getRepositoryName();
                    break;
            }

            $entitiesToBeClearedQueryString = implode(",", $entitiesToBeCleared);
            $clearAppCacheUrl = ApplicationManager::getInstance($this->getServiceLocator())->getClearAppCacheUrl();
            $clearAppCacheUrl = $clearAppCacheUrl . $entitiesToBeClearedQueryString;
            $clearAppCacheResult = file_get_contents($clearAppCacheUrl);
            if ($clearAppCacheResult == ClearAppCacheController::OK_MESSAGE)
                $this->logMessage(MessagesConstants::APP_CACHE_CLEARED, Logger::INFO, $console);
            else
                $this->logMessage(MessagesConstants::APP_CACHE_NOT_CLEARED, Logger::WARN, $console);
        }
    }

}
