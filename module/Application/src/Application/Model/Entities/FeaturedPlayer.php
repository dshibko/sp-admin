<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeaturedPlayer
 *
 * @ORM\Table(name="featured_player")
 * @ORM\Entity
 */
class FeaturedPlayer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="goals", type="integer", length=3, nullable=true)
     */
    private $goals;

    /**
     * @var integer
     *
     * @ORM\Column(name="matches_played", type="integer", length=3, nullable=true)
     */
    private $matchesPlayed;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_of_assists", type="integer", length=3, nullable=true)
     */
    private $numberOfAssists;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_of_shots", type="integer", length=6, nullable=true)
     */
    private $numberOfShots;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     * })
     */
    private $player;

    /**
     * @param int $goals
     * @return \Application\Model\Entities\FeaturedPlayer
     */
    public function setGoals($goals)
    {
        $this->goals = $goals;
        return $this;
    }

    /**
     * @return int
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * @param int $id
     * @return \Application\Model\Entities\FeaturedPlayer
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $numberOfAssists
     * @return \Application\Model\Entities\FeaturedPlayer
     */
    public function setNumberOfAssists($numberOfAssists)
    {
        $this->numberOfAssists = $numberOfAssists;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfAssists()
    {
        return $this->numberOfAssists;
    }

    /**
     * @param int $matchesPlayed
     * @return \Application\Model\Entities\FeaturedPlayer
     */
    public function setMatchesPlayed($matchesPlayed)
    {
        $this->matchesPlayed = $matchesPlayed;
        return $this;
    }

    /**
     * @return int
     */
    public function getMatchesPlayed()
    {
        return $this->matchesPlayed;
    }

    /**
     * @param int $numberOfShots
     * @return \Application\Model\Entities\FeaturedPlayer
     */
    public function setNumberOfShots($numberOfShots)
    {
        $this->numberOfShots = $numberOfShots;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfShots()
    {
        return $this->numberOfShots;
    }

    /**
     * @param \Application\Model\Entities\Player $player
     * @return \Application\Model\Entities\FeaturedPlayer
     */
    public function setPlayer($player)
    {
        $this->player = $player;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
}