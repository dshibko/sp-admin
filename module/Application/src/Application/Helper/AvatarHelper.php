<?php
namespace Application\Helper;

use Zend\Validator\File\Size;
use Zend\Validator\File\IsImage;
use Zend\File\Transfer\Adapter\Http;
use Zend\Form\Element\File as File;
use Application\Manager\ImageManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Application\Model\Entities\Avatar as EntityAvatar;
use Application\Model\DAOs\AvatarDAO;

class AvatarHelper
{
    const IMAGE_MAX_SIZE = '2MB';
    const DEFAULT_AVATAR_ID = 1;
    /**
     *   @var File
    **/
    protected $data;
    protected $errorMessages = array();
    protected $adapter;
    protected $path;
    protected $height = 72;
    protected $width = 72;
    protected $defaultAvatarId;
    protected $use_default = false;
    protected $avatar;

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

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getPath()
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

    public function populate()
    {
        $avatarData = array(
            'original_image_path' => $this->getPath(),
            'big_image_path' => $this->getPath(),
            'medium_image_path' => $this->getPath(),
            'small_image_path' => $this->getPath(),
            'tiny_image_path' => $this->getPath()
        );

        $avatar = new EntityAvatar();
        $avatar->populate($avatarData);
        $this->setAvatar($avatar);
        return $this;
    }
    public function validate()
    {
        $data = $this->getData()->getValue();

        if ($data['error'] != UPLOAD_ERR_NO_FILE) { //User upload avatar
            $size = new Size(array(
                'max' => self::IMAGE_MAX_SIZE
            ));

            $isImage = new IsImage(array());
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
            $path = ImageManager::getInstance($this->getServiceLocator())->saveUploadedImage($this->getData(), 'avatar/small');
            $this->setPath($path)->populate();
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
            $imagine = new Imagine();
            $size = new Box($this->getWidth(), $this->getHeight());
            $file = ImageManager::getInstance($this->getServiceLocator())->getAppPublicPath() . $this->getPath();
            $image = $imagine->open($file);
            $image->thumbnail($size, ImageInterface::THUMBNAIL_INSET)->save($file);
        }
        return $this;
    }
}