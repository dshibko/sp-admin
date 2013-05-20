<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * MatchGoal
 *
 * @ORM\Table(name="match_goal")
 * @ORM\Entity
 */
class MatchGoal extends BasicObject {

    const FIRST_HALF_PERIOD = 'FirstHalf';
    const SECOND_HALF_PERIOD = 'SecondHalf';
    const EXTRA_FIRST_HALF_PERIOD = 'ExtraFirstHalf';
    const EXTRA_SECOND_HALF_PERIOD = 'ExtraSecondHalf';
    const SHOOTOUT_PERIOD = 'ShootOut';

    const GOAL_TYPE = 'Goal';
    const PENALTY_TYPE = 'Penalty';
    const OWN_TYPE = 'Own';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */

    private $time;
    /**
     * @var integer
     *
     * @ORM\Column(name="minute", type="integer")
     */
    private $minute;

    /**
     * @var string
     *
     * @ORM\Column(name="period", type="string", columnDefinition="ENUM('FirstHalf', 'SecondHalf', 'ExtraFirstHalf', 'ExtraSecondHalf', 'ShootOut')")
     */
    private $period;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", columnDefinition="ENUM('Goal', 'Penalty', 'Own')")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="`order`", type="integer")
     */
    private $order;

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
     * @ORM\ManyToOne(targetEntity="Match")
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
     * Set order
     *
     * @param integer $order
     * @return MatchGoal
     */
    public function setOrder($order)
    {
        $this->order = $order;
    
        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
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
     * Set team
     *
     * @param Team $team
     * @return MatchGoal
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
     * Set match
     *
     * @param Match $match
     * @return MatchGoal
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
     * Set player
     *
     * @param Player $player
     * @return MatchGoal
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
     * @param int $minute
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;
    }

    /**
     * @return int
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param string $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}
