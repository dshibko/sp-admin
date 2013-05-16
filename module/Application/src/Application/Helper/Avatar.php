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

class Avatar
{
    const IMAGE_MAX_SIZE = '2MB';

    protected $data;
    protected $errorMessages = array();
    protected $adapter;
    protected $path;
    protected $height = 72;
    protected $width = 72;
    protected $default_avatar;
    protected $use_default = false;

    function __construct(File $data, ServiceLocatorInterface $serviceLocator)
    {
        $this->setData($data)->setAdapter(new Http())->setServiceLocator($serviceLocator);
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator (ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
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

    public function getDefaultAvatar()
    {
        return $this->default_avatar;
    }

    public function setDefaultAvatar($avatar)
    {
        $this->default_avatar = $avatar;
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
            if (!$this->getDefaultAvatar()) {
                $this->addErrorMsg('Select default image or upload your own');
                return false;
            }
            $this->setPath($this->getDefaultAvatar())->setUseDefault(true);
        }

        return true;
    }

    public function save()
    {
        if (!$this->getUseDefault()) {
            $path = ImageManager::getInstance($this->getServiceLocator())->saveUploadedImage($this->getData(), 'avatar/small');
            $this->setPath($path);
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