<?php

namespace Application\Manager;

use \Application\Model\Entities\ContentImage;
use \Imagine\Gd\Imagine;
use \Imagine\Image\Box;
use \Imagine\Image\ImageInterface;
use \Application\Model\Helpers\MessagesConstants;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class ImageManager extends BasicManager {

    const IMAGE_TYPE_AVATAR = 'avatar';
    const IMAGE_TYPE_LEAGUE = 'league';
    const IMAGE_TYPE_CONTENT = 'content';
    const IMAGE_TYPE_OTHER = 'other';
    const IMAGE_TYPE_CLUB = 'club';
    const IMAGE_TYPE_REPORT = 'report';
    const IMAGE_PLAYER_AVATAR = 'player/avatar';
    const IMAGE_PLAYER_BACKGROUND = 'player/background';
    const IMAGES_DIR_PATH = '/img/';

    const WEB_SEPARATOR = '/';

    const FOOTER_IMAGE_WIDTH = 182;
    const FOOTER_IMAGE_HEIGHT = 206;

    const CLUB_LOGO_SIZE = 110;

    /**
     * @var ImageManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ImageManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ImageManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    public function saveUploadedImage(\Zend\Form\Element\File $imageElement, $type = self::IMAGE_TYPE_OTHER) {

        $fileValue = $imageElement->getValue();

        if ($fileValue['stored'] == 1) return '';

        $appPath = $this->getAppPublicPath();
        $webPath = self::IMAGES_DIR_PATH . $type;
        $localPath = str_replace(self::WEB_SEPARATOR, DIRECTORY_SEPARATOR, $webPath);
        $ext = strpos($fileValue["name"], '.') !== false ? array_pop(explode('.', $fileValue["name"])) : 'jpg';
        $name = uniqid() . '.' . $ext;
        $webPath .= self::WEB_SEPARATOR . $name;
        $localAbsPath = $appPath . $localPath . DIRECTORY_SEPARATOR . $name;

        $uploadResult = move_uploaded_file($fileValue["tmp_name"], $localAbsPath);
        if (!$uploadResult)
            throw new \Exception(MessagesConstants::ERROR_UPLOAD_FAILED);

        return $webPath;

    }

    public static $HERO_BACKGROUND_SIZES = array(1280 => false, 1024 => false, 600 => true, 480 => true);
    public static $HERO_FOREGROUND_SIZES = array(600 => false, 500 => false, 587 => false, 471 => false);
    public static $GAMEPLAY_FOREGROUND_SIZES = array(700 => false, 554 => false, 600 => false, 480 => false);

    public function prepareContentImage($webPath, $sizes) {
        $originalImagePath = $this->getAppPublicPath() . $webPath;
        $imageSize = getimagesize($originalImagePath);
        $imagine = new Imagine();
        $thumbWebPaths = array();
        foreach ($sizes as $size => $crop) {
            $thumbSize = new Box($imageSize[0], $imageSize[1]);
            $image = $imagine->open($originalImagePath);
            $thumbInfo = pathinfo($webPath);
            $name = uniqid() . "." . $thumbInfo["extension"];
            $thumbWebPath = $thumbInfo['dirname'] . self::WEB_SEPARATOR . $name;
            $thumbPath = $this->getAppPublicPath() . str_replace(self::WEB_SEPARATOR, DIRECTORY_SEPARATOR, $thumbWebPath);
            if ($crop) {
                $thumbSize = $thumbSize->widen(max(array_keys($sizes)));
                $thumbSize = new Box($size, $thumbSize->getHeight());
            } else
                $thumbSize = $thumbSize->widen($size);
            $image->thumbnail($thumbSize, $crop ? ImageInterface::THUMBNAIL_OUTBOUND : ImageInterface::THUMBNAIL_INSET)->save($thumbPath);
            $thumbWebPaths[] = $thumbWebPath;
        }
        unlink($originalImagePath);
        $contentImage = new ContentImage();
        $contentImage->setWidth1280($thumbWebPaths[0]);
        $contentImage->setWidth1024($thumbWebPaths[1]);
        $contentImage->setWidth600($thumbWebPaths[2]);
        $contentImage->setWidth480($thumbWebPaths[3]);
        return $contentImage;
    }

    public function resizeImage($webImagePath, $width = null, $height = null) {
        if ($width != null || $height != null) {
            $imagePath = $this->getAppPublicPath() . str_replace(self::WEB_SEPARATOR, DIRECTORY_SEPARATOR, $webImagePath);
            $imageSize = getimagesize($imagePath);
            $imagine = new Imagine();
            $thumbSize = new Box($imageSize[0], $imageSize[1]);
            if ($width != null)
                $thumbSize = $thumbSize->widen($width);
            if ($height != null)
                $thumbSize = $thumbSize->heighten($height);
            $image = $imagine->open($imagePath);
            $image->thumbnail($thumbSize, ImageInterface::THUMBNAIL_INSET)->save($imagePath);
        }
    }

    public function deleteImage($webImagePath) {
        @unlink($this->getAppPublicPath() . str_replace(self::WEB_SEPARATOR, DIRECTORY_SEPARATOR, $webImagePath));
    }

    /**
     * @param \Application\Model\Entities\ContentImage $contentImage
     */
    public function deleteContentImage(ContentImage $contentImage) {
        $this->deleteImage($contentImage->getWidth1280());
        $this->deleteImage($contentImage->getWidth1024());
        $this->deleteImage($contentImage->getWidth600());
        $this->deleteImage($contentImage->getWidth480());
    }

    public function getAppPublicPath() {
        return getcwd() . DIRECTORY_SEPARATOR . "public";
    }

}