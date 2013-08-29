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

    /**
     * @static
     * @return array
     */
    public static function getAvailableStatuses() {
        return array(self::PRE_MATCH_STATUS, self::LIVE_STATUS, self::FULL_TIME_STATUS, self::POSTPONED_STATUS, self::ABANDONED_STATUS);
    }

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", columnDefinition="ENUM('PreMatch', 'Live', 'FullTime', 'Postponed', 'Abandoned')")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="stadium_name", type="string", length=100)
     */
    protected $stadiumName;

    /**
     * @var string
     *
     * @ORM\Column(name="city_name", type="string", length=100)
     */
    protected $cityName;

    /**
     * @var integer
     *
     * @ORM\Column(name="feeder_id", type="integer", nullable=false)
     */
    protected $feederId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime")
     */
    protected $startTime;

    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string")
     */
    protected $timezone;

    /**
     * @var integer
     *
     * @ORM\Column(name="week", type="integer")
     */
    protected $week;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="match")
     */
    protected $predictions;

    /**
     * @var Prediction
     *
     * @ORM\ManyToOne(targetEntity="Prediction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="featured_prediction_id", referencedColumnName="id")
     * })
     */
    protected $featuredPrediction;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="away_team_id", referencedColumnName="id")
     * })
     */
    protected $awayTeam;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="home_team_id", referencedColumnName="id")
     * })
     */
    protected $homeTeam;

    /**
     * @var CompetitionSeason
     *
     * @ORM\ManyToOne(targetEntity="CompetitionSeason")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="competition_season_id", referencedColumnName="id")
     * })
     */
    protected $competitionSeason;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="featured_player_id", referencedColumnName="id")
     * })
     */
    protected $featuredPlayer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_double_points", type="boolean")
     */
    protected $isDoublePoints;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_line_up", type="boolean")
     */
    protected $hasLineUp = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="home_team_full_time_score", type="integer")
     */
    protected $homeTeamFullTimeScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="away_team_full_time_score", type="integer")
     */
    protected $awayTeamFullTimeScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="home_team_extra_time_score", type="integer")
     */
    protected $homeTeamExtraTimeScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="away_team_extra_time_score", type="integer")
     */
    protected $awayTeamExtraTimeScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="home_team_shootout_score", type="integer")
     */
    protected $homeTeamShootoutScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="away_team_shootout_score", type="integer")
     */
    protected $awayTeamShootoutScore;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="MatchGoal", mappedBy="match", cascade={"persist", "remove"})
     */
    protected $matchGoals;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_blocked", type="boolean")
     */
    protected $isBlocked = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="MatchLanguage", mappedBy="match", cascade={"remove"})
     */
    protected $matchLanguages;

    /**
     * @var PreMatchReportHeadToHead
     *
     * @ORM\OneToOne(targetEntity="PreMatchReportHeadToHead", mappedBy="match", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $preMatchReportHeadToHead;

    /**
     * @var PreMatchReportGoalsScored
     *
     * @ORM\OneToOne(targetEntity="PreMatchReportGoalsScored", mappedBy="match", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $preMatchReportGoalsScored;

    /**
     * @var PreMatchReportAvgGoalsScored
     *
     * @ORM\OneToOne(targetEntity="PreMatchReportAvgGoalsScored", mappedBy="match", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $preMatchReportAvgGoalsScored;

    /**
     * @var PreMatchReportFormGuide
     *
     * @ORM\OneToOne(targetEntity="PreMatchReportFormGuide", mappedBy="match", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $preMatchReportFormGuide;

    /**
     * @var PreMatchReportLastSeasonMatch
     *
     * @ORM\OneToOne(targetEntity="PreMatchReportLastSeasonMatch", mappedBy="match", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $preMatchReportLastSeasonMatch;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="PreMatchReportMostRecentScorer", mappedBy="match", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $preMatchReportMostRecentScorers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="PreMatchReportTopScorer", mappedBy="match", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $preMatchReportTopScorers;

    /**
     * Get match report languages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchLanguages()
    {
        return $this->matchLanguages;
    }


    /**
     * @param MatchLanguage $matchLanguage
     * @return \Application\Model\Entities\Match
     */
    public function addMatchReportLanguage(MatchLanguage $matchLanguage)
    {
        $this->matchLanguages[] = $matchLanguage;
        return $this;
    }


    /**
     * @param MatchLanguage $matchLanguage
     * @return Match
     */
    public function removeMatchLanguage(MatchLanguage $matchLanguage)
    {
        $this->matchLanguages->removeElement($matchLanguage);
        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->predictions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matchGoals = new \Doctrine\Common\Collections\ArrayCollection();
        $this->matchLanguages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isDoublePoints = false;
        $this->preMatchReportMostRecentScorers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preMatchReportTopScorers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Match
     */
    public function setStartTime($startTime)
    {
        if (!empty($startTime))
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
        if (!empty($week))
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
     * Set competitionSeason
     *
     * @param CompetitionSeason $competitionSeason
     * @return Match
     */
    public function setCompetitionSeason(CompetitionSeason $competitionSeason = null)
    {
        $this->competitionSeason = $competitionSeason;
    
        return $this;
    }

    /**
     * Get competitionSeason
     *
     * @return CompetitionSeason
     */
    public function getCompetitionSeason()
    {
        return $this->competitionSeason;
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
        if (!empty($cityName))
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
        if (!empty($stadiumName))
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
        if (!empty($status))
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
     * @param $isDoublePoints
     * @return \Application\Model\Entities\Match
     */
    public function setIsDoublePoints($isDoublePoints)
    {
        $this->isDoublePoints = $isDoublePoints;
        return $this;
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
        if ($awayTeamExtraTimeScore !== null && $awayTeamExtraTimeScore !== '')
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
        if ($awayTeamShootoutScore !== null && $awayTeamShootoutScore !== '')
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
        if ($homeTeamExtraTimeScore !== null && $homeTeamExtraTimeScore !== '')
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
        if ($homeTeamFullTimeScore !== null && $homeTeamFullTimeScore !== '')
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
        if ($homeTeamShootoutScore !== null && $homeTeamShootoutScore !== '')
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


    /**
     * @param $isBlocked
     * @return \Application\Model\Entities\Match
     */
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

    /**
     * @return bool
     */
    public function getIsLive() {
        return $this->getStatus() == self::LIVE_STATUS || ($this->getStatus() == self::PRE_MATCH_STATUS && $this->getStartTime() < new \DateTime());
    }

    /**
     * @param boolean $hasLineUp
     */
    public function setHasLineUp($hasLineUp)
    {
        $this->hasLineUp = $hasLineUp;
    }

    /**
     * @return boolean
     */
    public function getHasLineUp()
    {
        return $this->hasLineUp;
    }

    /**
     * @param \Application\Model\Entities\PreMatchReportHeadToHead $preMatchReportHeadToHead
     */
    public function setPreMatchReportHeadToHead($preMatchReportHeadToHead)
    {
        $this->preMatchReportHeadToHead = $preMatchReportHeadToHead;
    }

    /**
     * @return \Application\Model\Entities\PreMatchReportHeadToHead
     */
    public function getPreMatchReportHeadToHead()
    {
        return $this->preMatchReportHeadToHead;
    }

    /**
     * @param \Application\Model\Entities\PreMatchReportGoalsScored $preMatchReportGoalsScored
     */
    public function setPreMatchReportGoalsScored($preMatchReportGoalsScored)
    {
        $this->preMatchReportGoalsScored = $preMatchReportGoalsScored;
    }

    /**
     * @return \Application\Model\Entities\PreMatchReportGoalsScored
     */
    public function getPreMatchReportGoalsScored()
    {
        return $this->preMatchReportGoalsScored;
    }

    /**
     * @param \Application\Model\Entities\PreMatchReportFormGuide $preMatchReportFormGuide
     */
    public function setPreMatchReportFormGuide($preMatchReportFormGuide)
    {
        $this->preMatchReportFormGuide = $preMatchReportFormGuide;
    }

    /**
     * @return \Application\Model\Entities\PreMatchReportFormGuide
     */
    public function getPreMatchReportFormGuide()
    {
        return $this->preMatchReportFormGuide;
    }

    /**
     * @param \Application\Model\Entities\PreMatchReportAvgGoalsScored $preMatchReportAvgGoalsScored
     */
    public function setPreMatchReportAvgGoalsScored($preMatchReportAvgGoalsScored)
    {
        $this->preMatchReportAvgGoalsScored = $preMatchReportAvgGoalsScored;
    }

    /**
     * @return \Application\Model\Entities\PreMatchReportAvgGoalsScored
     */
    public function getPreMatchReportAvgGoalsScored()
    {
        return $this->preMatchReportAvgGoalsScored;
    }

    /**
     * @param \Application\Model\Entities\PreMatchReportLastSeasonMatch $preMatchReportLastSeasonMatch
     */
    public function setPreMatchReportLastSeasonMatch($preMatchReportLastSeasonMatch)
    {
        $this->preMatchReportLastSeasonMatch = $preMatchReportLastSeasonMatch;
    }

    /**
     * @return \Application\Model\Entities\PreMatchReportLastSeasonMatch
     */
    public function getPreMatchReportLastSeasonMatch()
    {
        return $this->preMatchReportLastSeasonMatch;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $preMatchReportMostRecentScorers
     */
    public function setPreMatchReportMostRecentScorers($preMatchReportMostRecentScorers)
    {
        $this->preMatchReportMostRecentScorers = $preMatchReportMostRecentScorers;
    }

    /**
     * @param PreMatchReportMostRecentScorer $preMatchReportMostRecentScorer
     */
    public function addPreMatchReportMostRecentScorer($preMatchReportMostRecentScorer)
    {
        $this->preMatchReportMostRecentScorers [] = $preMatchReportMostRecentScorer;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreMatchReportMostRecentScorers()
    {
        return $this->preMatchReportMostRecentScorers;
    }

    /**
     * @param Team $team
     * @param int $place
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreMatchReportMostRecentScorerByTeamAndPlace($team, $place)
    {
        return $this->getPreMatchReportMostRecentScorers()->filter(function (PreMatchReportMostRecentScorer $preMatchReportMostRecentScorer) use ($team, $place) {
            return $preMatchReportMostRecentScorer->getTeam()->getId() == $team->getId() && $preMatchReportMostRecentScorer->getPlace() == $place;
        })->first();
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $preMatchReportTopScorer
     */
    public function setPreMatchReportTopScorers($preMatchReportTopScorer)
    {
        $this->preMatchReportTopScorers = $preMatchReportTopScorer;
    }

    /**
     * @param PreMatchReportTopScorer $preMatchReportTopScorer
     */
    public function addPreMatchReportTopScorers($preMatchReportTopScorer)
    {
        $this->preMatchReportTopScorers [] = $preMatchReportTopScorer;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreMatchReportTopScorers()
    {
        return $this->preMatchReportTopScorers;
    }

    /**
     * @param Team $team
     * @param int $place
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreMatchReportTopScorerByTeamAndPlace($team, $place)
    {
        return $this->getPreMatchReportTopScorers()->filter(function (PreMatchReportTopScorer $preMatchReportTopScorer) use ($team, $place) {
            return $preMatchReportTopScorer->getTeam()->getId() == $team->getId() && $preMatchReportTopScorer->getPlace() == $place;
        })->first();
    }

}
