<?php

namespace Application\Manager;

use \Application\Model\Entities\AchievementBlock;
use \Application\Model\Entities\ShareCopy;
use \Application\Model\DAOs\AchievementBlockDAO;
use \Application\Model\DAOs\ShareCopyDAO;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class ShareManager extends BasicManager
{

    /**
     * @var ShareManager
     */
    private static $instance;

    /**
     * @static
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocatorInterface
     * @return ShareManager
     */
    public static function getInstance(ServiceLocatorInterface $serviceLocatorInterface)
    {
        if (self::$instance == null) {
            self::$instance = new ShareManager();
            self::$instance->setServiceLocator($serviceLocatorInterface);
        }
        return self::$instance;
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAchievementBlocks($hydrate = false, $skipCache = false)
    {
        return AchievementBlockDAO::getInstance($this->getServiceLocator())->getAchievementBlocks($hydrate, $skipCache);
    }

    /**
     * @param int $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAchievementBlockById($id, $hydrate = false, $skipCache = false)
    {
        return AchievementBlockDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }

    /**
     * @param $type
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getAchievementBlockByType($type, $hydrate = false, $skipCache = false)
    {
        $achievementBlockDAO = AchievementBlockDAO::getInstance($this->getServiceLocator());
        return $achievementBlockDAO->getAchievementBlockByType($type, $hydrate, $skipCache);
    }

    public function saveAchievementBlock($achievementBlock, $flush = true, $clearCache = true) {
        $achievementBlockDAO = AchievementBlockDAO::getInstance($this->getServiceLocator());
        $achievementBlockDAO->save($achievementBlock, $flush, $clearCache);
    }

    /**
     * @return array
     */
    public function getFirstPredictionCopy()
    {
        $facebookCopy = ShareCopyDAO::getInstance($this->getServiceLocator())->getFirstPredictionCopy(ShareCopy::FACEBOOK_ENGINE);
        $twitterCopy = ShareCopyDAO::getInstance($this->getServiceLocator())->getFirstPredictionCopy(ShareCopy::TWITTER_ENGINE);
        return array($facebookCopy, $twitterCopy);
    }

    /**
     * @return array
     */
    public function getRandomEveryPredictionCopy()
    {
        $copies = ShareCopyDAO::getInstance($this->getServiceLocator())->getEveryPredictionNonEmptyCopies(ShareCopy::FACEBOOK_ENGINE, true);
        $copiesIndex = rand(1, count($copies));
        $copy = $copies[$copiesIndex - 1];
        $facebookCopy = $copy['copy'];
        $copies = ShareCopyDAO::getInstance($this->getServiceLocator())->getEveryPredictionNonEmptyCopies(ShareCopy::TWITTER_ENGINE, true);
        $copiesIndex = rand(1, count($copies));
        $copy = $copies[$copiesIndex - 1];
        $twitterCopy = $copy['copy'];
        return array($facebookCopy, $twitterCopy);
    }

    /**
     * @param string $target
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getCopyByTarget($target, $hydrate = false, $skipCache = false)
    {
        return ShareCopyDAO::getInstance($this->getServiceLocator())->getCopyByTarget($target, $hydrate, $skipCache);
    }

    public function saveShareCopy($id, $value, $flush = true, $clearCache = true) {
        $shareCopyDAO = ShareCopyDAO::getInstance($this->getServiceLocator());
        $shareCopy = $shareCopyDAO->findOneById($id);
        if ($shareCopy !== null) {
            $shareCopy->setCopy($value);
            $shareCopyDAO->save($shareCopy, $flush, $clearCache);
        }
    }

    public function flushAndClearCache() {
        $shareCopyDAO = ShareCopyDAO::getInstance($this->getServiceLocator());
        $shareCopyDAO->flush();
        $shareCopyDAO->clearCache();
    }

}