<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * MatchRegion
 *
 * @ORM\Table(name="match_region")
 * @ORM\Entity
 */
class MatchRegion
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="intro", type="text", nullable=true)
     */
    private $intro;

    /**
     * @var string
     *
     * @ORM\Column(name="header_image_path", type="string", length=255, nullable=true)
     */
    private $headerImagePath;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Match
     *
     * @ORM\ManyToOne(targetEntity="Match")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * })
     */
    private $match;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     * })
     */
    private $region;

    /**
     * @var Featured Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="featured_player_id", referencedColumnName="id")
     * })
     */
    private $featuredPlayer;


    /**
     * @param string $headerImagePath
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setHeaderImagePath($headerImagePath)
    {
        $this->headerImagePath = $headerImagePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderImagePath()
    {
        return $this->headerImagePath;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $intro
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
        return $this;
    }

    /**
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @param \Application\Model\Entities\Match $match
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setMatch($match)
    {
        $this->match = $match;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Match
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $title
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param \Application\Model\Entities\Featured $featuredPlayer
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setFeaturedPlayer($featuredPlayer)
    {
        $this->featuredPlayer = $featuredPlayer;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\Player
     */
    public function getFeaturedPlayer()
    {
        return $this->featuredPlayer;
    }
}
