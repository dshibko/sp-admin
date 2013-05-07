<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="team")
 * @ORM\Entity
 */
class Team extends BasicObject {

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=50, nullable=false)
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
     * @ORM\OneToMany(targetEntity="Match", mappedBy="homeTeam")
     */
    private $homeMatches;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Match", mappedBy="awayTeam")
     */
    private $awayMatches;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Player", mappedBy="team")
     */
    private $players;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->homeMatches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->awayMatches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Team
     */
    public function setDisplayName($displayName)
    {
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
     * @return Team
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
     * Add homeMatches
     *
     * @param Match $homeMatches
     * @return Team
     */
    public function addHomeMatch(Match $homeMatches)
    {
        $this->homeMatches[] = $homeMatches;
    
        return $this;
    }

    /**
     * Remove homeMatches
     *
     * @param Match $homeMatches
     */
    public function removeHomeMatch(Match $homeMatches)
    {
        $this->homeMatches->removeElement($homeMatches);
    }

    /**
     * Get homeMatches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHomeMatches()
    {
        return $this->homeMatches;
    }

    /**
     * Add awayMatches
     *
     * @param Match $awayMatches
     * @return Team
     */
    public function addAwayMatche(Match $awayMatches)
    {
        $this->awayMatches[] = $awayMatches;
    
        return $this;
    }

    /**
     * Remove awayMatches
     *
     * @param Match $awayMatches
     */
    public function removeAwayMatche(Match $awayMatches)
    {
        $this->awayMatches->removeElement($awayMatches);
    }

    /**
     * Get awayMatches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAwayMatches()
    {
        return $this->awayMatches;
    }

    /**
     * Add players
     *
     * @param Player $players
     * @return Team
     */
    public function addPlayer(Player $players)
    {
        $this->players[] = $players;
    
        return $this;
    }

    /**
     * Remove players
     *
     * @param Player $players
     */
    public function removePlayer(Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }
}
