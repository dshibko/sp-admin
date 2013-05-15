<?php

namespace Application\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prize
 *
 * @ORM\Table(name="prize")
 * @ORM\Entity
 */
class Prize
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
     * @return Prize
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
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     * })
     */
    private $region;


    /**
     * Set prizeTitle
     *
     * @param string $prizeTitle
     * @return Prize
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
     * @return Prize
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
     * @return Prize
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
     * @return Prize
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
     * @return Prize
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
     * @return Prize
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
     * Set region
     *
     * @param Region $region
     * @return Prize
     */
    public function setRegion(Region $region = null)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }
}