<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Neoco\Model\BasicObject;

/**
 * PreMatchReportMostRecentScorer
 *
 * @ORM\Table(name="pre_match_report_most_recent_scorer")
 * @ORM\Entity
 */
class PreMatchReportMostRecentScorer extends BasicObject
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

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
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;

    /**
     * @var integer
     *
     * @ORM\Column(name="place", type="integer")
     */
    protected $place;

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
     * @param \Application\Model\Entities\Match $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }

    /**
     * @return \Application\Model\Entities\Match
     */
    public function getMatch()
    {
        return $this->match;
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
     * @param \Application\Model\Entities\Player $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @return \Application\Model\Entities\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param \Application\Model\Entities\Team $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return \Application\Model\Entities\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

}
