<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * TermCopy
 *
 * @ORM\Table(name="term_copy")
 * @ORM\Entity
 */
class TermCopy extends BasicObject {

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
     * @var Term
     *
     * @ORM\ManyToOne(targetEntity="Term")
     * @ORM\JoinColumn(name="term_id", referencedColumnName="id")
     */
    protected $term;

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
     * @param \Application\Model\Entities\Language $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param \Application\Model\Entities\Term $term
     * @return $this
     */
    public function setTerm($term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Term
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @return \Application\Model\Entities\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }


}
