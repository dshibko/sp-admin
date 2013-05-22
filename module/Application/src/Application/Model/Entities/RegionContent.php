<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * RegionContent
 *
 * @ORM\Table(name="region_content")
 * @ORM\Entity
 */
class RegionContent extends BasicObject
{

    /**
     * @var string
     *
     * @ORM\Column(name="headline_copy", type="string", length=255)
     */
    protected $headlineCopy;

    /**
     * @var string
     *
     * @ORM\Column(name="register_button_copy", type="string", length=50)
     */
    protected $registerButtonCopy;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Region
     *
     * @ORM\OneToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    protected $region;

    /**
    * @ORM\OneToOne(targetEntity="ContentImage", cascade={"persist", "remove"}, orphanRemoval=true)
    * @ORM\JoinColumn(name="hero_background_image_id", referencedColumnName="id")
    **/
    protected $heroBackgroundImage;

    /**
    * @ORM\OneToOne(targetEntity="ContentImage", cascade={"persist", "remove"}, orphanRemoval=true)
    * @ORM\JoinColumn(name="hero_foreground_image_id", referencedColumnName="id")
    **/
    protected $heroForegroundImage;

    /**
     * @param string $headlineCopy
     */
    public function setHeadlineCopy($headlineCopy)
    {
        $this->headlineCopy = $headlineCopy;
    }

    /**
     * @return string
     */
    public function getHeadlineCopy()
    {
        return $this->headlineCopy;
    }

    public function setHeroBackgroundImage($heroBackgroundImage)
    {
        $this->heroBackgroundImage = $heroBackgroundImage;
    }

    public function getHeroBackgroundImage()
    {
        return $this->heroBackgroundImage;
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
     * @param \Application\Model\Entities\Region $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return \Application\Model\Entities\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $registerButtonCopy
     */
    public function setRegisterButtonCopy($registerButtonCopy)
    {
        $this->registerButtonCopy = $registerButtonCopy;
    }

    /**
     * @return string
     */
    public function getRegisterButtonCopy()
    {
        return $this->registerButtonCopy;
    }

    public function setHeroForegroundImage($heroForegroundImage)
    {
        $this->heroForegroundImage = $heroForegroundImage;
    }

    public function getHeroForegroundImage()
    {
        return $this->heroForegroundImage;
    }
}