<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Logotype
 *
 * @ORM\Table(name="logotype")
 * @ORM\Entity
 */
class Logotype extends BasicObject {

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
     * @ORM\OneToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

    /**
     * @var string
     *
     * @ORM\Column(name="emblem_image_path", type="string", length=255, nullable=false)
     */
    protected $emblem;

    /**
     * @var string
     *
     * @ORM\Column(name="logotype_image_path", type="string", length=255, nullable=false)
     */
    protected $logotype;

    /**
     * @param string $emblem
     * @return $this
     */
    public function setEmblem($emblem)
    {
        $this->emblem = $emblem;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmblem()
    {
        return $this->emblem;
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
     * @return \Application\Model\Entities\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $logotype
     * @return $this
     */
    public function setLogotype($logotype)
    {
        $this->logotype = $logotype;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogotype()
    {
        return $this->logotype;
    }

}
