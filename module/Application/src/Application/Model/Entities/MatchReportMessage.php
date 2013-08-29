<?php

namespace Application\Model\Entities;


use Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * MatchReportMessage
 *
 * @ORM\Table(name="match_report_message")
 * @ORM\Entity
 */
class MatchReportMessage extends BasicObject {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Prediction
     *
     * @ORM\OneToOne(targetEntity="Prediction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prediction_id", referencedColumnName="id")
     * })
     */
    private $prediction;

    /**
     * @var Message
     *
     * @ORM\OneToOne(targetEntity="Message")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     * })
     */
    private $message;

    /**
     * Set message
     *
     * @param Message $message
     * @return Message
     */
    public function setMessage(Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set prediction
     *
     * @param Prediction $prediction
     * @return Prediction
     */
    public function setPrediction($prediction)
    {
        $this->prediction = $prediction;

        return $this;
    }

    /**
     * Get prediction
     *
     * @return Prediction
     */
    public function getPrediction()
    {
        return $this->prediction;
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
}
