<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * FooterImage
 *
 * @ORM\Table(name="footer_image")
 * @ORM\Entity
 */
class FooterImage extends BasicObject
{

    /**
     * @var string
     *
     * @ORM\Column(name="footer_image", type="string", length=255)
     */
    protected $footerImage;

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
     * @param string $footerImage
     */
    public function setFooterImage($footerImage)
    {
        $this->footerImage = $footerImage;
    }

    /**
     * @return string
     */
    public function getFooterImage()
    {
        return $this->footerImage;
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

}