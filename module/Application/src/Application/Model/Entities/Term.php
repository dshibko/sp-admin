<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Logotype
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
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

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
     * @var string
     *
     * @ORM\Column(name="copy", type="text", nullable=false)
     */
    protected $copy;

    /**
     * @param string $copy
     * @return $this
     */
    public function setCopy($copy)
    {
        $this->copy = $copy;
        return $this;
    }

    /**
     * @return string
     */
    public function getCopy()
    {
        return $this->copy;
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
     * @param \Application\Model\Entities\Language $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }


}
