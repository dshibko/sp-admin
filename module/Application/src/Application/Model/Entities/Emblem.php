<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Emblem
 *
 * @ORM\Table(name="emblem")
 * @ORM\Entity
 */
class Emblem extends BasicObject {

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
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    protected $path;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Logotype", mappedBy="emblem", cascade={"remove"}, orphanRemoval=true)
     */
    protected $logotypes;

    /**
     * @param Logotype $logotype
     * @return $this
     */
    public function addLogotype(Logotype $logotype)
    {
        $this->logotypes[] = $logotype;
        return $this;
    }


    /**
     * @param Logotype $logotype
     * @return $this
     */
    public function removeLogotype(Logotype $logotype)
    {
        $this->logotypes->removeElement($logotype);
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogotypes()
    {
        return $this->logotypes;
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

    /**
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


}
