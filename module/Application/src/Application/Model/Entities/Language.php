<?php

namespace Application\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity
 */
class Language extends BasicObject {

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean")
     */
    protected $isDefault = false;

    /**
     * @var string
     *
     * @ORM\Column(name="language_code", type="string", length=5, nullable=false)
     */
    protected $languageCode;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=40, nullable=false)
     */
    protected $displayName;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;



    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="language")
     */
    protected $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Country", mappedBy="language", cascade={"persist"})
     */
    protected $countries;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="HowToPlayContent", mappedBy="language")
     */
    protected $howToPlayBlocks;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->howToPlayBlocks = new ArrayCollection();
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $howToPlayBlocks
     * @return $this
     */
    public function setHowToPlayBlocks($howToPlayBlocks)
    {
        $this->howToPlayBlocks = $howToPlayBlocks;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHowToPlayBlocks()
    {
        return $this->howToPlayBlocks;
    }
    /**
     * Set languageCode
     *
     * @param string $languageCode
     * @return Language
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
    
        return $this;
    }

    /**
     * Get languageCode
     *
     * @return string 
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Language
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
     * Add users
     *
     * @param User $users
     * @return Language
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
     * @return Language
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