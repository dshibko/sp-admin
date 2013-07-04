<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * DefaultReportContent
 *
 * @ORM\Table(name="`default_report_content`")
 * @ORM\Entity
 */
class DefaultReportContent extends BasicObject {

    const PRE_MATCH_TYPE = 'Pre-Match';
    const POST_MATCH_TYPE = 'Post-Match';

    /**
     * @var string
     *
     * @ORM\Column(name="report_type", type="string", columnDefinition="ENUM('Pre-Match', 'Post-Match')")
     */
    protected $reportType;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="intro", type="string")
     */
    protected $intro;

    /**
     * @var string
     *
     * @ORM\Column(name="header_image", type="string")
     */
    protected $headerImage;

    /**
     * @var Region
     *
     * @ORM\OneToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    protected $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

//    public function populate(array $data = array()) {
//
//        if (isset($data['title']))
//            $this->setTitle($data['title']);
//
//        if (isset($data['description']))
//            $this->setDescription($data['description']);
//
//        if (isset($data['iconPath']))
//            $this->setIconPath($data['iconPath']);
//
//    }


    /**
     * @param string $headerImage
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = $headerImage;
    }

    /**
     * @return string
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
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
     * @param string $intro
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
    }

    /**
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
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
     * @param string $reportType
     */
    public function setReportType($reportType)
    {
        $this->reportType = $reportType;
    }

    /**
     * @return string
     */
    public function getReportType()
    {
        return $this->reportType;
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

}