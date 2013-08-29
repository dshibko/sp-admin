<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Neoco\Model\BasicObject;

/**
 * PreMatchReportGoalsScored
 *
 * @ORM\Table(name="pre_match_report_goals_scored")
 * @ORM\Entity
 */
class PreMatchReportGoalsScored extends BasicObject
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
     * @ORM\Column(name="home_team_goals", type="integer")
     */
    protected $homeTeamGoals;

    /**
     * @var integer
     *
     * @ORM\Column(name="away_team_goals", type="integer")
     */
    protected $awayTeamGoals;

    /**
     * @param int $awayTeamGoals
     */
    public function setAwayTeamGoals($awayTeamGoals)
    {
        $this->awayTeamGoals = $awayTeamGoals;
    }

    /**
     * @return int
     */
    public function getAwayTeamGoals()
    {
        return $this->awayTeamGoals;
    }

    /**
     * @param int $homeTeamGoals
     */
    public function setHomeTeamGoals($homeTeamGoals)
    {
        $this->homeTeamGoals = $homeTeamGoals;
    }

    /**
     * @return int
     */
    public function getHomeTeamGoals()
    {
        return $this->homeTeamGoals;
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
