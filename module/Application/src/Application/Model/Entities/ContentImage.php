<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContentImage
 *
 * @ORM\Table(name="content_image")
 * @ORM\Entity
 */
class ContentImage extends BasicObject {

    /**
     * @var string
     *
     * @ORM\Column(name="width1280", type="string", length=255)
     */
    private $width1280;

    /**
     * @var string
     *
     * @ORM\Column(name="width1024", type="string", length=255)
     */
    private $width1024;

    /**
     * @var string
     *
     * @ORM\Column(name="width600", type="string", length=255)
     */
    private $width600;

    /**
     * @var string
     *
     * @ORM\Column(name="width480", type="string", length=255)
     */
    private $width480;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @param string $width1024
     */
    public function setWidth1024($width1024)
    {
        $this->width1024 = $width1024;
    }

    /**
     * @return string
     */
    public function getWidth1024()
    {
        return $this->width1024;
    }

    /**
     * @param string $width1280
     */
    public function setWidth1280($width1280)
    {
        $this->width1280 = $width1280;
    }

    /**
     * @return string
     */
    public function getWidth1280()
    {
        return $this->width1280;
    }

    /**
     * @param string $width480
     */
    public function setWidth480($width480)
    {
        $this->width480 = $width480;
    }

    /**
     * @return string
     */
    public function getWidth480()
    {
        return $this->width480;
    }

    /**
     * @param string $width600
     */
    public function setWidth600($width600)
    {
        $this->width600 = $width600;
    }

    /**
     * @return string
     */
    public function getWidth600()
    {
        return $this->width600;
    }
}
