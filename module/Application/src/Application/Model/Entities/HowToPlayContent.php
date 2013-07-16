<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * HowToPlayContent
 *
 * @ORM\Table(name="howtoplay_content")
 * @ORM\Entity
 */
class HowToPlayContent extends BasicObject
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
     * @ORM\Column(name="heading", type="string", length=255)
     */
    protected $heading;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description;

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
    * @ORM\OneToOne(targetEntity="ContentImage", cascade={"persist", "remove"}, orphanRemoval=true)
    * @ORM\JoinColumn(name="foreground_image_id", referencedColumnName="id")
    **/
    protected $foregroundImage;

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $foregroundImage
     * @return $this
     */
    public function setForegroundImage($foregroundImage)
    {
        $this->foregroundImage = $foregroundImage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForegroundImage()
    {
        return $this->foregroundImage;
    }

    /**
     * @param string  $heading
     * @return $this
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeading()
    {
        return $this->heading;
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
     * @param $language
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

    public function populate(array $data)
    {
        if (!empty($data['id'])){
            $this->setId($data['id']);
        }
        if (array_key_exists('language', $data) && $data['language'] instanceof Language){
            $this->setLanguage($data['language']);
        }
        if (array_key_exists('foregroundImage', $data) && $data['foregroundImage'] instanceof ContentImage){
            $this->setForegroundImage($data['foregroundImage']);
        }
        if (array_key_exists('heading', $data)){
            $this->setHeading($data['heading']);
        }
        if (array_key_exists('description', $data)){
            $this->setDescription($data['description']);
        }
        if (array_key_exists('order', $data)){
            $this->setOrder($data['order']);
        }
    }

}