<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * LineUpPlayer
 *
 * @ORM\Table(name="line_up")
 * @ORM\Entity
 */
class LineUpPlayer extends BasicObject {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;

    /**
     * @var Match
     *
     * @ORM\ManyToOne(targetEntity="Match", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * })
     */
    private $match;

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
     * @var bool
     *
     * @ORM\Column(name="is_start", type="boolean")
     */
    private $isStart = true;

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
     * Set team
     *
     * @param Team $team
     * @return LineUpPlayer
     */
    public function setTeam(Team $team = null)
    {
        $this->team = $team;
    
        return $this;
    }

    /**
     * Get team
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set prediction
     *
     * @param Match $match
     * @return LineUpPlayer
     */
    public function setMatch(Match $match = null)
    {
        $this->match = $match;
    
        return $this;
    }

    /**
     * Get Match
     *
     * @return Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * Set player
     *
     * @param Player $player
     * @return LineUpPlayer
     */
    public function setPlayer(Player $player = null)
    {
        $this->player = $player;
    
        return $this;
    }

    /**
     * Get player
     *
     * @return \Player 
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param boolean $isStart
     */
    public function setIsStart($isStart)
    {
        $this->isStart = $isStart;
    }

    /**
     * @return boolean
     */
    public function getIsStart()
    {
        return $this->isStart;
    }

}
