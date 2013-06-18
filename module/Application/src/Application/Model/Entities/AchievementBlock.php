<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * AchievementBlock
 *
 * @ORM\Table(name="`achievement_block`")
 * @ORM\Entity
 */
class AchievementBlock extends BasicObject {

    const CORRECT_RESULT_TYPE = 'First Correct Result';
    const CORRECT_SCORER_TYPE = 'First Correct Scorer';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", columnDefinition="ENUM('First Correct Result', 'First Correct Scorer')")
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_path", type="string")
     */
    protected $iconPath;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ShareCopy", mappedBy="achievementBlock", cascade={"persist"})
     */
    protected $shareCopies;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $iconPath
     */
    public function setIconPath($iconPath)
    {
        $this->iconPath = $iconPath;
    }

    /**
     * @return string
     */
    public function getIconPath()
    {
        return $this->iconPath;
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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $shareCopies
     */
    public function setShareCopies($shareCopies)
    {
        $this->shareCopies = $shareCopies;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShareCopies()
    {
        return $this->shareCopies;
    }

    public function getFacebookShareCopy() {
        return $this->getShareCopies()->filter(function(ShareCopy $shareCopy) {
            return $shareCopy->getEngine() == ShareCopy::FACEBOOK_ENGINE;
        })->first();
    }

    public function getTwitterShareCopy() {
        return $this->getShareCopies()->filter(function(ShareCopy $shareCopy) {
            return $shareCopy->getEngine() == ShareCopy::TWITTER_ENGINE;
        })->first();
    }

    public function populate(array $data = array()) {

        if (isset($data['title']))
            $this->setTitle($data['title']);

        if (isset($data['description']))
            $this->setDescription($data['description']);

        if (isset($data['iconPath']))
            $this->setIconPath($data['iconPath']);

    }

}