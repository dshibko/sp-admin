<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * FooterSocial
 *
 * @ORM\Table(name="footer_social")
 * @ORM\Entity
 */
class FooterSocial extends BasicObject
{

    /**
     * @var integer
     *
     * @ORM\Column(name="`order`", type="integer")
     */
    protected $order;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    protected $icon;

    /**
     * @var string
     *
     * @ORM\Column(name="copy", type="string", length=100)
     */
    protected $copy;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=500)
     */
    protected $url;

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
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
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
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

}