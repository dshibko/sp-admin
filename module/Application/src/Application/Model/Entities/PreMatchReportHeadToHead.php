<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Neoco\Model\BasicObject;

/**
 * PreMatchReportHeadToHead
 *
 * @ORM\Table(name="pre_match_report_head_to_head")
 * @ORM\Entity
 */
class PreMatchReportHeadToHead extends BasicObject
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
     * @ORM\OneToOne(targetEntity="Match")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * })
     */

    private $match;

    /**
     * @var integer
     *
     * @ORM\Column(name="home_team_wins", type="integer")
     */
    protected $homeTeamWins;

    /**
     * @var integer
     *
     * @ORM\Column(name="draws", type="integer")
     */
    protected $draws;

    /**
     * @var integer
     *
     * @ORM\Column(name="away_team_wins", type="integer")
     */
    protected $awayTeamWins;

    /**
     * @param int $awayTeamWins
     */
    public function setAwayTeamWins($awayTeamWins)
    {
        $this->awayTeamWins = $awayTeamWins;
    }

    /**
     * @return int
     */
    public function getAwayTeamWins()
    {
        return $this->awayTeamWins;
    }

    /**
     * @param int $draws
     */
    public function setDraws($draws)
    {
        $this->draws = $draws;
    }

    /**
     * @return int
     */
    public function getDraws()
    {
        return $this->draws;
    }

    /**
     * @param int $homeTeamWins
     */
    public function setHomeTeamWins($homeTeamWins)
    {
        $this->homeTeamWins = $homeTeamWins;
    }

    /**
     * @return int
     */
    public function getHomeTeamWins()
    {
        return $this->homeTeamWins;
    }

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

}
