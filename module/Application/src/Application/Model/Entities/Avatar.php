<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Avatar
 *
 * @ORM\Table(name="avatar")
 * @ORM\Entity
 */
class Avatar extends BasicObject {
    /**
     * @var string
     *
     * @ORM\Column(name="original_image_path", type="string", length=255, nullable=false)
     */
    private $originalImagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="big_image_path", type="string", length=255, nullable=false)
     */
    private $bigImagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="medium_image_path", type="string", length=255, nullable=false)
     */
    private $mediumImagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="small_image_path", type="string", length=255, nullable=false)
     */
    private $smallImagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="tiny_image_path", type="string", length=255, nullable=false)
     */
    private $tinyImagePath;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=true)
     */
    private $isDefault;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * Set originalImagePath
     *
     * @param string $originalImagePath
     * @return Avatar
     */
    public function setOriginalImagePath($originalImagePath)
    {
        $this->originalImagePath = $originalImagePath;
    
        return $this;
    }

    /**
     * Get originalImagePath
     *
     * @return string 
     */
    public function getOriginalImagePath()
    {
        return $this->originalImagePath;
    }

    /**
     * Set bigImagePath
     *
     * @param string $bigImagePath
     * @return Avatar
     */
    public function setBigImagePath($bigImagePath)
    {
        $this->bigImagePath = $bigImagePath;
    
        return $this;
    }

    /**
     * Get bigImagePath
     *
     * @return string 
     */
    public function getBigImagePath()
    {
        return $this->bigImagePath;
    }

    /**
     * Set mediumImagePath
     *
     * @param string $mediumImagePath
     * @return Avatar
     */
    public function setMediumImagePath($mediumImagePath)
    {
        $this->mediumImagePath = $mediumImagePath;
    
        return $this;
    }

    /**
     * Get mediumImagePath
     *
     * @return string 
     */
    public function getMediumImagePath()
    {
        return $this->mediumImagePath;
    }

    /**
     * Set smallImagePath
     *
     * @param string $smallImagePath
     * @return Avatar
     */
    public function setSmallImagePath($smallImagePath)
    {
        $this->smallImagePath = $smallImagePath;
    
        return $this;
    }

    /**
     * Get smallImagePath
     *
     * @return string 
     */
    public function getSmallImagePath()
    {
        return $this->smallImagePath;
    }

    /**
     * Set tinyImagePath
     *
     * @param string $tinyImagePath
     * @return Avatar
     */
    public function setTinyImagePath($tinyImagePath)
    {
        $this->tinyImagePath = $tinyImagePath;
    
        return $this;
    }

    /**
     * Get tinyImagePath
     *
     * @return string 
     */
    public function getTinyImagePath()
    {
        return $this->tinyImagePath;
    }

    /**
     * Set isDefault
     *
     * @param boolean $isDefault
     * @return Avatar
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    
        return $this;
    }

    /**
     * Get isDefault
     *
     * @return boolean 
     */
    public function getIsDefault()
    {
        return $this->isDefault;
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
