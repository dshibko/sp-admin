<?php

namespace Application\Manager;

use \Application\Model\Entities\RegionGameplayContent;
use \Application\Model\DAOs\RegionGameplayContentDAO;
use \Application\Model\Entities\RegionContent;
use \Application\Model\DAOs\RegionContentDAO;
use \Application\Model\DAOs\MatchDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class ContentManager extends BasicManager {

    /**
     * @var ContentManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ContentManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface) {
        if (self::$instance == null) {
            self::$instance = new ContentManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param $heroBackgroundImage
     * @param $heroForegroundImage
     * @param $headlineCopy
     * @param $registerButtonCopy
     */
    public function saveRegionContent($region, $heroBackgroundImage, $heroForegroundImage, $headlineCopy, $registerButtonCopy) {
        $regionContent = $region->getRegionContent();
        if ($regionContent == null) {
            $regionContent = new RegionContent();
            $regionContent->setRegion($region);
        } else {
            if ($heroBackgroundImage != null)
                ImageManager::getInstance($this->getServiceLocator())->deleteContentImage($regionContent->getHeroBackgroundImage());
            if ($heroForegroundImage != null)
                ImageManager::getInstance($this->getServiceLocator())->deleteContentImage($regionContent->getHeroForegroundImage());
        }
        if ($heroBackgroundImage != null)
            $regionContent->setHeroBackgroundImage($heroBackgroundImage);
        if ($heroForegroundImage != null)
            $regionContent->setHeroForegroundImage($heroForegroundImage);
        $regionContent->setHeadlineCopy($headlineCopy);
        $regionContent->setRegisterButtonCopy($registerButtonCopy);
        RegionContentDAO::getInstance($this->getServiceLocator())->save($regionContent);
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param \Application\Model\Entities\ContentImage $foregroundImage
     * @param $heading
     * @param $description
     * @param $order
     * @param RegionGameplayContent|null $regionGameplayContent
     */
    public function saveRegionGameplayContent($region, $foregroundImage, $heading, $description, $order, $regionGameplayContent = null) {
        if ($regionGameplayContent == null) {
            $regionGameplayContent = new RegionGameplayContent();
            $regionGameplayContent->setRegion($region);
        } elseif ($foregroundImage != null)
            ImageManager::getInstance($this->getServiceLocator())->deleteContentImage($regionGameplayContent->getForegroundImage());
        if ($foregroundImage != null)
            $regionGameplayContent->setForegroundImage($foregroundImage);
        $regionGameplayContent->setHeading($heading);
        $regionGameplayContent->setDescription($description);
        $regionGameplayContent->setOrder($order);
        RegionGameplayContentDAO::getInstance($this->getServiceLocator())->save($regionGameplayContent);
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param $fromOrder
     * @param $lastOrder
     */
    public function swapRegionGameplayContentFromOrder($region, $fromOrder, $lastOrder) {
        $regionGameplayContentDAO = RegionGameplayContentDAO::getInstance($this->getServiceLocator());
        for ($i = $fromOrder; $i < $lastOrder; $i++) {
            $regionGameplayBlock = $region->getRegionGameplayBlockByOrder($i);
            $regionGameplayBlock->setOrder($i + 1);
            $regionGameplayContentDAO->save($regionGameplayBlock, false, false);
        }
        $regionGameplayContentDAO->flush();
        $regionGameplayContentDAO->clearCache();
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param $toOrder
     * @param $lastOrder
     */
    public function swapRegionGameplayContentToOrder($region, $toOrder, $lastOrder) {
        $regionGameplayContentDAO = RegionGameplayContentDAO::getInstance($this->getServiceLocator());
        for ($i = $lastOrder; $i > $toOrder; $i--) {
            $regionGameplayBlock = $region->getRegionGameplayBlockByOrder($i);
            $regionGameplayBlock->setOrder($i - 1);
            $regionGameplayContentDAO->save($regionGameplayBlock, false, false);
        }
        $regionGameplayContentDAO->flush();
        $regionGameplayContentDAO->clearCache();
    }

    /**
     * @param \Application\Model\Entities\RegionGameplayContent $block
     */
    public function deleteRegionGameplayContent(RegionGameplayContent $block) {
        ImageManager::getInstance($this->getServiceLocator())->deleteContentImage($block->getForegroundImage());
        ContentManager::getInstance($this->getServiceLocator())->swapRegionGameplayContentToOrder($block->getRegion(), $block->getOrder(), $block->getRegion()->getRegionGameplayBlocks()->count());
        RegionContentDAO::getInstance($this->getServiceLocator())->remove($block);
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getGameplayBlocks($region, $hydrate = false, $skipCache = false) {
        return RegionGameplayContentDAO::getInstance($this->getServiceLocator())->getRegionGameplayBlocks($region, $hydrate, $skipCache);
    }

}