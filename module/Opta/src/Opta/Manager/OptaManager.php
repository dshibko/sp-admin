<?php

namespace Opta\Manager;

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

        $this->startProgress($console, 'F40');

        $seasonDAO = SeasonDAO::getInstance($this->getServiceLocator());
        $seasonFeederId = $this->getXmlAttribute($xml->SoccerDocument, 'season_id');
        $season = $seasonDAO->getRepository()->findOneByFeederId($seasonFeederId);

        if ($season != null) {

            $competitionDAO = CompetitionDAO::getInstance($this->getServiceLocator());
            $competitionFeederId = $this->getXmlAttribute($xml->SoccerDocument, 'competition_id');
            $competition = $competitionDAO->getRepository()->findOneByFeederId($competitionFeederId);
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
                $teamFeederId = $this->getXmlAttribute($teamXml, 'uID');
                $teamFeederId = preg_replace("/([a-z]*)/", "", $teamFeederId);
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
                    $playerFeederId = $this->getXmlAttribute($playerXml, 'uID');
                    $playerFeederId = preg_replace("/([a-z]*)/", "", $playerFeederId);
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

                $this->doProgress($console);

            }
        }

        $this->finishProgress($console, 'F40');

    }

    /**
     * @param \SimpleXMLElement $xmlObj
     * @param $index
     * @param string|null $format
     * @return \DateTime|string|null
     */
    private function getNodeValue(\SimpleXMLElement $xmlObj, $index = -1, $format = null) {

        $xmlNode = $index != -1 ? $xmlObj->{$index} : $xmlObj;
        $xmlValue = $xmlNode != null ? $xmlNode->__toString() : null;
        if (empty($xmlValue) || $xmlValue == 'Unknown')
            return null;
        else if ($format != null)
            $xmlValue = \DateTime::createFromFormat($format, $xmlValue);
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

    /**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @param string $feedType
     */
    private function startProgress($console, $feedType) {
        $console->writeLine("");
        $console->writeLine(sprintf(self::LOG_FEED_IMPORT_STARTED, $feedType));
        $console->writeLine("");
    }

    /**
     * @param \Zend\Console\Adapter\AdapterInterface $console
     * @param string $feedType
     */
    private function finishProgress($console, $feedType) {
        $console->writeLine("");
        $console->writeLine(sprintf(self::LOG_FEED_IMPORT_FINISHED, $feedType));
        $console->writeLine("");
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
