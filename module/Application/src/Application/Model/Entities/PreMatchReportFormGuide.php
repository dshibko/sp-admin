<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Neoco\Model\BasicObject;

/**
 * PreMatchReportFormGuide
 *
 * @ORM\Table(name="pre_match_report_form_guide")
 * @ORM\Entity
 */
class PreMatchReportFormGuide extends BasicObject
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
     * @var string
     *
     * @ORM\Column(name="home_team_form", type="string")
     */
    protected $homeTeamForm;

    /**
     * @var string
     *
     * @ORM\Column(name="away_team_form", type="string")
     */
    protected $awayTeamForm;

    /**
     * @param string $awayTeamForm
     */
    public function setAwayTeamForm($awayTeamForm)
    {
        $this->awayTeamForm = $awayTeamForm;
    }

    /**
     * @return string
     */
    public function getAwayTeamForm()
    {
        return $this->awayTeamForm;
    }

    /**
     * @param string $homeTeamForm
     */
    public function setHomeTeamForm($homeTeamForm)
    {
        $this->homeTeamForm = $homeTeamForm;
    }

    /**
     * @return string
     */
    public function getHomeTeamForm()
    {
        return $this->homeTeamForm;
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
