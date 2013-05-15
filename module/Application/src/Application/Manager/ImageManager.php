<?php

namespace Application\Manager;

use \Application\Model\Helpers\MessagesConstants;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class ImageManager extends BasicManager {

    const IMAGE_TYPE_AVATAR = 'avatar';
    const IMAGE_TYPE_LEAGUE = 'league';
    const IMAGE_TYPE_OTHER = 'other';

    const IMAGES_DIR_PATH = '/img/';

    const WEB_SEPARATOR = '/';

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

    private function getAppPublicPath() {
        return getcwd() . DIRECTORY_SEPARATOR . "public";
    }

}