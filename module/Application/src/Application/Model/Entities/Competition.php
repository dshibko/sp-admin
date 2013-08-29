<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Competition
 *
 * @ORM\Table(name="competition")
 * @ORM\Entity
 */
class Competition extends BasicObject {

    /**
     * @var integer
     *
     * @ORM\Column(name="feeder_id", type="integer", nullable=false)
     */
    private $feederId;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=100, nullable=false)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_path", type="string", length=255, nullable=true)
     */
    private $logoPath;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Match", mappedBy="competition")
     */
    private $matches;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Season", inversedBy="competitions")
     * @ORM\JoinTable(name="competition_season",
     *   joinColumns={
     *     @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     *   }
     * )
     */
    private $seasons;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CompetitionSeason", mappedBy="competition")
     */
    private $competitionSeasons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seasons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->competitionSeasons = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Competition
     */
    public function setDisplayName($displayName)
    {
        if (!empty($displayName))
            $this->displayName = $displayName;
    
        return $this;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set logoPath
     *
     * @param string $logoPath
     * @return Competition
     */
    public function setLogoPath($logoPath)
    {
        $this->logoPath = $logoPath;
    
        return $this;
    }

    /**
     * Get logoPath
     *
     * @return string 
     */
    public function getLogoPath()
    {
        return $this->logoPath;
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
     * Add matches
     *
     * @param Match $matches
     * @return Competition
     */
    public function addMatch(Match $matches)
    {
        $this->matches[] = $matches;
    
        return $this;
    }

    /**
     * Remove matches
     *
     * @param Match $matches
     */
    public function removeMatch(Match $matches)
    {
        $this->matches->removeElement($matches);
    }

    /**
     * Get matches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Add season
     *
     * @param Season $season
     * @return Competition
     */
    public function addSeason(Season $season)
    {
        $this->seasons[] = $season;

        return $this;
    }

    /**
     * Remove season
     *
     * @param Season $season
     */
    public function removeSeason(Season $season)
    {
        $this->seasons->removeElement($season);
    }

    /**
     * Get seasons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeasons()
    {
        return $this->seasons;
    }

    /**
     * @param integer $feederId
     */
    public function setFeederId($feederId)
    {
        $this->feederId = $feederId;
    }

    /**
     * @return integer
     */
    public function getFeederId()
    {
        return $this->feederId;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $competitionSeasons
     */
    public function setCompetitionSeasons($competitionSeasons)
    {
        $this->competitionSeasons = $competitionSeasons;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompetitionSeasons()
    {
        return $this->competitionSeasons;
    }

}
