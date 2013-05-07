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
     * @ORM\Column(name="home_team_score", type="boolean", nullable=false)
     */
    private $homeTeamScore;

    /**
     * @var boolean
     *
     * @ORM\Column(name="away_team_score", type="boolean", nullable=false)
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
     * @ORM\OneToMany(targetEntity="PredictionPlayer", mappedBy="prediction")
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
    }
    
    /**
     * Set homeTeamScore
     *
     * @param boolean $homeTeamScore
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
     * @return boolean 
     */
    public function getHomeTeamScore()
    {
        return $this->homeTeamScore;
    }

    /**
     * Set awayTeamScore
     *
     * @param boolean $awayTeamScore
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
     * @return boolean 
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
}
