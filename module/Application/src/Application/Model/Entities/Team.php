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
     * @var integer
     *
     * @ORM\Column(name="stadium_capacity", type="integer")
     */
    private $stadiumCapacity;

    /**
     * @var string
     *
     * @ORM\Column(name="stadium_name", type="string", length=50)
     */
    private $stadiumName;

    /**
     * @var string
     *
     * @ORM\Column(name="manager", type="string", length=100)
     */
    private $manager;

    /**
     * @var integer
     *
     * @ORM\Column(name="feeder_id", type="integer", nullable=false)
     */
    private $feederId;

    /**
     * @var integer
     *
     * @ORM\Column(name="founded", type="integer")
     */
    private $founded;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=50, nullable=false)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="short_name", type="string", length=10)
     */
    private $shortName;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Competition", cascade={"persist"})
     * @ORM\JoinTable(name="team_competition",
     *   joinColumns={
     *     @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     *   }
     * )
     */
    private $competitions;

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
     * @ORM\OneToMany(targetEntity="Player", mappedBy="team", cascade={"persist"})
     */
    private $players;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->competitions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Has player
     *
     * @param \Application\Model\Entities\Player $player
     * @return bool
     */
    public function hasPlayer(Player $player)
    {
        return $this->players->contains($player);
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

    /**
     * @param int $feederId
     */
    public function setFeederId($feederId)
    {
        $this->feederId = $feederId;
    }

    /**
     * @return int
     */
    public function getFeederId()
    {
        return $this->feederId;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param int $founded
     */
    public function setFounded($founded)
    {
        $this->founded = $founded;
    }

    /**
     * @return int
     */
    public function getFounded()
    {
        return $this->founded;
    }

    /**
     * @param int $stadiumCapacity
     */
    public function setStadiumCapacity($stadiumCapacity)
    {
        $this->stadiumCapacity = $stadiumCapacity;
    }

    /**
     * @return int
     */
    public function getStadiumCapacity()
    {
        return $this->stadiumCapacity;
    }

    /**
     * @param string $stadiumName
     */
    public function setStadiumName($stadiumName)
    {
        $this->stadiumName = $stadiumName;
    }

    /**
     * @return string
     */
    public function getStadiumName()
    {
        return $this->stadiumName;
    }

    /**
     * @param string $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return string
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Add competitions
     *
     * @param Competition $competition
     * @return \Application\Model\Entities\Team
     */
    public function addCompetition(Competition $competition)
    {
        $this->competitions[] = $competition;

        return $this;
    }

    /**
     * Has competition
     *
     * @param \Application\Model\Entities\Competition $competition
     * @return \Application\Model\Entities\Team
     */
    public function hasCompetition(Competition $competition)
    {
        return $this->competitions->contains($competition);
    }

    /**
     * Remove competitions
     *
     * @param Competition $competitions
     */
    public function removeCompetition(Competition $competitions)
    {
        $this->competitions->removeElement($competitions);
    }

    /**
     * Clear competitions
     */
    public function clearCompetitions()
    {
        $this->competitions->clear();
    }


}
