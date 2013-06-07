<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeaturedGoalkeeper
 *
 * @ORM\Table(name="featured_goalkeeper")
 * @ORM\Entity
 */
class FeaturedGoalkeeper
{
    /**
     * @var integer
     *
     * @ORM\Column(name="saves", type="integer", length=6, nullable=true)
     */
    private $saves;

    /**
     * @var integer
     *
     * @ORM\Column(name="matches_played", type="integer", length=3, nullable=true)
     */
    private $matchesPlayed;

    /**
     * @var integer
     *
     * @ORM\Column(name="penalty_saves", type="integer", length=6, nullable=true)
     */
    private $penaltySaves;

    /**
     * @var integer
     *
     * @ORM\Column(name="clean_sheets", type="integer", length=3, nullable=true)
     */
    private $cleanSheets;

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
     * @param int $matchesPlayed
     * @return \Application\Model\Entities\FeaturedGoalkeeper
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
     * @param \Application\Model\Entities\Player $player
     * @return \Application\Model\Entities\FeaturedGoalkeeper
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

    /**
     * @param int $cleanSheets
     * @return \Application\Model\Entities\FeaturedGoalkeeper
     */
    public function setCleanSheets($cleanSheets)
    {
        $this->cleanSheets = $cleanSheets;
        return $this;
    }

    /**
     * @return int
     */
    public function getCleanSheets()
    {
        return $this->cleanSheets;
    }

    /**
     * @param int $penaltySaves
     * @return \Application\Model\Entities\FeaturedGoalkeeper
     */
    public function setPenaltySaves($penaltySaves)
    {
        $this->penaltySaves = $penaltySaves;
        return $this;
    }

    /**
     * @return int
     */
    public function getPenaltySaves()
    {
        return $this->penaltySaves;
    }

    /**
     * @param int $saves
     * @return \Application\Model\Entities\FeaturedGoalkeeper
     */
    public function setSaves($saves)
    {
        $this->saves = $saves;
        return $this;
    }

    /**
     * @return int
     */
    public function getSaves()
    {
        return $this->saves;
    }
}
