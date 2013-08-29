<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompetitionSeason
 *
 * @ORM\Table(name="competition_season")
 * @ORM\Entity
 */
class CompetitionSeason
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Competition
     *
     * @ORM\ManyToOne(targetEntity="Competition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     * })
     */
    private $competition;

    /**
     * @var Season
     *
     * @ORM\ManyToOne(targetEntity="Season")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     * })
     */
    private $season;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Match", mappedBy="competitionSeason")
     */
    private $matches;

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
     * @param \Application\Model\Entities\Competition $competition
     */
    public function setCompetition($competition)
    {
        $this->competition = $competition;
    }

    /**
     * @return \Application\Model\Entities\Competition
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * @param \Application\Model\Entities\Season $season
     */
    public function setSeason($season)
    {
        $this->season = $season;
    }

    /**
     * @return \Application\Model\Entities\Season
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $matches
     */
    public function setMatches($matches)
    {
        $this->matches = $matches;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatches()
    {
        return $this->matches;
    }

}
