<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * PrivateLeague
 *
 * @ORM\Table(name="private_league")
 * @ORM\Entity
 */
class PrivateLeague extends BasicObject
{

    /**
     * @var string
     *
     * @ORM\Column(name="unique_hash", type="string", length=10)
     */
    protected $uniqueHash;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var League
     *
     * @ORM\OneToOne(targetEntity="League")
     * @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     */
    protected $league;

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
     * @param \Application\Model\Entities\League $league
     */
    public function setLeague($league)
    {
        $this->league = $league;
    }

    /**
     * @return \Application\Model\Entities\League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * @param string $uniqueHash
     */
    public function setUniqueHash($uniqueHash)
    {
        $this->uniqueHash = $uniqueHash;
    }

    /**
     * @return string
     */
    public function getUniqueHash()
    {
        return $this->uniqueHash;
    }

}