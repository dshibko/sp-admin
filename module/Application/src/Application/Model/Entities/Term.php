<?php

namespace Application\Model\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Term
 *
 * @ORM\Table(name="term")
 * @ORM\Entity
 */
class Term extends BasicObject {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TermCopy", mappedBy="term", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $termCopies;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_required", type="boolean", nullable=false)
     */
    protected $isRequired = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_checked", type="boolean", nullable=false)
     */
    protected $isChecked = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->termCopies = new ArrayCollection();
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
     * @param boolean $isChecked
     * @return $this
     */
    public function setIsChecked($isChecked)
    {
        $this->isChecked = $isChecked;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsChecked()
    {
        return $this->isChecked;
    }

    /**
     * @param boolean $isRequired
     * @return $this
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $termCopies
     * @return $this
     */
    public function setTermCopies($termCopies)
    {
        $this->termCopies = $termCopies;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTermCopies()
    {
        return $this->termCopies;
    }

    /**
     * Add TermCopy
     *
     * @param TermCopy $termCopy
     * @return $this
     */
    public function addTermCopy(TermCopy $termCopy)
    {
        $this->termCopies[] = $termCopy;
        return $this;
    }


    /**
     * @param TermCopy $termCopy
     * @return $this
     */
    public function removeTermCopy(TermCopy $termCopy)
    {
        $this->termCopies->removeElement($termCopy);
        return $this;
    }

    /**
     * @var array
     */
    private $termCopiesByLanguageId = array();

    /**
     * @param $id
     * @return mixed
     */
    public function getTermCopyByLanguage($id)
    {
        if (!array_key_exists($id, $this->termCopiesByLanguageId)){
            foreach ($this->getTermCopies() as $termCopy){
                if ($termCopy->getLanguage()->getId() == $id) {
                    $this->termCopiesByLanguageId[$id] = $termCopy;
                    break;
                }
            }
        }

        return isset($this->termCopiesByLanguageId[$id]) ? $this->termCopiesByLanguageId[$id] : null;
    }

}
