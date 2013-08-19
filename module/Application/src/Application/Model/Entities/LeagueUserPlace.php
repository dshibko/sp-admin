<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * League
 *
 * @ORM\Table(name="league_user_place")
 * @ORM\Entity
 */
class LeagueUserPlace extends BasicObject {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var LeagueUser
     *
     * @ORM\ManyToOne(targetEntity="LeagueUser", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="league_user_id", referencedColumnName="id")
     * })
     */
    protected $leagueUser;

    /**
     * @var Match
     *
     * @ORM\ManyToOne(targetEntity="Match", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * })
     */
    private $match;

    /**
     * @var integer
     *
     * @ORM\Column(name="previous_place", type="integer")
     */
    private $previousPlace;

    /**
     * @var integer
     *
     * @ORM\Column(name="place", type="integer")
     */
    private $place;

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
     * @param \Application\Model\Entities\LeagueUser $leagueUser
     */
    public function setLeagueUser($leagueUser)
    {
        $this->leagueUser = $leagueUser;
    }

    /**
     * @return \Application\Model\Entities\LeagueUser
     */
    public function getLeagueUser()
    {
        return $this->leagueUser;
    }

    /**
     * @param \Application\Model\Entities\Match $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }

    /**
     * @return \Application\Model\Entities\Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param int $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return int
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param int $previousPlace
     */
    public function setPreviousPlace($previousPlace)
    {
        $this->previousPlace = $previousPlace;
    }

    /**
     * @return int
     */
    public function getPreviousPlace()
    {
        return $this->previousPlace;
    }

}