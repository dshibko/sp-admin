<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Match
 *
 * @ORM\Table(name="match")
 * @ORM\Entity
 */
class Match extends BasicObject {

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=false)
     */
    private $startTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="week", type="integer", nullable=false)
     */
    private $week;

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
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="match")
     */
    private $predictions;

    /**
     * @var Prediction
     *
     * @ORM\ManyToOne(targetEntity="Prediction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="featured_prediction_id", referencedColumnName="id")
     * })
     */
    private $featuredPrediction;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="away_team_id", referencedColumnName="id")
     * })
     */
    private $awayTeam;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="home_team_id", referencedColumnName="id")
     * })
     */
    private $homeTeam;

    /**
     * @var Competition
     *
     * @ORM\ManyToOne(targetEntity="Competition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     * })
     */
    private $competition;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="featured_player_id", referencedColumnName="id")
     * })
     */
    private $featuredPlayer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->predictions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Match
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    
        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set week
     *
     * @param integer $week
     * @return Match
     */
    public function setWeek($week)
    {
        $this->week = $week;
    
        return $this;
    }

    /**
     * Get week
     *
     * @return integer 
     */
    public function getWeek()
    {
        return $this->week;
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
     * Add predictions
     *
     * @param Prediction $predictions
     * @return Match
     */
    public function addPrediction(Prediction $predictions)
    {
        $this->predictions[] = $predictions;
    
        return $this;
    }

    /**
     * Remove predictions
     *
     * @param Prediction $predictions
     */
    public function removePrediction(Prediction $predictions)
    {
        $this->predictions->removeElement($predictions);
    }

    /**
     * Get predictions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPredictions()
    {
        return $this->predictions;
    }

    /**
     * Set featuredPrediction
     *
     * @param Prediction $featuredPrediction
     * @return Match
     */
    public function setFeaturedPrediction(Prediction $featuredPrediction = null)
    {
        $this->featuredPrediction = $featuredPrediction;
    
        return $this;
    }

    /**
     * Get featuredPrediction
     *
     * @return Prediction
     */
    public function getFeaturedPrediction()
    {
        return $this->featuredPrediction;
    }

    /**
     * Set awayTeam
     *
     * @param Team $awayTeam
     * @return Match
     */
    public function setAwayTeam(Team $awayTeam = null)
    {
        $this->awayTeam = $awayTeam;
    
        return $this;
    }

    /**
     * Get awayTeam
     *
     * @return Team
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Set homeTeam
     *
     * @param Team $homeTeam
     * @return Match
     */
    public function setHomeTeam(Team $homeTeam = null)
    {
        $this->homeTeam = $homeTeam;
    
        return $this;
    }

    /**
     * Get homeTeam
     *
     * @return Team
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Set competition
     *
     * @param Competition $competition
     * @return Match
     */
    public function setCompetition(Competition $competition = null)
    {
        $this->competition = $competition;
    
        return $this;
    }

    /**
     * Get competition
     *
     * @return Competition
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * Set featuredPlayer
     *
     * @param Player $featuredPlayer
     * @return Match
     */
    public function setFeaturedPlayer(Player $featuredPlayer = null)
    {
        $this->featuredPlayer = $featuredPlayer;
    
        return $this;
    }

    /**
     * Get featuredPlayer
     *
     * @return Player
     */
    public function getFeaturedPlayer()
    {
        return $this->featuredPlayer;
    }
}
