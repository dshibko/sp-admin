<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Season
 *
 * @ORM\Table(name="season")
 * @ORM\Entity
 */
class Season extends BasicObject {

    /**
     * @var integer
     *
     * @ORM\Column(name="feeder_id", type="integer", nullable=false)
     */
    private $feederId;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=100, nullable=false)
     */
    private $displayName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=false)
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="League", mappedBy="season", cascade={"remove"})
     */
    private $leagues;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->leagues = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Season
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    
        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Season
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Season
     */
    public function setEndDate($endTime)
    {
        $this->endDate = $endTime;
    
        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add leagues
     *
     * @param League $leagues
     * @return Season
     */
    public function addLeague(League $leagues)
    {
        $this->leagues[] = $leagues;
    
        return $this;
    }

    /**
     * Remove leagues
     *
     * @param League $leagues
     */
    public function removeLeague(League $leagues)
    {
        $this->leagues->removeElement($leagues);
    }

    /**
     * Get leagues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLeagues()
    {
        return $this->leagues;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Competition", mappedBy="season", cascade={"remove"})
     */
    private $competitions;

    /**
     * Add competitions
     *
     * @param Competition $competitions
     * @return Season
     */
    public function addCompetition(Competition $competitions)
    {
        $this->competitions[] = $competitions;

        return $this;
    }

    /**
     * Remove competitions
     *
     * @param Competition $competitions
     */
    public function removeCompetition(Competition $competitions)
    {
        $this->competitions->removeElement($competitions);
    }

    /**
     * Get competitions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompetitions()
    {
        return $this->competitions;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SeasonRegion", mappedBy="season", cascade={"remove"})
     */
    private $seasonRegions;


    /**
     * Get seasonRegions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeasonRegions()
    {
        return $this->seasonRegions;
    }

    /**
     * Add seasonRegions
     *
     * @param SeasonRegion $seasonRegions
     * @return Season
     */
    public function addSeasonRegion(SeasonRegion $seasonRegions)
    {
        $this->seasonRegions[] = $seasonRegions;

        return $this;
    }

    /**
     * Remove seasonRegions
     *
     * @param SeasonRegion $seasonRegions
     */
    public function removeSeasonRegion(SeasonRegion $seasonRegions)
    {
        $this->seasonRegions->removeElement($seasonRegions);
    }

    private $seasonRegionsByRegion = array();

    public function getSeasonRegionByRegionId($id)
    {
        if (!array_key_exists($id, $this->seasonRegionsByRegion))
            foreach ($this->getSeasonRegions() as $seasonRegion)
                if ($seasonRegion->getRegion()->getId() == $id) {
                    $this->seasonRegionsByRegion[$id] = $seasonRegion;
                    break;
                }
        return $this->seasonRegionsByRegion[$id];
    }

    private $regionalLeagueByRegion = array();

    /**
     * @param $id
     * @return \Application\Model\Entities\League
     */
    public function getRegionalLeagueByRegionId($id)
    {
        if (!array_key_exists($id, $this->regionalLeagueByRegion))
            foreach ($this->getLeagues() as $league)
                if (!$league->getIsGlobal() && !$league->getIsPrivate() && $league->getRegion() != null &&
                    $league->getRegion() instanceof Region && $league->getRegion()->getId() == $id) {
                    $this->regionalLeagueByRegion[$id] = $league;
                    break;
                }
        return $this->regionalLeagueByRegion[$id];
    }

    private $globalLeague;

    public function getGlobalLeague() {
        if ($this->globalLeague == null) {
            $leagues = $this->getLeagues();
            foreach ($leagues as $league)
                if ($league->getIsGlobal()) {
                    $this->globalLeague = $league;
                    break;
                }
        }
        return $this->globalLeague;
    }

    /**
     * @param integer $feederId
     */
    public function setFeederId($feederId)
    {
        $this->feederId = $feederId;
    }

    /**
     * @return integer
     */
    public function getFeederId()
    {
        return $this->feederId;
    }

    public function getCompetitionByFeederId($feederId) {
        $competitions = $this->getCompetitions()->filter(function(Competition $competition) use ($feederId) {
            return $competition->getFeederId() == $feederId;
        });
        return ($competitions->count() > 0) ? $competitions->first() : null;
    }

}
