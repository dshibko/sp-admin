<?php

namespace Application\Model\Entities;

use \Neoco\Model\BasicObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * Setting
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity
 */
class Setting extends BasicObject {

    /**
     * @var string
     *
     * @ORM\Column(name="setting_key", type="string", length=100, nullable=false)
     */
    private $settingKey;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_value", type="string", length=500, nullable=false)
     */
    private $settingValue;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @param string $settingKey
     */
    public function setSettingKey($settingKey)
    {
        $this->settingKey = $settingKey;
    }

    /**
     * @return string
     */
    public function getSettingKey()
    {
        return $this->settingKey;
    }

    /**
     * @param string $settingValue
     */
    public function setSettingValue($settingValue)
    {
        $this->settingValue = $settingValue;
    }

    /**
     * @return string
     */
    public function getSettingValue()
    {
        return $this->settingValue;
    }
}
