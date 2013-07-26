<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Neoco\Model\BasicObject;

/**
 * MatchLanguage
 *
 * @ORM\Table(name="match_language")
 * @ORM\Entity
 */
class MatchLanguage extends BasicObject
{
    /**
     * @var string
     *
     * @ORM\Column(name="pre_match_report_title", type="string", length=255, nullable=true)
     */
    protected $preMatchReportTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="pre_match_report_intro", type="text", nullable=true)
     */
    protected $preMatchReportIntro;

    /**
     * @var string
     *
     * @ORM\Column(name="pre_match_report_header_image_path", type="string", length=255, nullable=true)
     */
    protected $preMatchReportHeaderImagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="post_match_report_title", type="string", length=255, nullable=true)
     */
    protected $postMatchReportTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="post_match_report_intro", type="text", nullable=true)
     */
    protected $postMatchReportIntro;

    /**
     * @var string
     *
     * @ORM\Column(name="post_match_report_header_image_path", type="string", length=255, nullable=true)
     */
    protected $postMatchReportHeaderImagePath;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Match
     *
     * @ORM\ManyToOne(targetEntity="Match")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * })
     */
    private $match;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;

    /**
     * @var FeaturedPlayer
     *
     * @ORM\OneToOne(targetEntity="FeaturedPlayer", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="featured_player_id", referencedColumnName="id")
     * })
     */
    private $featuredPlayer;

    /**
     * @var FeaturedGoalkeeper
     *
     * @ORM\OneToOne(targetEntity="FeaturedGoalkeeper", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="featured_goalkeeper_id", referencedColumnName="id")
     * })
     */
    private $featuredGoalKeeper;

    /**
     * @var FeaturedPrediction
     *
     * @ORM\OneToOne(targetEntity="FeaturedPrediction", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="featured_prediction_id", referencedColumnName="id")
     * })
     */
    private $featuredPrediction;

    /**
     * @var bool
     *
     * @ORM\Column(name="display_featured_player", type="boolean")
     */
    protected $displayFeaturedPlayer;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Application\Model\Entities\Match $match
     * @return \Application\Model\Entities\MatchLanguage
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
     * @param \Application\Model\Entities\Language $language
     * @return \Application\Model\Entities\MatchLanguage
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
     * @param \Application\Model\Entities\FeaturedPlayer $featuredPlayer
     * @return \Application\Model\Entities\MatchLanguage
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
     * @return \Application\Model\Entities\MatchLanguage
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
     * @return \Application\Model\Entities\MatchLanguage
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
     * @return \Application\Model\Entities\MatchLanguage
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

    /**
     * @param string $postMatchReportHeaderImagePath
     * @return \Application\Model\Entities\MatchLanguage
     */
    public function setPostMatchReportHeaderImagePath($postMatchReportHeaderImagePath)
    {
        $this->postMatchReportHeaderImagePath = $postMatchReportHeaderImagePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostMatchReportHeaderImagePath()
    {
        return $this->postMatchReportHeaderImagePath;
    }

    /**
     * @param string $postMatchReportIntro
     * @return \Application\Model\Entities\MatchLanguage
     */
    public function setPostMatchReportIntro($postMatchReportIntro)
    {
        $this->postMatchReportIntro = $postMatchReportIntro;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostMatchReportIntro()
    {
        return $this->postMatchReportIntro;
    }

    /**
     * @param string $postMatchReportTitle
     * @return \Application\Model\Entities\MatchLanguage
     */
    public function setPostMatchReportTitle($postMatchReportTitle)
    {
        $this->postMatchReportTitle = $postMatchReportTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostMatchReportTitle()
    {
        return $this->postMatchReportTitle;
    }

    /**
     * @param string $preMatchReportHeaderImagePath
     * @return \Application\Model\Entities\MatchLanguage
     */
    public function setPreMatchReportHeaderImagePath($preMatchReportHeaderImagePath)
    {
        $this->preMatchReportHeaderImagePath = $preMatchReportHeaderImagePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreMatchReportHeaderImagePath()
    {
        return $this->preMatchReportHeaderImagePath;
    }

    /**
     * @param string $preMatchReportIntro
     * @return \Application\Model\Entities\MatchLanguage
     */
    public function setPreMatchReportIntro($preMatchReportIntro)
    {
        $this->preMatchReportIntro = $preMatchReportIntro;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreMatchReportIntro()
    {
        return $this->preMatchReportIntro;
    }

    /**
     * @param string $preMatchReportTitle
     * @return \Application\Model\Entities\MatchLanguage
     */
    public function setPreMatchReportTitle($preMatchReportTitle)
    {
        $this->preMatchReportTitle = $preMatchReportTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreMatchReportTitle()
    {
        return $this->preMatchReportTitle;
    }
}
