<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity
 */
class Message extends BasicObject {

    const MATCH_REPORT_TYPE = 'MatchReport';
    const CLUB_UPDATE_TYPE = 'ClubUpdate';

    /**
     * @var string
     *
     * @ORM\Column(name="message_type", type="string", columnDefinition="ENUM('MatchReport', 'ClubUpdate')")
     */
    protected $messageType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToOne(targetEntity="MatchReportMessage", mappedBy="message", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $matchReportMessage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="was_viewed", type="boolean")
     */
    private $wasViewed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

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
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * Get matchReportMessage
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchReportMessage()
    {
        return $this->matchReportMessage;
    }

    /**
     * Set matchReportMessage
     *
     * @param MatchReportMessage $matchReportMessage
     * @return Message
     */
    public function setMatchReportMessage(MatchReportMessage $matchReportMessage)
    {
        $this->matchReportMessage = $matchReportMessage;

        return $this;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Message
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
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
     * @param boolean $wasViewed
     */
    public function setWasViewed($wasViewed)
    {
        $this->wasViewed = $wasViewed;
    }

    /**
     * @return boolean
     */
    public function getWasViewed()
    {
        return $this->wasViewed;
    }

    /**
     * @param string $messageType
     */
    public function setMessageType($messageType)
    {
        $this->messageType = $messageType;
    }

    /**
     * @return string
     */
    public function getMessageType()
    {
        return $this->messageType;
    }
}
