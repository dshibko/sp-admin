<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity
 */
class Player extends BasicObject {

    function __construct()
    {
        $this->competitions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Competition", cascade={"persist"})
     * @ORM\JoinTable(name="player_competition",
     *   joinColumns={
     *     @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $competitions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="join_date", type="date")
     */
    protected $joinDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date")
     */
    protected $birthDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer")
     */
    protected $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=100)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="real_position", type="string", length=50)
     */
    protected $realPosition;

    /**
     * @var string
     *
     * @ORM\Column(name="real_position_side", type="string", length=20)
     */
    protected $realPositionSide;

    /**
     * @var integer
     *
     * @ORM\Column(name="feeder_id", type="integer", nullable=false)
     */
    protected $feederId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=100, nullable=false)
     */
    protected $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=50, nullable=false)
     */
    protected $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=50, nullable=false)
     */
    protected $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="shirt_number", type="integer", nullable=true)
     */
    protected $shirtNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path", type="string", length=255, nullable=true)
     */
    protected $imagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="background_image_path", type="string", length=255, nullable=true)
     */
    protected $backgroundImagePath;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_blocked", type="boolean")
     */
    protected $isBlocked = false;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="players")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    protected $team;


    /**
     * Set name
     *
     * @param string $name
     * @return Player
     */
    public function setName($name)
    {
        if (!empty($name))
            $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Player
     */
    public function setSurname($surname)
    {
        if (!empty($surname))
            $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Player
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
     * Set position
     *
     * @param string $position
     * @return Player
     */
    public function setPosition($position)
    {
        if (!empty($position))
            $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set shirtNumber
     *
     * @param integer $shirtNumber
     * @return Player
     */
    public function setShirtNumber($shirtNumber)
    {
        if ($shirtNumber !== null && $shirtNumber !== '')
            $this->shirtNumber = $shirtNumber;
    
        return $this;
    }

    /**
     * Get shirtNumber
     *
     * @return integer
     */
    public function getShirtNumber()
    {
        return $this->shirtNumber;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     * @return Player
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    
        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string 
     */
    public function getImagePath()
    {
        return $this->imagePath;
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
     * @return Player
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
     * @param \DateTime $birthDate
     */
    public function setBirthDate($birthDate)
    {
        if ($birthDate != null)
            $this->birthDate = $birthDate;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        if (!empty($country))
            $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        if (!empty($height))
            $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param \DateTime $joinDate
     */
    public function setJoinDate($joinDate)
    {
        if ($joinDate != null)
            $this->joinDate = $joinDate;
    }

    /**
     * @return \DateTime
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * @param string $realPosition
     */
    public function setRealPosition($realPosition)
    {
        if (!empty($realPosition))
            $this->realPosition = $realPosition;
    }

    /**
     * @return string
     */
    public function getRealPosition()
    {
        return $this->realPosition;
    }

    /**
     * @param string $realPositionSide
     */
    public function setRealPositionSide($realPositionSide)
    {
        if (!empty($realPositionSide))
            $this->realPositionSide = $realPositionSide;
    }

    /**
     * @return string
     */
    public function getRealPositionSide()
    {
        return $this->realPositionSide;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
        if (!empty($weight))
            $this->weight = $weight;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $competitions
     */
    public function setCompetitions($competitions)
    {
        $this->competitions = $competitions;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompetitions()
    {
        return $this->competitions;
    }

    /**
     * Add competitions
     *
     * @param Competition $competition
     * @return \Application\Model\Entities\Player
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
     * @return \Application\Model\Entities\Player
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


    /**
     * @param $backgroundImagePath
     * @return \Application\Model\Entities\Player
     */
    public function setBackgroundImagePath($backgroundImagePath)
    {
        $this->backgroundImagePath = $backgroundImagePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundImagePath()
    {
        return $this->backgroundImagePath;
    }


    /**
     * @param $isBlocked
     * @return Player
     */
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsBlocked()
    {
        return $this->isBlocked;
    }

}
