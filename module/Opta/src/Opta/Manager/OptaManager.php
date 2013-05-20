<?php

namespace Opta\Manager;

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
     * @param \SimpleXMLElement $xml
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    public function parseF40Feed($xml, $console) {

        try {
            $this->startProgress($console, true, 'F40');

            $seasonDAO = SeasonDAO::getInstance($this->getServiceLocator());
            $seasonFeederId = $this->getXmlAttribute($xml->SoccerDocument, 'season_id');
            $season = $seasonDAO->getRepository()->findOneByFeederId($seasonFeederId);

            if ($season != null) {

                $competitionDAO = CompetitionDAO::getInstance($this->getServiceLocator());
                $competitionFeederId = $this->getXmlAttribute($xml->SoccerDocument, 'competition_id');
                $competition = $season->getCompetitionByFeederId($competitionFeederId);
                if ($competition == null) {
                    $competition = new Competition();
                    $competition->setFeederId($competitionFeederId);
                }
                $competition->setSeason($season);
                $competition->setDisplayName($this->getXmlAttribute($xml->SoccerDocument, 'competition_name'));

                $competitionDAO->save($competition);

                $teamDAO = TeamDAO::getInstance($this->getServiceLocator());
                $playerDAO = PlayerDAO::getInstance($this->getServiceLocator());

                $this->setProgressLength($xml->SoccerDocument->Team->count());

                foreach ($xml->SoccerDocument->Team as $teamXml) {
                    try {
                        $teamFeederId = $this->getIdFromString($this->getXmlAttribute($teamXml, 'uID'));
                        $team = $teamDAO->getRepository()->findOneByFeederId($teamFeederId);
                        if ($team == null) {
                            $team = new Team();
                            $team->setFeederId($teamFeederId);
                        }
                        if (!$team->hasCompetition($competition))
                            $team->addCompetition($competition);
                        $team->setDisplayName($teamXml->Name->__toString());
                        $team->setShortName($this->getNodeValue($teamXml->SYMID));
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
                            $playerFeederId = $this->getIdFromString($this->getXmlAttribute($playerXml, 'uID'));
                            $player = $playerDAO->getRepository()->findOneByFeederId($playerFeederId);
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
                            $player->setDisplayName($playerXml->Name->__toString());
                            $player->setPosition($playerXml->Position->__toString());
                            $player->setName($playerXml->Stat->{0}->__toString());
                            $player->setSurname($playerXml->Stat->{1}->__toString());
                            $player->setBirthDate($this->getNodeValue($playerXml->Stat, 3, 'Y-m-d'));
                            $player->setWeight($this->getNodeValue($playerXml->Stat, 4));
                            $player->setHeight($this->getNodeValue($playerXml->Stat, 5));
                            $player->setShirtNumber($this->getNodeValue($playerXml->Stat, 6));
                            $player->setRealPosition($this->getNodeValue($playerXml->Stat, 7));
                            $player->setRealPositionSide($this->getNodeValue($playerXml->Stat, 8));
                            $player->setJoinDate($this->getNodeValue($playerXml->Stat, 9, 'Y-m-d'));
                            $player->setCountry($this->getNodeValue($playerXml->Stat, 10));

                        }

                        $teamDAO->save($team);

                        $teamDAO->detach($team);

                    } catch (\Exception $e) {
                        ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e);
                    }

                    $this->doProgress($console);

                }
            }

            $this->finishProgress($console, true, 'F40');

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e);
        }

    }

    /**
     * @param \SimpleXMLElement $xml
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    public function parseF1Feed($xml, $console) {

        try {
            $this->startProgress($console, true, 'F1');

//            $nonCalculatedMatches = array();

            $seasonDAO = SeasonDAO::getInstance($this->getServiceLocator());
            $seasonFeederId = $this->getXmlAttribute($xml->SoccerDocument, 'season_id');
            $season = $seasonDAO->getRepository()->findOneByFeederId($seasonFeederId);

            $matchDAO = MatchDAO::getInstance($this->getServiceLocator());

            if ($season != null) {

                $competitionFeederId = $this->getXmlAttribute($xml->SoccerDocument, 'competition_id');
                $competition = $season->getCompetitionByFeederId($competitionFeederId);

                if ($competition != null) {

                    $teamDAO = TeamDAO::getInstance($this->getServiceLocator());

                    $this->setProgressLength($xml->SoccerDocument->MatchData->count());

                    foreach ($xml->SoccerDocument->MatchData as $matchXml) {
                        try {
                            $matchFeederId = $this->getIdFromString($this->getXmlAttribute($matchXml, 'uID'));
                            $match = $matchDAO->getRepository()->findOneByFeederId($matchFeederId);
                            if ($match == null) {
                                $match = new Match();
                                $match->setFeederId($matchFeederId);
                            }

                            $match->setCompetition($competition);
                            $match->setWeek($this->getXmlAttribute($matchXml->MatchInfo, 'MatchDay'));
                            $status = $this->getXmlAttribute($matchXml->MatchInfo, 'Period');
                            if (!in_array($status, Match::getAvailableStatuses()))
                                $status = Match::LIVE_STATUS;

//                            $isJustFinishedMatch = $status == Match::FULL_TIME_STATUS && $match->getStatus() != Match::FULL_TIME_STATUS;
//                            $isNonCalculatedMatch = $isJustFinishedMatch && !$match->getPredictions()->isEmpty();

                            $match->setStatus($status);
                            $timezoneAbbr = $this->getNodeValue($matchXml->MatchInfo, 'TZ');
                            $timezone = !empty($timezoneAbbr) ? new \DateTimeZone(timezone_name_from_abbr($timezoneAbbr)) : null;
                            $match->setStartTime($this->getNodeValue($matchXml->MatchInfo, 'Date', 'Y-m-d G:i:s', $timezone));

                            $match->setStadiumName($this->getNodeValue($matchXml->Stat, 0));
                            $match->setCityName($this->getNodeValue($matchXml->Stat, 1));

                            $team1FeederId = $this->getIdFromString($this->getXmlAttribute($matchXml->TeamData->{0}, 'TeamRef'));
                            $team2FeederId = $this->getIdFromString($this->getXmlAttribute($matchXml->TeamData->{1}, 'TeamRef'));
                            $team2Side = $this->getXmlAttribute($matchXml->TeamData->{1}, 'Side');

                            if ($team2Side == 'Home') {
                                $homeTeamFeederId = $team2FeederId;
                                $awayTeamFeederId = $team1FeederId;
                            } else {
                                $homeTeamFeederId = $team1FeederId;
                                $awayTeamFeederId = $team2FeederId;
                            }

                            $match->setHomeTeam($teamDAO->getRepository()->findOneByFeederId($homeTeamFeederId));
                            $match->setAwayTeam($teamDAO->getRepository()->findOneByFeederId($awayTeamFeederId));

                            if ($match->getHomeTeam() == null || $match->getAwayTeam() == null) {
                                $missedFeederId = $match->getHomeTeam() == null ? $homeTeamFeederId : $awayTeamFeederId;
                                LogManager::getInstance($this->getServiceLocator())->logOptaWarning(sprintf(MessagesConstants::WARNING_TEAM_MISSED, $missedFeederId));
                            } else {
//                                if ($isJustFinishedMatch) {
//
//                                }
                                $matchDAO->save($match);
                            }

//                            if (!$isNonCalculatedMatch)
                                $matchDAO->detach($match);
//                            else
//                                $nonCalculatedMatches[] = $match;

                        } catch (\Exception $e) {
                            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e);
                        }

                        $this->doProgress($console);

                    }
                }
            }

            $this->finishProgress($console, true, 'F1');

//            if (!empty($nonCalculatedMatch)) {
//                $this->startProgress($console, false);
//                $scoringManager = ScoringManager::getInstance($this->getServiceLocator());
//                $this->setProgressLength(count($nonCalculatedMatches));
//                foreach($nonCalculatedMatches as $nonCalculatedMatch) {
//                    $scoringManager->calculateMatchScores($nonCalculatedMatch);
//                    $matchDAO->detach($nonCalculatedMatch);
//                    $this->doProgress($console);
//                }
//                $this->finishProgress($console, false);
//            }

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e);
        }
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param \Zend\Console\Adapter\AdapterInterface $console
     */
    public function parseF7Feed($xml, $console) {

        try {

            $match = null;

            $this->startProgress($console, true, 'F7');

            $matchDAO = MatchDAO::getInstance($this->getServiceLocator());
            $matchFeederId = $this->getIdFromString($this->getXmlAttribute($xml->SoccerDocument, 'uID'));
            $type = $this->getXmlAttribute($xml->SoccerDocument, 'Type');

            if ($type == 'Result' && ($match = $matchDAO->getRepository()->findOneByFeederId($matchFeederId)) != null &&
                $match->getStatus() != Match::FULL_TIME_STATUS) {

                $result = $this->getXmlAttribute($xml->SoccerDocument->MatchData->MatchInfo->Result, 'Type');

                $teamData1 = $xml->SoccerDocument->MatchData->TeamData->{0};
                $teamData2 = $xml->SoccerDocument->MatchData->TeamData->{1};

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
                $homeTeam = $teamDAO->getRepository()->findOneByFeederId($homeTeamFeederId);
                $awayTeamFeederId = $this->getIdFromString($this->getXmlAttribute($awayTeamData, 'TeamRef'));
                $awayTeam = $teamDAO->getRepository()->findOneByFeederId($awayTeamFeederId);

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
                            $players[$playerFeederId] = $playerDAO->getRepository()->findOneByFeederId($playerFeederId);
                        $matchGoal->setPlayer($players[$playerFeederId]);
                        $timestamp = $this->getXmlAttribute($goalData, 'TimeStamp');
                        $matchGoal->setTime(new \DateTime($timestamp));
                        $matchGoal->setOrder(++$order);
                        $match->addMatchGoal($matchGoal);
                    }
                }

                $match->setStatus(Match::FULL_TIME_STATUS);

                $matchDAO->save($match);

            }

            $this->finishProgress($console, true, 'F7');

            if ($match != null)
                ScoringManager::getInstance($this->getServiceLocator())->calculateMatchScores($match);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleOptaException($e);
        }
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
        else if ($format != null)
            $xmlValue = \DateTime::createFromFormat($format, $xmlValue, $tz);
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

    const LOG_FEED_IMPORT_STARTED = 'Opta Feed %s Import Started';
    const LOG_FEED_IMPORT_FINISHED = 'Opta Feed %s Import Finished';

    const LOG_FEED_SCORING_CALCULATION_STARTED = 'Scoring Calculation Started';
    const LOG_FEED_SCORING_CALCULATION_FINISHED = 'Scoring Calculation Finished';

    /**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @param bool $isFeed
     * @param string $feedType
     */
    private function startProgress($console, $isFeed, $feedType = null) {
        $info = $isFeed ? sprintf(self::LOG_FEED_IMPORT_STARTED, $feedType) : self::LOG_FEED_SCORING_CALCULATION_STARTED;
        LogManager::getInstance($this->getServiceLocator())->logOptaInfo($info);
        $console->writeLine("");
        $console->writeLine($info);
        $console->writeLine("");
    }

    /**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @param bool $isFeed
     * @param string $feedType
     */
    private function finishProgress($console, $isFeed, $feedType = null) {
        $info = $isFeed ? sprintf(self::LOG_FEED_IMPORT_FINISHED, $feedType) : self::LOG_FEED_SCORING_CALCULATION_FINISHED;
        LogManager::getInstance($this->getServiceLocator())->logOptaInfo($info);
        $console->writeLine("");
        $console->writeLine($info);
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
        echo "\r";
        $percentage = (int)((++$this->progressCounter / $this->progressLength) * 100);
        if ($percentage < 100)
            $console->writeAt($percentage . "%", 0, 0);
        else
            $console->writeLine($percentage . "%");
    }

}
