<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * SeasonRegion
 *
 * @ORM\Table(name="league_user")
 * @ORM\Entity
 */
class LeagueUser
{
    function __construct()
    {
        $this->correctScorers = $this->correctScores = $this->correctResults = $this->correctScorersOrder = $this->predictionsCount = $this->predictionsPlayersCount = 0;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    /**
     * @var integer
     *
     * @ORM\Column(name="accuracy", type="integer")
     */
    private $accuracy;

    /**
     * @var integer
     *
     * @ORM\Column(name="correct_results", type="integer")
     */
    private $correctResults;

    /**
     * @var integer
     *
     * @ORM\Column(name="correct_scores", type="integer")
     */
    private $correctScores;

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
     * @ORM\Column(name="predictions_players_count", type="integer")
     */
    private $predictionsPlayersCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="predictions_count", type="integer")
     */
    private $predictionsCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="previous_place", type="integer")
     */
    private $previousPlace;

    /**
     * @var integer
     *
     * @ORM\Column(name="place", type="integer")
     */
    private $place;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var League
     *
     * @ORM\ManyToOne(targetEntity="League")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     * })
     */
    private $league;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="LeagueUserPlace", mappedBy="leagueUser", cascade={"persist", "remove"})
     */
    private $leagueUserPlaces;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registration_date", type="datetime", nullable=false)
     */
    private $registrationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="join_date", type="datetime", nullable=false)
     */
    private $joinDate;


    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $joinDate
     */
    public function setJoinDate($joinDate)
    {
        $this->joinDate = $joinDate;
    }

    /**
     * @return \DateTime
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * @param \Application\Model\Entities\League $league
     */
    public function setLeague($league)
    {
        $this->league = $league;
    }

    /**
     * @return \Application\Model\Entities\League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * @param \Application\Model\Entities\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Application\Model\Entities\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $accuracy
     */
    public function setAccuracy($accuracy)
    {
        $this->accuracy = $accuracy;
    }

    /**
     * @return int
     */
    public function getAccuracy()
    {
        return $this->accuracy;
    }

    /**
     * @param int $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return int
     */
    public function getPlace()
    {
        return $this->place;
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
     * @param int $previousPlace
     */
    public function setPreviousPlace($previousPlace)
    {
        $this->previousPlace = $previousPlace;
    }

    /**
     * @return int
     */
    public function getPreviousPlace()
    {
        return $this->previousPlace;
    }

    /**
     * @param int $correctResults
     */
    public function setCorrectResults($correctResults)
    {
        $this->correctResults = $correctResults;
    }

    /**
     * @return int
     */
    public function getCorrectResults()
    {
        return $this->correctResults;
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
     * @param int $correctScores
     */
    public function setCorrectScores($correctScores)
    {
        $this->correctScores = $correctScores;
    }

    /**
     * @return int
     */
    public function getCorrectScores()
    {
        return $this->correctScores;
    }

    /**
     * @param int $predictionsCount
     */
    public function setPredictionsCount($predictionsCount)
    {
        $this->predictionsCount = $predictionsCount;
    }

    /**
     * @return int
     */
    public function getPredictionsCount()
    {
        return $this->predictionsCount;
    }

    /**
     * @param int $predictionsPlayersCount
     */
    public function setPredictionsPlayersCount($predictionsPlayersCount)
    {
        $this->predictionsPlayersCount = $predictionsPlayersCount;
    }

    /**
     * @return int
     */
    public function getPredictionsPlayersCount()
    {
        return $this->predictionsPlayersCount;
    }

    /**
     * @param \DateTime $registrationDate
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $leagueUserPlaces
     */
    public function setLeagueUserPlaces($leagueUserPlaces)
    {
        $this->leagueUserPlaces = $leagueUserPlaces;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLeagueUserPlaces()
    {
        return $this->leagueUserPlaces;
    }

}
