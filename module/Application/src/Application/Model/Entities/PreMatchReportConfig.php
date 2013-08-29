<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Neoco\Model\BasicObject;

/**
 * PreMatchReportConfig
 *
 * @ORM\Table(name="pre_match_report_config")
 * @ORM\Entity
 */
class PreMatchReportConfig extends BasicObject
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Match
     *
     * @ORM\OneToOne(targetEntity="Match")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * })
     */

    private $match;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight;

    /**
     * @var integer
     *
     * @ORM\Column(name="display_index", type="integer")
     */
    protected $displayIndex;

    /**
     * @param int $displayIndex
     */
    public function setDisplayIndex($displayIndex)
    {
        $this->displayIndex = $displayIndex;
    }

    /**
     * @return int
     */
    public function getDisplayIndex()
    {
        return $this->displayIndex;
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
