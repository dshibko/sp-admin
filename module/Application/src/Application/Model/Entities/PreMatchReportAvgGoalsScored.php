<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Neoco\Model\BasicObject;

/**
 * PreMatchReportAvgGoalsScored
 *
 * @ORM\Table(name="pre_match_report_avg_goals_scored")
 * @ORM\Entity
 */
class PreMatchReportAvgGoalsScored extends BasicObject
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
     * @var float
     *
     * @ORM\Column(name="home_team_avg_goals", type="decimal", precision=5, scale=2)
     */
    protected $homeTeamAvgGoals;

    /**
     * @var float
     *
     * @ORM\Column(name="away_team_avg_goals", type="decimal", precision=5, scale=2)
     */
    protected $awayTeamAvgGoals;

    /**
     * @param float $awayTeamAvgGoals
     */
    public function setAwayTeamAvgGoals($awayTeamAvgGoals)
    {
        $this->awayTeamAvgGoals = $awayTeamAvgGoals;
    }

    /**
     * @return float
     */
    public function getAwayTeamAvgGoals()
    {
        return $this->awayTeamAvgGoals;
    }

    /**
     * @param float $homeTeamAvgGoals
     */
    public function setHomeTeamAvgGoals($homeTeamAvgGoals)
    {
        $this->homeTeamAvgGoals = $homeTeamAvgGoals;
    }

    /**
     * @return float
     */
    public function getHomeTeamAvgGoals()
    {
        return $this->homeTeamAvgGoals;
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
