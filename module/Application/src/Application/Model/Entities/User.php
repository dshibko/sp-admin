<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use \Neoco\Model\BasicObject;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User extends BasicObject {

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_public", type="boolean")
     */
    private $isPublic = 1;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=5, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=30, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=30, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=false)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10, nullable=false)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=20, nullable=false)
     */
    private $displayName;

    /**
     * @var \Avatar
     *
     * @ORM\ManyToOne(targetEntity="Avatar", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="avatar_id", referencedColumnName="id")
     * })
     */
    private $avatar;

    /**
     * @var integer
     *
     * @ORM\Column(name="favourite_player_id", type="integer", nullable=true)
     */
    private $favouritePlayerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var bigint
     *
     * @ORM\Column(name="facebook_id", type="bigint", nullable=true, options={"unsigned"=true})
     */
    private $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_access_token", type="string", length=300, nullable=true)
     */
    private $facebookAccessToken;
    /**
     * @var \Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     * })
     */
    private $region;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="League", mappedBy="creator")
     */
    private $ownLeagues;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="League", mappedBy="users")
     */
    private $leagues;

    /**
     * Set title
     *
     * @param string $title
     * @return User
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    
        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return User
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
     * Set avatar
     *
     * @param \Application\Model\Entities\Avatar $avatar
     * @return User
     */
    public function setAvatar(\Application\Model\Entities\Avatar $avatar)
    {
        $this->avatar = $avatar;
    
        return $this;
    }

    /**
     * Get avatar
     *
     * @return \Application\Model\Entities\Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set favouritePlayerId
     *
     * @param integer $favouritePlayerId
     * @return User
     */
    public function setFavouritePlayerId($favouritePlayerId)
    {
        $this->favouritePlayerId = $favouritePlayerId;
    
        return $this;
    }

    /**
     * Get favouritePlayerId
     *
     * @return integer 
     */
    public function getFavouritePlayerId()
    {
        return $this->favouritePlayerId;
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
     * Set role
     *
     * @param \Application\Model\Entities\Role $role
     * @return User
     */
    public function setRole(\Application\Model\Entities\Role $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return \Application\Model\Entities\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \Application\Model\Entities\Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param \Application\Model\Entities\Language $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param \Application\Model\Entities\Region $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $leagues
     */
    public function setLeagues($leagues)
    {
        $this->leagues = $leagues;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLeagues()
    {
        return $this->leagues;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $ownLeagues
     */
    public function setOwnLeagues($ownLeagues)
    {
        $this->ownLeagues = $ownLeagues;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOwnLeagues()
    {
        return $this->ownLeagues;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="user")
     */
    private $predictions;


    /**
     * Add predictions
     *
     * @param Prediction $predictions
     * @return User
     */
    public function addPrediction(Prediction $predictions)
    {
        $this->predictions[] = $predictions;

        return $this;
    }

    /**
     * Remove predictions
     *
     * @param Prediction $predictions
     */
    public function removePrediction(Prediction $predictions)
    {
        $this->predictions->removeElement($predictions);
    }

    /**
     * Get predictions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPredictions()
    {
        return $this->predictions;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $facebookAccessToken
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param \Application\Model\Entities\bigint $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\bigint
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     *  @param boolean $isPublic
     *  @return \Application\Model\Entities\User
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    public function populate(array $data = array()){

        if (isset($data['title'])){
            $this->setTitle($data['title']);
        }
        if (isset($data['country']) && $data['country'] instanceof \Application\Model\Entities\Country){
            $this->setCountry($data['country']);
        }
        if (isset($data['avatar']) && $data['avatar'] instanceof \Application\Model\Entities\Avatar){
            $this->setAvatar($data['avatar']);
        }
        if (isset($data['date_of_birth']) && $data['date_of_birth'] instanceof \DateTime){
            $this->setBirthday($data['date_of_birth']);
        }
        if (isset($data['display_name'])){
            $this->setDisplayName($data['display_name']);
        }
        if (isset($data['first_name'])){
            $this->setFirstName($data['first_name']);
        }
        if (isset($data['last_name'])){
            $this->setLastName($data['last_name']);
        }
        if (isset($data['password'])){
            $this->setPassword($data['password']);
        }
        if (isset($data['gender'])){
            $this->setGender($data['gender']);
        }
        if (isset($data['email'])){
            $this->setEmail($data['email']);
        }
        if (isset($data['date']) && $data['date'] instanceof \DateTime){
            $this->setDate($data['date']);
        }

        if (isset($data['language']) && $data['language'] instanceof \Application\Model\Entities\Language){
            $this->setLanguage($data['language']);
        }
        if (isset($data['region']) && $data['region'] instanceof \Application\Model\Entities\Region){
            $this->setRegion($data['region']);
        }
        if (isset($data['role']) && $data['role'] instanceof \Application\Model\Entities\Role){
            $this->setRole($data['role']);
        }
        if (isset($data['active'])){
            $this->setActive($data['active']);
        }
        if (isset($data['facebook_id'])){
            $this->setFacebookId($data['facebook_id']);
        }
        if (isset($data['facebook_access_token'])){
            $this->setFacebookAccessToken($data['facebook_access_token']);
        }
        if (isset($data['is_public'])){
            $this->setIsPublic($data['is_public']);
        }
    }



}
