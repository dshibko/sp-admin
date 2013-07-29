<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prize
 *
 * @ORM\Table(name="league_language")
 * @ORM\Entity
 */
class LeagueLanguage
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var League
     *
     * @ORM\ManyToOne(targetEntity="League")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="league_id", referencedColumnName="id")
     * })
     */
    private $league;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set league
     *
     * @param League $league
     * @return LeagueLanguage
     */
    public function setLeague(League $league = null)
    {
        $this->league = $league;
    
        return $this;
    }

    /**
     * Get league
     *
     * @return League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=255, nullable=false)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="prize_title", type="string", length=50, nullable=false)
     */
    private $prizeTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="prize_description", type="text", nullable=false)
     */
    private $prizeDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="prize_image", type="string", length=255, nullable=false)
     */
    private $prizeImage;

    /**
     * @var string
     *
     * @ORM\Column(name="post_win_title", type="string", length=50, nullable=false)
     */
    private $postWinTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="post_win_description", type="text", nullable=false)
     */
    private $postWinDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="post_win_image", type="string", length=255, nullable=false)
     */
    private $postWinImage;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $language;


    /**
     * Set prizeTitle
     *
     * @param string $prizeTitle
     * @return LeagueLanguage
     */
    public function setPrizeTitle($prizeTitle)
    {
        $this->prizeTitle = $prizeTitle;
    
        return $this;
    }

    /**
     * Get prizeTitle
     *
     * @return string 
     */
    public function getPrizeTitle()
    {
        return $this->prizeTitle;
    }

    /**
     * Set prizeDescription
     *
     * @param string $prizeDescription
     * @return LeagueLanguage
     */
    public function setPrizeDescription($prizeDescription)
    {
        $this->prizeDescription = $prizeDescription;
    
        return $this;
    }

    /**
     * Get prizeDescription
     *
     * @return string 
     */
    public function getPrizeDescription()
    {
        return $this->prizeDescription;
    }

    /**
     * Set prizeImage
     *
     * @param string $prizeImage
     * @return LeagueLanguage
     */
    public function setPrizeImage($prizeImage)
    {
        $this->prizeImage = $prizeImage;
    
        return $this;
    }

    /**
     * Get prizeImage
     *
     * @return string 
     */
    public function getPrizeImage()
    {
        return $this->prizeImage;
    }

    /**
     * Set postWinTitle
     *
     * @param string $postWinTitle
     * @return LeagueLanguage
     */
    public function setPostWinTitle($postWinTitle)
    {
        $this->postWinTitle = $postWinTitle;
    
        return $this;
    }

    /**
     * Get postWinTitle
     *
     * @return string 
     */
    public function getPostWinTitle()
    {
        return $this->postWinTitle;
    }

    /**
     * Set postWinDescription
     *
     * @param string $postWinDescription
     * @return LeagueLanguage
     */
    public function setPostWinDescription($postWinDescription)
    {
        $this->postWinDescription = $postWinDescription;
    
        return $this;
    }

    /**
     * Get postWinDescription
     *
     * @return string 
     */
    public function getPostWinDescription()
    {
        return $this->postWinDescription;
    }

    /**
     * Set postWinImage
     *
     * @param string $postWinImage
     * @return LeagueLanguage
     */
    public function setPostWinImage($postWinImage)
    {
        $this->postWinImage = $postWinImage;
    
        return $this;
    }

    /**
     * Get postWinImage
     *
     * @return string 
     */
    public function getPostWinImage()
    {
        return $this->postWinImage;
    }

    /**
     * Set language
     *
     * @param Language $language
     * @return LeagueLanguage
     */
    public function setLanguage(Language $language = null)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

}