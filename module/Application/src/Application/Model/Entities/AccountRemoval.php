<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Account Removal
 *
 * @ORM\Table(name="account_removal")
 * @ORM\Entity
 */
class AccountRemoval extends BasicObject {

    const DIRECT_ACCOUNT = 'direct';
    const FACEBOOK_ACCOUNT = 'facebook';
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="account_type", type="string", columnDefinition="ENUM('direct', 'facebook')")
     */
    protected $accountType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    protected $date;

    /**
     * @param string $accountType
     * @return $this
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * @param \DateTime $date
     * @return $this
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
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



}
