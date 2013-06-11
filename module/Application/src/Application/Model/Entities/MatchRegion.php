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
     * @var FeaturedPlayer
     *
     * @ORM\OneToOne(targetEntity="FeaturedPlayer", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="featured_player_id", referencedColumnName="id")
     * })
     */
    private $featuredPlayer;

    /**
     * @var FeaturedGoalkeeper
     *
     * @ORM\OneToOne(targetEntity="FeaturedGoalkeeper", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="featured_goalkeeper_id", referencedColumnName="id")
     * })
     */
    private $featuredGoalKeeper;

    /**
     * @var FeaturedPrediction
     *
     * @ORM\OneToOne(targetEntity="FeaturedPrediction", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="featured_prediction_id", referencedColumnName="id")
     * })
     */
    private $featuredPrediction;

    /**
     * @var bool
     *
     * @ORM\Column(name="display_featured_player", type="boolean")
     */
    private $displayFeaturedPlayer;

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
     * @param \Application\Model\Entities\FeaturedPlayer $featuredPlayer
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setFeaturedPlayer($featuredPlayer)
    {
        $this->featuredPlayer = $featuredPlayer;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\FeaturedPlayer
     */
    public function getFeaturedPlayer()
    {
        return $this->featuredPlayer;
    }

    /**
     * @param \Application\Model\Entities\FeaturedGoalkeeper $featuredGoalKeeper
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setFeaturedGoalKeeper($featuredGoalKeeper)
    {
        $this->featuredGoalKeeper = $featuredGoalKeeper;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\FeaturedGoalkeeper
     */
    public function getFeaturedGoalKeeper()
    {
        return $this->featuredGoalKeeper;
    }

    /**
     * @param \Application\Model\Entities\FeaturedPrediction $featuredPrediction
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setFeaturedPrediction($featuredPrediction)
    {
        $this->featuredPrediction = $featuredPrediction;
        return $this;
    }

    /**
     * @return \Application\Model\Entities\FeaturedPrediction
     */
    public function getFeaturedPrediction()
    {
        return $this->featuredPrediction;
    }

    /**
     * @param boolean $displayFeaturedPlayer
     * @return \Application\Model\Entities\MatchRegion
     */
    public function setDisplayFeaturedPlayer($displayFeaturedPlayer)
    {
        $this->displayFeaturedPlayer = $displayFeaturedPlayer;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getDisplayFeaturedPlayer()
    {
        return $this->displayFeaturedPlayer;
    }
}
