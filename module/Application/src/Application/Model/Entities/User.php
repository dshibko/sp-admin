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
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_public", type="boolean")
     */
    protected $isPublic = true;
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=5, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=30, nullable=false)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=30, nullable=false)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    protected $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=false)
     */
    protected $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=10, nullable=false)
     */
    protected $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=20, nullable=false)
     */
    protected $displayName;

    /**
     * @var Avatar
     *
     * @ORM\OneToOne(targetEntity="Avatar", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="avatar_id", referencedColumnName="id")
     * })
     */
    protected $avatar;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Recovery", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    protected $recoveries;

    /**
     * @var integer
     *
     * @ORM\Column(name="favourite_player_id", type="integer", nullable=true)
     */
    protected $favouritePlayerId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="facebook_id", type="bigint", nullable=true, options={"unsigned"=true})
     */
    protected $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_access_token", type="string", length=300, nullable=true)
     */
    protected $facebookAccessToken;
    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    protected $role;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    protected $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_logged_in", type="datetime", nullable=true)
     */
    protected $lastLoggedIn;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    protected $country;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    protected $language;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="League", mappedBy="creator")
     */
    protected $ownLeagues;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="League", mappedBy="users")
     */
    protected $leagues;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="LeagueUser", mappedBy="user")
     */
    protected $leagueUsers;

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
     * @return \Application\Model\Entities\User
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
     * @return \Application\Model\Entities\User
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
     * @return \Application\Model\Entities\User
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
     * @param \Doctrine\Common\Collections\Collection $leagues
     * @return \Application\Model\Entities\User
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
     * @return \Application\Model\Entities\User
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
     * @ORM\OneToMany(targetEntity="Prediction", mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    protected $predictions;


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
     * @param \Doctrine\Common\Collections\Collection $leagueUsers
     */
    public function setLeagueUsers($leagueUsers)
    {
        $this->leagueUsers = $leagueUsers;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLeagueUsers()
    {
        return $this->leagueUsers;
    }

    /**
     * @param boolean $active
     */
    public function setIsActive($active)
    {
        $this->isActive = $active;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
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
     * @param int $facebookId
     * @return \Application\Model\Entities\User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        return $this;
    }

    /**
     * @return int
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


    /**
     * @param Recovery $recovery
     * @return $this
     */
    public function addRecovery(Recovery $recovery)
    {
        $this->recoveries[] = $recovery;

        return $this;
    }


    /**
     * @param Recovery $recovery
     * @return $this
     */
    public function removeRecovery(Recovery $recovery)
    {
        $this->recoveries->removeElement($recovery);
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecoveries()
    {
        return $this->recoveries;
    }

    /**
     * @param array $data
     */
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
        if (isset($data['last_logged_in']) && $data['last_logged_in'] instanceof \DateTime){
            $this->setLastLoggedIn($data['last_logged_in']);
        }
        if (isset($data['language']) && $data['language'] instanceof \Application\Model\Entities\Language){
            $this->setLanguage($data['language']);
        }
        if (isset($data['role']) && $data['role'] instanceof \Application\Model\Entities\Role){
            $this->setRole($data['role']);
        }
        if (isset($data['active'])){
            $this->setIsActive($data['active']);
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

    /**
     * @param \DateTime $lastLoggedIn
     * @return $this
     */
    public function setLastLoggedIn(\DateTime $lastLoggedIn)
    {
        $this->lastLoggedIn = $lastLoggedIn;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastLoggedIn()
    {
        return $this->lastLoggedIn;
    }

}
