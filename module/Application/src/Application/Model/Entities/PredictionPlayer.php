<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * PredictionPlayer
 *
 * @ORM\Table(name="prediction_player")
 * @ORM\Entity
 */
class PredictionPlayer extends BasicObject {

    /**
     * @var boolean
     *
     * @ORM\Column(name="order", type="boolean", nullable=false)
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;

    /**
     * @var Prediction
     *
     * @ORM\ManyToOne(targetEntity="Prediction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prediction_id", referencedColumnName="id")
     * })
     */
    private $prediction;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     * })
     */
    private $player;


    /**
     * Set order
     *
     * @param boolean $order
     * @return PredictionPlayer
     */
    public function setOrder($order)
    {
        $this->order = $order;
    
        return $this;
    }

    /**
     * Get order
     *
     * @return boolean 
     */
    public function getOrder()
    {
        return $this->order;
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
     * Set team
     *
     * @param Team $team
     * @return PredictionPlayer
     */
    public function setTeam(Team $team = null)
    {
        $this->team = $team;
    
        return $this;
    }

    /**
     * Get team
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set prediction
     *
     * @param Prediction $prediction
     * @return PredictionPlayer
     */
    public function setPrediction(Prediction $prediction = null)
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
     * Set player
     *
     * @param Player $player
     * @return PredictionPlayer
     */
    public function setPlayer(Player $player = null)
    {
        $this->player = $player;
    
        return $this;
    }

    /**
     * Get player
     *
     * @return \Player 
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
