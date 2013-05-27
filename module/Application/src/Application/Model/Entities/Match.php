<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Match
 *
 * @ORM\Table(name="`match`")
 * @ORM\Entity
 */
class Match extends BasicObject {

    const PRE_MATCH_STATUS = 'PreMatch';
    const LIVE_STATUS = 'Live';
    const FULL_TIME_STATUS = 'FullTime';
    const POSTPONED_STATUS = 'Postponed';
    const ABANDONED_STATUS = 'Abandoned';

    public static function getAvailableStatuses() {
        return array(self::PRE_MATCH_STATUS, self::LIVE_STATUS, self::FULL_TIME_STATUS, self::POSTPONED_STATUS, self::ABANDONED_STATUS);
    }

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", columnDefinition="ENUM('PreMatch', 'Live', 'FullTime', 'Postponed', 'Abandoned')")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="stadium_name", type="string", length=100)
     */
    private $stadiumName;

    /**
     * @var string
     *
     * @ORM\Column(name="city_name", type="string", length=100)
     */
    private $cityName;

    /**
     * @var integer
     *
     * @ORM\Column(name="feeder_id", type="integer", nullable=false)
     */
    private $feederId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime")
     */
    private $startTime;

    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string")
     */
    private $timezone;

    /**
     * @var integer
     *
     * @ORM\Column(name="week", type="integer")
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
     * @var boolean
     *
     * @ORM\Column(name="is_double_points", type="boolean")
     */
    private $isDoublePoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="home_team_full_time_score", type="integer")
     */
    private $homeTeamFullTimeScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="away_team_full_time_score", type="integer")
     */
    private $awayTeamFullTimeScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="home_team_extra_time_score", type="integer")
     */
    private $homeTeamExtraTimeScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="away_team_extra_time_score", type="integer")
     */
    private $awayTeamExtraTimeScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="home_team_shootout_score", type="integer")
     */
    private $homeTeamShootoutScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="away_team_shootout_score", type="integer")
     */
    private $awayTeamShootoutScore;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="MatchGoal", mappedBy="match", cascade={"persist", "remove"})
     */
    private $matchGoals;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->predictions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matchGoals = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isDoublePoints = false;
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

    /**
     * @param int $feederId
     */
    public function setFeederId($feederId)
    {
        $this->feederId = $feederId;
    }

    /**
     * @return int
     */
    public function getFeederId()
    {
        return $this->feederId;
    }

    /**
     * @param string $cityName
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;
    }

    /**
     * @return string
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * @param string $stadiumName
     */
    public function setStadiumName($stadiumName)
    {
        $this->stadiumName = $stadiumName;
    }

    /**
     * @return string
     */
    public function getStadiumName()
    {
        return $this->stadiumName;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $isDoublePoints
     */
    public function setIsDoublePoints($isDoublePoints)
    {
        $this->isDoublePoints = $isDoublePoints;
    }

    /**
     * @return boolean
     */
    public function getIsDoublePoints()
    {
        return $this->isDoublePoints;
    }

    /**
     * @param int $awayTeamExtraTimeScore
     */
    public function setAwayTeamExtraTimeScore($awayTeamExtraTimeScore)
    {
        $this->awayTeamExtraTimeScore = $awayTeamExtraTimeScore;
    }

    /**
     * @return int
     */
    public function getAwayTeamExtraTimeScore()
    {
        return $this->awayTeamExtraTimeScore;
    }

    /**
     * @param int $awayTeamFullTimeScore
     */
    public function setAwayTeamFullTimeScore($awayTeamFullTimeScore)
    {
        $this->awayTeamFullTimeScore = $awayTeamFullTimeScore;
    }

    /**
     * @return int
     */
    public function getAwayTeamFullTimeScore()
    {
        return $this->awayTeamFullTimeScore;
    }

    /**
     * @param int $awayTeamShootoutScore
     */
    public function setAwayTeamShootoutScore($awayTeamShootoutScore)
    {
        $this->awayTeamShootoutScore = $awayTeamShootoutScore;
    }

    /**
     * @return int
     */
    public function getAwayTeamShootoutScore()
    {
        return $this->awayTeamShootoutScore;
    }

    /**
     * @param int $homeTeamExtraTimeScore
     */
    public function setHomeTeamExtraTimeScore($homeTeamExtraTimeScore)
    {
        $this->homeTeamExtraTimeScore = $homeTeamExtraTimeScore;
    }

    /**
     * @return int
     */
    public function getHomeTeamExtraTimeScore()
    {
        return $this->homeTeamExtraTimeScore;
    }

    /**
     * @param int $homeTeamFullTimeScore
     */
    public function setHomeTeamFullTimeScore($homeTeamFullTimeScore)
    {
        $this->homeTeamFullTimeScore = $homeTeamFullTimeScore;
    }

    /**
     * @return int
     */
    public function getHomeTeamFullTimeScore()
    {
        return $this->homeTeamFullTimeScore;
    }

    /**
     * @param int $homeTeamShootoutScore
     */
    public function setHomeTeamShootoutScore($homeTeamShootoutScore)
    {
        $this->homeTeamShootoutScore = $homeTeamShootoutScore;
    }

    /**
     * @return int
     */
    public function getHomeTeamShootoutScore()
    {
        return $this->homeTeamShootoutScore;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $matchGoals
     */
    public function setMatchGoals($matchGoals)
    {
        $this->matchGoals = $matchGoals;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchGoals()
    {
        return $this->matchGoals;
    }

    /**
     * Add matchGoal
     *
     * @param MatchGoal $matchGoal
     * @return Match
     */
    public function addMatchGoal(MatchGoal $matchGoal)
    {
        $this->matchGoals[] = $matchGoal;

        return $this;
    }

    /**
     * Remove matchGoal
     *
     * @param MatchGoal $matchGoal
     */
    public function removeHomeMatch(MatchGoal $matchGoal)
    {
        $this->matchGoals->removeElement($matchGoal);
    }

    /**
     * @param string $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone ? $this->timezone : 'UTC';
    }

}
