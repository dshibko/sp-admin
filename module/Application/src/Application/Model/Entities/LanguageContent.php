<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * LanguageContent
 *
 * @ORM\Table(name="language_content")
 * @ORM\Entity
 */
class LanguageContent extends BasicObject
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
     * @var Language
     *
     * @ORM\OneToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    protected $language;

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
     * @param \Application\Model\Entities\Language $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return \Application\Model\Entities\Language
     */
    public function getLanguage()
    {
        return $this->language;
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