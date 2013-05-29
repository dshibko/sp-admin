<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * League
 *
 * @ORM\Table(name="league")
 * @ORM\Entity
 */
class League extends BasicObject {

    const GLOBAL_TYPE = 'Global';
    const REGIONAL_TYPE = 'Regional';
    const MINI_TYPE = 'Mini';
    const PRIVATE_TYPE = 'Private';

    public static function getAvailableTypes() {
        return array(self::GLOBAL_TYPE, self::REGIONAL_TYPE, self::MINI_TYPE, self::PRIVATE_TYPE);
    }

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=50, nullable=false)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_path", type="string", length=255, nullable=true)
     */
    private $logoPath;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * })
     */
    private $creator;

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
     * @ORM\ManyToMany(targetEntity="Region", inversedBy="leagues", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="league_region",
     *   joinColumns={
     *     @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     *   }
     * )
     */
    private $regions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="leagues", cascade={"persist"})
     * @ORM\JoinTable(name="league_user",
     *   joinColumns={
     *     @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   }
     * )
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set displayName
     *
     * @param string $displayName
     * @return League
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
     * Get isGlobal
     *
     * @return boolean 
     */
    public function getIsGlobal()
    {
        return $this->getType() == League::GLOBAL_TYPE;
    }

    /**
     * Get isRegional
     *
     * @return boolean 
     */
    public function getIsRegional()
    {
        return $this->getType() == League::REGIONAL_TYPE;
    }

    /**
     * Get isMini
     *
     * @return boolean
     */
    public function getIsMini()
    {
        return $this->getType() == League::MINI_TYPE;
    }

    /**
     * Get isPrivate
     *
     * @return boolean
     */
    public function getIsPrivate()
    {
        return $this->getType() == League::PRIVATE_TYPE;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return League
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return League
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set logoPath
     *
     * @param string $logoPath
     * @return League
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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return League
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
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
     * Set creator
     *
     * @param User $creator
     * @return League
     */
    public function setCreator(User $creator = null)
    {
        $this->creator = $creator;
    
        return $this;
    }

    /**
     * Get creator
     *
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set season
     *
     * @param Season $season
     * @return League
     */
    public function setSeason(Season $season = null)
    {
        $this->season = $season;
    
        return $this;
    }

    /**
     * Get season
     *
     * @return Season
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Get region
     *
     * @return Region
     */
    public function getRegion()
    {
        return $this->getRegions()->first();
    }

    /**
     * Add users
     *
     * @param User $users
     * @return League
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="LeagueUser", mappedBy="league", cascade={"persist", "remove"})
     */
    private $leagueUsers;

    /**
     * Add leagueUsers
     *
     * @param LeagueUser $leagueUsers
     * @return League
     */
    public function addLeagueUser(LeagueUser $leagueUsers)
    {
        $this->leagueUsers[] = $leagueUsers;

        return $this;
    }

    /**
     * Remove leagueUsers
     *
     * @param LeagueUser $leagueUsers
     */
    public function removeLeagueUser(LeagueUser $leagueUsers)
    {
        $this->leagueUsers->removeElement($leagueUsers);
    }

    /**
     * Get leagueUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLeagueUsers()
    {
        return $this->leagueUsers;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Prize", mappedBy="league", cascade={"persist", "remove"})
     */
    private $prizes;

    /**
     * Add prizes
     *
     * @param Prize $prizes
     * @return League
     */
    public function addPrize(Prize $prizes)
    {
        $this->prizes[] = $prizes;

        return $this;
    }

    /**
     * Remove prizes
     *
     * @param Prize $prizes
     */
    public function removePrize(Prize $prizes)
    {
        $this->prizes->removeElement($prizes);
    }

    /**
     * Get prizes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrizes()
    {
        return $this->prizes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $regions
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegions()
    {
        return $this->regions;
    }

    /**
     * Add regions
     *
     * @param Region $regions
     * @return League
     */
    public function addRegion(Region $regions)
    {
        $this->regions[] = $regions;

        return $this;
    }

    /**
     * Remove regions
     *
     * @param Region $regions
     */
    public function removeRegion(Region $regions)
    {
        $this->regions->removeElement($regions);
    }

    private $prizesByRegion = array();

    public function getPrizeByRegionId($id)
    {
        if (!array_key_exists($id, $this->prizesByRegion))
            foreach ($this->getPrizes() as $prize)
                if ($prize->getRegion()->getId() == $id) {
                    $this->prizesByRegion[$id] = $prize;
                    break;
                }
        return $this->prizesByRegion[$id];
    }

}