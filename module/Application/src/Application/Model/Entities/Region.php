<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table(name="region")
 * @ORM\Entity
 */
class Region extends BasicObject {

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    private $isDefault = false;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=50, nullable=false)
     */
    private $displayName;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Region
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="region")
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="RegionGameplayContent", mappedBy="region")
     */
    private $regionGameplayBlocks;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Country", mappedBy="region")
     */
    private $countries;

    /**
     * @var RegionContent
     *
     * @ORM\OneToOne(targetEntity="RegionContent", mappedBy="region")
     */
    private $regionContent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add users
     *
     * @param User $users
     * @return Region
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
     * Add countries
     *
     * @param Country $countries
     * @return Region
     */
    public function addCountry(Country $countries)
    {
        $this->countries[] = $countries;
    
        return $this;
    }

    /**
     * Remove countries
     *
     * @param Country $countries
     */
    public function removeCountry(Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param \Application\Model\Entities\RegionContent $regionContent
     */
    public function setRegionContent($regionContent)
    {
        $this->regionContent = $regionContent;
    }

    /**
     * @return \Application\Model\Entities\RegionContent
     */
    public function getRegionContent()
    {
        return $this->regionContent;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $regionGameplayBlocks
     */
    public function setRegionGameplayBlocks($regionGameplayBlocks)
    {
        $this->regionGameplayBlocks = $regionGameplayBlocks;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegionGameplayBlocks()
    {
        return $this->regionGameplayBlocks;
    }

    /**
     * @param $order
     * @return \Application\Model\Entities\RegionGameplayContent
     */
    public function getRegionGameplayBlockByOrder($order) {
        return $this->getRegionGameplayBlocks()->filter(function (RegionGameplayContent $regionGameplayContent) use ($order) {
            return $order == $regionGameplayContent->getOrder();
        })->first();
    }

    /**
     * @param boolean $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @return boolean
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

}