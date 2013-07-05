<?php
namespace Application\Helper;

use \Application\Model\Entities\Avatar;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Zend\Validator\File\IsImage;
use Zend\File\Transfer\Adapter\Http;
use Zend\Form\Element\File as File;
use Application\Manager\ImageManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\Entities\Avatar as EntityAvatar;
use Application\Model\DAOs\AvatarDAO;

class AvatarHelper
{
    const IMAGE_MAX_SIZE = '2MB';
    const DEFAULT_AVATAR_ID = 1;

    const BIG_IMAGE_SIZE = 100;
    const MEDIUM_IMAGE_SIZE = 79;
    const SMALL_IMAGE_SIZE = 50;
    const TINY_IMAGE_SIZE = 29;

    const ORIGINAL_IMAGE_DIR = 'original';
    const BIG_IMAGE_DIR = 'big';
    const MEDIUM_IMAGE_DIR = 'medium';
    const SMALL_IMAGE_DIR = 'small';
    const TINY_IMAGE_DIR = 'tiny';

    /**
     *   @var File
    **/
    protected $data;
    protected $errorMessages = array();
    protected $adapter;
    protected $path;
    protected $defaultAvatarId;
    protected $use_default = false;
    protected $avatar;
    protected $serviceLocator;

    function __construct(File $data = null, ServiceLocatorInterface $serviceLocator = null)
    {
        if (!is_null($data)){
            $this->setData($data);
        }
        if (!is_null($serviceLocator)){
            $this->setServiceLocator($serviceLocator);
        }
        $this->setAdapter(new Http());
    }

    public function setAvatar(EntityAvatar $avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function setUseDefault($use_default)
    {
        $this->use_default = $use_default;
        return $this;
    }

    public function getUseDefault()
    {
        return $this->use_default;
    }

    public function addErrorMsg($msg)
    {
        $this->errorMessages[] = $msg;
        return $this;
    }

    public function getDefaultAvatarId()
    {
        return $this->defaultAvatarId;
    }

    public function setDefaultAvatarId($avatar)
    {
        $this->defaultAvatarId = $avatar;
        return $this;
    }

    public function getOriginalPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function setAdapter(Http $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    public function setData(File $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function validate()
    {
        $data = $this->getData()->getValue();

        if ($data['error'] != UPLOAD_ERR_NO_FILE) { //User upload avatar
            $size = new Size(array(
                'max' => self::IMAGE_MAX_SIZE
            ));

            $isImage = new Extension(array(
                array('extension' => 'jpg,jpeg,gif,png,bmp')
            ));
            $this->getAdapter()->setValidators(array($size, $isImage), $data['name']);
            $this->getAdapter()->setFilters(array());
            if (!$this->getAdapter()->isValid()) {
                $dataError = $this->getAdapter()->getMessages();
                foreach ($dataError as $row)
                {
                    $this->addErrorMsg($row);
                }
                return false;
            }

        } else { //Use default instead
            if (!$this->getDefaultAvatarId()) {
                $this->addErrorMsg('Select default image or upload your own');
                return false;
            }
            $this->setUseDefault(true);
        }

        return true;
    }

    public function save()
    {
        if (!$this->getUseDefault()) { //Get upload avatar
            $path = ImageManager::getInstance($this->getServiceLocator())->saveUploadedImage($this->getData(), ImageManager::IMAGE_TYPE_AVATAR . ImageManager::WEB_SEPARATOR . self::ORIGINAL_IMAGE_DIR);
            $this->setPath($path);
        } else {   //Get default avatar
            $avatar_id = ($this->getDefaultAvatarId()) ? $this->getDefaultAvatarId() : self::DEFAULT_AVATAR_ID;
            $avatar = AvatarDAO::getInstance($this->getServiceLocator())->findOneById($avatar_id);
            $this->setAvatar($avatar);
        }

        return $this;
    }

    public function resize()
    {
        if (!$this->getUseDefault()){
            $imageManager = ImageManager::getInstance($this->getServiceLocator());
            $sizes = array(
                self::BIG_IMAGE_DIR => self::BIG_IMAGE_SIZE,
                self::MEDIUM_IMAGE_DIR => self::MEDIUM_IMAGE_SIZE,
                self::SMALL_IMAGE_DIR => self::SMALL_IMAGE_SIZE,
                self::TINY_IMAGE_DIR => self::TINY_IMAGE_SIZE,
            );
            $data = array('original_image_path' => $this->getOriginalPath());
            foreach ($sizes as $dir => $size) {
                $imagePath = ImageManager::IMAGES_DIR_PATH . ImageManager::IMAGE_TYPE_AVATAR . ImageManager::WEB_SEPARATOR . $dir . ImageManager::WEB_SEPARATOR;
                $thumbInfo = pathinfo($this->getOriginalPath());
                $imagePath .= uniqid() . "." . $thumbInfo["extension"];
                $imageManager->resizeImage($this->getOriginalPath(), $size, $size, $imagePath);
                $data[$dir . '_image_path'] = $imagePath;
            }
            $avatar = new Avatar();
            $avatar->populate($data);
            $this->setAvatar($avatar);
        }
        return $this;
    }
}