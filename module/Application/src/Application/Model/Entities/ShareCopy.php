<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * ShareCopy
 *
 * @ORM\Table(name="`share_copy`")
 * @ORM\Entity
 */
class ShareCopy extends BasicObject {

    const FACEBOOK_ENGINE = 'Facebook';
    const TWITTER_ENGINE = 'Twitter';

    const PRE_MATCH_REPORT = 'PreMatchReport';
    const POST_MATCH_REPORT = 'PostMatchReport';

    /**
     * @var string
     *
     * @ORM\Column(name="engine", type="string", columnDefinition="ENUM('Facebook', 'Twitter')")
     */
    protected $engine;

    /**
     * @var string
     *
     * @ORM\Column(name="target", type="string", columnDefinition="ENUM('PreMatchReport', 'PostMatchReport')")
     */
    protected $target;

    /**
     * @var string
     *
     * @ORM\Column(name="copy", type="string")
     */
    protected $copy;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @param string $copy
     */
    public function setCopy($copy)
    {
        $this->copy = $copy;
    }

    /**
     * @return string
     */
    public function getCopy()
    {
        return $this->copy;
    }

    /**
     * @param string $engine
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }

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
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

}