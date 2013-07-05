<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Feed
 *
 * @ORM\Table(name="`feed`")
 * @ORM\Entity
 */
class Feed extends BasicObject {

    const F1_TYPE = 'F1';
    const F2_TYPE = 'F2';
    const F7_TYPE = 'F7';
    const F40_TYPE = 'F40';

    const IN_PROGRESS_RESULT = 'InProgress';
    const SUCCESS_RESULT = 'Success';
    const ERROR_RESULT = 'Error';

    /**
     * @static
     * @return array
     */
    public static function getAvailableTypes() {
        return array(self::F1_TYPE, self::F2_TYPE, self::F7_TYPE, self::F40_TYPE);
    }

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string")
     */
    protected $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", columnDefinition="ENUM('F1', 'F2', 'F7', 'F40')")
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="last_sync_result", type="string", columnDefinition="ENUM('Success', 'Error', 'InProgress')")
     */
    protected $lastSyncResult;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update", type="datetime")
     */
    protected $lastUpdate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
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
     * @param string $lastSyncResult
     */
    public function setLastSyncResult($lastSyncResult)
    {
        $this->lastSyncResult = $lastSyncResult;
    }

    /**
     * @return string
     */
    public function getLastSyncResult()
    {
        return $this->lastSyncResult;
    }

    /**
     * @param \DateTime $lastUpdateDate
     */
    public function setLastUpdate($lastUpdateDate)
    {
        $this->lastUpdate = $lastUpdateDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
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

}
