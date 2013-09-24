<?php

namespace Application\Manager;

use \Application\Model\Entities\AchievementBlock;
use Application\Model\Entities\Season;
use \Application\Model\Entities\ShareCopy;
use \Application\Model\DAOs\AchievementBlockDAO;
use \Application\Model\DAOs\ShareCopyDAO;
use \Application\Model\Entities\User;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;

class ShareManager extends BasicManager
{

    const FIRST_PREDICTION_WEIGHT = 1;
    const PREDICTION_MILESTONE_WEIGHT = 2;
    const ACHIEVEMENT_WEIGHT = 4;
    const PREDICTION_WEIGHT = 5;

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
     * @param Season $season
     * @param \Application\Model\Entities\User $user
     * @param array $currentMatch
     * @return AchievementBlock
     */
    public function getAchievementBlock($season, $user, $currentMatch) {
        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());

        $achievementBlock = null;

        // weight #10
        if ($currentMatch['prediction']['isCorrectScore'])
            return $this->getAchievementBlockByType(AchievementBlock::CORRECT_SCORE_TYPE);

        // weight #3
        if ($currentMatch['prediction']['isCorrectResult']) {
            $hasThreeConsecutiveWinsInSeason = $predictionManager->getConsecutiveWinsInSeason($season, $user, $currentMatch['startTime']);
            if ($hasThreeConsecutiveWinsInSeason % 3 == 2)
                return $this->getAchievementBlockByType(AchievementBlock::PERFECT_PREDICTION_TYPE);
        }

        // weight #1

        $firstCorrectScorer = false;
        if ($currentMatch['prediction']['correctScorers'] > 0)
            $firstCorrectScorer = $predictionManager->getUserCorrectScorerPredictionsNumber($season, $user, $currentMatch['startTime']) == 0;

        $firstCorrectResult = false;
        if ($currentMatch['prediction']['isCorrectResult'])
            $firstCorrectResult = $predictionManager->hasUserCorrectResults($season, $user, $currentMatch['startTime']) === false;

        if ($firstCorrectResult && $firstCorrectScorer) {
            $bool = rand(0, 1) == 1;
            $firstCorrectResult = $bool;
            $firstCorrectScorer = !$bool;
        }

        if ($firstCorrectResult || $firstCorrectScorer) {
            if ($firstCorrectResult)
                $achievementBlock = $this->getAchievementBlockByType(AchievementBlock::CORRECT_RESULT_TYPE);
            if ($firstCorrectScorer)
                $achievementBlock = $this->getAchievementBlockByType(AchievementBlock::CORRECT_SCORER_TYPE);
        }
        return $achievementBlock;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getPredictionCopy($user) {
        $predictionManager = PredictionManager::getInstance($this->getServiceLocator());

        $numberOfPredictions = $predictionManager->getAllUserPredictionsNumber($user);

        // weight 2
        $milestones = array(10, 25, 50, 100, 200, 500);
        if (in_array($numberOfPredictions, $milestones))
            return $this->getPredictionMilestoneCopy($numberOfPredictions);

        // weight 1
        if ($numberOfPredictions == 1)
            return $this->getFirstPredictionCopy();

        // weight 3
        return $this->getRandomEveryPredictionCopy();

    }

    /**
     * @return array
     */
    public function getFirstPredictionCopy()
    {
        $facebookCopy = ShareCopyDAO::getInstance($this->getServiceLocator())->getPredictionCopy(ShareCopy::FACEBOOK_ENGINE, self::FIRST_PREDICTION_WEIGHT);
        $twitterCopy = ShareCopyDAO::getInstance($this->getServiceLocator())->getPredictionCopy(ShareCopy::TWITTER_ENGINE, self::FIRST_PREDICTION_WEIGHT);
        return array($facebookCopy, $twitterCopy);
    }

    public function getSharingPredictionCopy()
    {
        $facebookCopy = ShareCopyDAO::getInstance($this->getServiceLocator())->getPredictionCopy(ShareCopy::FACEBOOK_ENGINE, self::PREDICTION_WEIGHT);
        $twitterCopy = ShareCopyDAO::getInstance($this->getServiceLocator())->getPredictionCopy(ShareCopy::TWITTER_ENGINE, self::PREDICTION_WEIGHT);
        return array($facebookCopy, $twitterCopy);
    }

    public function getSharingAchievementCopy()
    {
        $facebookCopy = ShareCopyDAO::getInstance($this->getServiceLocator())->getAchievementCopy(ShareCopy::FACEBOOK_ENGINE, self::ACHIEVEMENT_WEIGHT);
        $twitterCopy = ShareCopyDAO::getInstance($this->getServiceLocator())->getAchievementCopy(ShareCopy::TWITTER_ENGINE, self::ACHIEVEMENT_WEIGHT);
        return array($facebookCopy, $twitterCopy);
    }

    /**
     * @param int $numberOfPredictions
     * @return array
     */
    public function getPredictionMilestoneCopy($numberOfPredictions)
    {
        $facebookCopy = sprintf(ShareCopyDAO::getInstance($this->getServiceLocator())->getPredictionCopy(ShareCopy::FACEBOOK_ENGINE, self::PREDICTION_MILESTONE_WEIGHT), $numberOfPredictions);
        $twitterCopy = sprintf(ShareCopyDAO::getInstance($this->getServiceLocator())->getPredictionCopy(ShareCopy::TWITTER_ENGINE, self::PREDICTION_MILESTONE_WEIGHT), $numberOfPredictions);
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