<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Prediction
 *
 * @ORM\Table(name="prediction")
 * @ORM\Entity
 */
class Prediction extends BasicObject {

    /**
     * @var boolean
     *
     * @ORM\Column(name="was_viewed", type="boolean")
     */
    private $wasViewed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_correct_result", type="boolean")
     */
    private $isCorrectResult;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_correct_score", type="boolean")
     */
    private $isCorrectScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="correct_scorers", type="integer")
     */
    private $correctScorers;

    /**
     * @var integer
     *
     * @ORM\Column(name="correct_scorers_order", type="integer")
     */
    private $correctScorersOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    /**
     * @var integer
     *
     * @ORM\Column(name="home_team_score", type="integer", nullable=false)
     */
    private $homeTeamScore;

    /**
     * @var integer
     *
     * @ORM\Column(name="away_team_score", type="integer", nullable=false)
     */
    private $awayTeamScore;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_date", type="datetime", nullable=false)
     */
    private $lastUpdateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

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
     * @ORM\OneToMany(targetEntity="PredictionPlayer", mappedBy="prediction", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $predictionPlayers;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var Match
     *
     * @ORM\ManyToOne(targetEntity="Match")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * })
     */
    private $match;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->predictionPlayers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->wasViewed = false;
    }
    
    /**
     * Set homeTeamScore
     *
     * @param integer $homeTeamScore
     * @return Prediction
     */
    public function setHomeTeamScore($homeTeamScore)
    {
        $this->homeTeamScore = $homeTeamScore;
    
        return $this;
    }

    /**
     * Get homeTeamScore
     *
     * @return integer
     */
    public function getHomeTeamScore()
    {
        return $this->homeTeamScore;
    }

    /**
     * Set awayTeamScore
     *
     * @param integer $awayTeamScore
     * @return Prediction
     */
    public function setAwayTeamScore($awayTeamScore)
    {
        $this->awayTeamScore = $awayTeamScore;
    
        return $this;
    }

    /**
     * Get awayTeamScore
     *
     * @return integer
     */
    public function getAwayTeamScore()
    {
        return $this->awayTeamScore;
    }

    /**
     * Set lastUpdateDate
     *
     * @param \DateTime $lastUpdateDate
     * @return Prediction
     */
    public function setLastUpdateDate($lastUpdateDate)
    {
        $this->lastUpdateDate = $lastUpdateDate;
    
        return $this;
    }

    /**
     * Get lastUpdateDate
     *
     * @return \DateTime 
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Prediction
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
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
     * Add predictionPlayers
     *
     * @param PredictionPlayer $predictionPlayers
     * @return Prediction
     */
    public function addPredictionPlayer(PredictionPlayer $predictionPlayers)
    {
        $this->predictionPlayers[] = $predictionPlayers;
    
        return $this;
    }

    /**
     * Remove predictionPlayers
     *
     * @param PredictionPlayer $predictionPlayers
     */
    public function removePredictionPlayer(PredictionPlayer $predictionPlayers)
    {
        $this->predictionPlayers->removeElement($predictionPlayers);
    }

    /**
     * Clear predictionPlayers
     */
    public function clearPredictionPlayers()
    {
        $this->predictionPlayers->clear();
    }

    /**
     * Get predictionPlayers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPredictionPlayers()
    {
        return $this->predictionPlayers;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Prediction
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set match
     *
     * @param Match $match
     * @return Prediction
     */
    public function setMatch(Match $match = null)
    {
        $this->match = $match;
    
        return $this;
    }

    /**
     * Get match
     *
     * @return Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param boolean $isCorrectResult
     */
    public function setIsCorrectResult($isCorrectResult)
    {
        $this->isCorrectResult = $isCorrectResult;
    }

    /**
     * @return boolean
     */
    public function getIsCorrectResult()
    {
        return $this->isCorrectResult;
    }

    /**
     * @param boolean $isCorrectScore
     */
    public function setIsCorrectScore($isCorrectScore)
    {
        $this->isCorrectScore = $isCorrectScore;
    }

    /**
     * @return boolean
     */
    public function getIsCorrectScore()
    {
        return $this->isCorrectScore;
    }

    /**
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $correctScorers
     */
    public function setCorrectScorers($correctScorers)
    {
        $this->correctScorers = $correctScorers;
    }

    /**
     * @return int
     */
    public function getCorrectScorers()
    {
        return $this->correctScorers;
    }

    /**
     * @param int $correctScorersOrder
     */
    public function setCorrectScorersOrder($correctScorersOrder)
    {
        $this->correctScorersOrder = $correctScorersOrder;
    }

    /**
     * @return int
     */
    public function getCorrectScorersOrder()
    {
        return $this->correctScorersOrder;
    }

    /**
     * @param boolean $wasViewed
     */
    public function setWasViewed($wasViewed)
    {
        $this->wasViewed = $wasViewed;
    }

    /**
     * @return boolean
     */
    public function getWasViewed()
    {
        return $this->wasViewed;
    }

}
