<?php

namespace Application\Manager;

use \Application\Model\DAOs\DefaultReportContentDAO;
use Application\Model\DAOs\EmblemDAO;
use Application\Model\DAOs\LogotypeDAO;
use Application\Model\DAOs\TermCopyDAO;
use Application\Model\DAOs\TermDAO;
use Application\Model\Entities\Emblem;
use \Application\Model\Entities\FooterSocial;
use \Application\Model\DAOs\FooterSocialDAO;
use \Application\Model\Entities\FooterImage;
use \Application\Model\DAOs\FooterImageDAO;
use Application\Model\Entities\Logotype;
use \Application\Model\Entities\RegionGameplayContent;
use \Application\Model\DAOs\RegionGameplayContentDAO;
use \Application\Model\Entities\RegionContent;
use \Application\Model\DAOs\RegionContentDAO;
use \Application\Model\DAOs\MatchDAO;
use Application\Model\Entities\Term;
use Application\Model\Entities\TermCopy;
use Zend\ServiceManager\ServiceLocatorInterface;
use \Neoco\Manager\BasicManager;
use \Application\Model\Entities\FooterPage;
use \Application\Model\DAOs\FooterPageDAO;

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
        $regionContent = ContentManager::getInstance($this->getServiceLocator())->getRegionContent($region);
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
     * @param $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\RegionContent|array
     */
    public function getRegionContent($region, $hydrate = false, $skipCache = false) {
        return RegionContentDAO::getInstance($this->getServiceLocator())->getRegionContent($region, $hydrate, $skipCache);
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

    /**
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getFooterImages($region, $hydrate = false, $skipCache = false) {
        return FooterImageDAO::getInstance($this->getServiceLocator())->getFooterImages($region, $hydrate, $skipCache);
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param string $footerImagePath
     */
    public function addFooterImage($region, $footerImagePath) {
        $imageManager = ImageManager::getInstance($this->getServiceLocator());
        $imageManager->resizeImage($footerImagePath, ImageManager::FOOTER_IMAGE_WIDTH, ImageManager::FOOTER_IMAGE_HEIGHT);
        $footerImage = new FooterImage();
        $footerImage->setRegion($region);
        $footerImage->setFooterImage($footerImagePath);
        FooterImageDAO::getInstance($this->getServiceLocator())->save($footerImage);
    }

    /**
     * @param $footerImageId
     * @return bool
     */
    public function deleteFooterImage($footerImageId) {
        $footerImageDAO = FooterImageDAO::getInstance($this->getServiceLocator());
        $footerImage = $footerImageDAO->findOneById($footerImageId);
        if ($footerImage != null) {
            ImageManager::getInstance($this->getServiceLocator())->deleteImage($footerImage->getFooterImage());
            $footerImageDAO->remove($footerImage);
            return true;
        }
        return false;
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getFooterSocials($region, $hydrate = false, $skipCache = false) {
        return FooterSocialDAO::getInstance($this->getServiceLocator())->getFooterSocials($region, $hydrate, $skipCache);
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param \Application\Model\Entities\ContentImage $icon
     * @param $url
     * @param $copy
     * @param $order
     * @param \Application\Model\Entities\FooterSocial|null $footerSocial
     */
    public function saveFooterSocial($region, $icon, $url, $copy, $order, $footerSocial = null) {
        if ($footerSocial == null) {
            $footerSocial = new FooterSocial();
            $footerSocial->setRegion($region);
        } elseif ($icon != null)
            ImageManager::getInstance($this->getServiceLocator())->deleteImage($footerSocial->getIcon());
        if ($icon != null)
            $footerSocial->setIcon($icon);
        $footerSocial->setUrl($url);
        $footerSocial->setCopy($copy);
        $footerSocial->setOrder($order);
        FooterSocialDAO::getInstance($this->getServiceLocator())->save($footerSocial);
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param $fromOrder
     * @param $lastOrder
     */
    public function swapFooterSocialsFromOrder($region, $fromOrder, $lastOrder) {
        $footerSocialDAO = FooterSocialDAO::getInstance($this->getServiceLocator());
        for ($i = $fromOrder; $i < $lastOrder; $i++) {
            $footerSocial = $region->getFooterSocialByOrder($i);
            $footerSocial->setOrder($i + 1);
            $footerSocialDAO->save($footerSocial, false, false);
        }
        $footerSocialDAO->flush();
        $footerSocialDAO->clearCache();
    }

    /**
     * @param \Application\Model\Entities\Region $region
     * @param $toOrder
     * @param $lastOrder
     */
    public function swapFooterSocialsToOrder($region, $toOrder, $lastOrder) {
        $footerSocialDAO = FooterSocialDAO::getInstance($this->getServiceLocator());
        for ($i = $lastOrder; $i > $toOrder; $i--) {
            $footerSocial = $region->getFooterSocialByOrder($i);
            $footerSocial->setOrder($i - 1);
            $footerSocialDAO->save($footerSocial, false, false);
        }
        $footerSocialDAO->flush();
        $footerSocialDAO->clearCache();
    }

    /**
     * @param \Application\Model\Entities\FooterSocial $footerSocial
     * @return bool
     */
    public function deleteFooterSocial($footerSocial) {
        ImageManager::getInstance($this->getServiceLocator())->deleteImage($footerSocial->getIcon());
        ContentManager::getInstance($this->getServiceLocator())->swapFooterSocialsToOrder($footerSocial->getRegion(), $footerSocial->getOrder(), $footerSocial->getRegion()->getFooterSocials()->count());
        FooterSocialDAO::getInstance($this->getServiceLocator())->remove($footerSocial);
    }

    /**
     * @param array $fieldsets
     * @return array
     */
    public function getFooterPageLanguageData(array $fieldsets)
    {
        $data = array();
        if (!empty($fieldsets)){
            foreach($fieldsets as $fieldset){
                $language = $fieldset->getData();
                $data[$language['id']] = array(
                    'content' => $fieldset->get('content')->getValue()
                );
            }
        }
        return $data;
    }

    /**
     * @param \Zend\Form\Form $form
     * @return array
     */
    public function getLogotypeLanguageData(\Zend\Form\Form $form)
    {
        $data = array();
        if (!empty($form)){
            $imageManager = ImageManager::getInstance($this->getServiceLocator());
            $emblem = $imageManager->saveUploadedImage($form->get('emblem'), ImageManager::IMAGE_TYPE_LOGOTYPE);
            $data['emblem'] = $emblem;
            foreach($form->getFieldsets() as $fieldset){
                $language = $fieldset->getData();
                $data['languages'][$language['id']] = array(
                    'logotype' => $imageManager->saveUploadedImage($fieldset->get('logotype'), ImageManager::IMAGE_TYPE_LOGOTYPE)
                );
            }
        }
        return $data;
    }

    /**
     * @param \Zend\Form\Form $form
     * @return array
     */
    public function getTermLanguageData(\Zend\Form\Form $form)
    {
        $data = array();
        if (!empty($form)){
            $formData = $form->getData();
            foreach($form->getFieldsets() as $fieldset){
                $language = $fieldset->getData();
                $data[$language['id']] = array(
                    'required' => (bool)$formData['required'],
                    'checked'  => (bool)$formData['checked'],
                    'copy'     => $fieldset->get('copy')->getValue()
                );
            }
        }
        return $data;
    }

    /**
     * @param array $data
     */
    public function saveLogotype(array $data)
    {
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $logotypeDAO = LogotypeDAO::getInstance($this->getServiceLocator());
        $emblemDAO = EmblemDAO::getInstance($this->getServiceLocator());

        $imageManager = ImageManager::getInstance($this->getServiceLocator());
        if (!empty($data)){
            $emblemPath = $data['emblem'];
            $emblem = $emblemDAO->getEmblem();
            if (is_null($emblem)){
                $emblem = new Emblem();
            }
            if (!empty($emblemPath)){
                $oldEmblem = $emblem->getPath();
                if (!empty($oldEmblem)){
                    $imageManager->deleteImage($oldEmblem);
                }
                $emblem->setPath($emblemPath);
                $emblemDAO->save($emblem, false, false);
            }

            foreach($data['languages'] as $id => $languageData){
                $logotype = $this->getLogotypeByLanguage($id);
                if (is_null($logotype)){
                    $logotype = new Logotype();
                }
                $language = $languageManager->getLanguageById($id);
                $logotype->setLanguage($language);
                $logotype->setEmblem($emblem);
                if (!empty($languageData['logotype'])){
                    $oldLogotype = $logotype->getLogotype();
                    if (!empty($oldLogotype)){
                        $imageManager->deleteImage($oldLogotype);
                    }
                    $logotype->setLogotype($languageData['logotype']);
                }
                $logotypeDAO->save($logotype, false, false);
            }

            $logotypeDAO->flush();
            $logotypeDAO->clearCache();
            $emblemDAO->clearCache();
        }
    }
    /**
     * @param $type
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\FooterPage
     */
    public function getFooterPageByTypeAndLanguage($type, $languageId, $hydrate = false, $skipCache = false)
    {
        return FooterPageDAO::getInstance($this->getServiceLocator())->getFooterPageByTypeAndLanguage($type, $languageId, $hydrate, $skipCache);
    }

    /**
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\Logotype
     */
    public function getLogotypeByLanguage($languageId, $hydrate = false, $skipCache = false)
    {
        return LogotypeDAO::getInstance($this->getServiceLocator())->getLogotypeByLanguage($languageId, $hydrate, $skipCache);
    }
    /**
     * @param array $data
     * @param $pageType
     */
    public function saveFooterPage(array $data, $pageType)
    {
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $footerPageDAO = FooterPageDAO::getInstance($this->getServiceLocator());
        if (!empty($data)){
            foreach($data as $languageId => $content){
                $footerPage = $this->getFooterPageByTypeAndLanguage($pageType, $languageId);

                if (is_null($footerPage)){
                    $footerPage = new FooterPage();
                }
                $language = $languageManager->getLanguageById($languageId);
                $footerPage->setType($pageType)->setContent($content['content'])->setLanguage($language);
                $footerPageDAO->save($footerPage, false, false);
            }

            $footerPageDAO->flush();
            $footerPageDAO->clearCache();
        }
    }

    /**
     * @param $id
     * @param bool $hydrate
     * @param bool $skipCache
     * @return mixed
     */
    public function getTermById($id, $hydrate = false, $skipCache = false)
    {
        return TermDAO::getInstance($this->getServiceLocator())->findOneById($id, $hydrate, $skipCache);
    }
    /**
     * @param $type
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getFooterPageByType($type, $hydrate = false, $skipCache = false)
    {
        return FooterPageDAO::getInstance($this->getServiceLocator())->getFooterPageByType($type, $hydrate, $skipCache);
    }

    /**
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getLogotypes($hydrate = false, $skipCache = false)
    {
        return LogotypeDAO::getInstance($this->getServiceLocator())->findAll($hydrate, $skipCache);
    }

    /**
     * @param $pageType
     * @return string
     */
    public function getFooterPageContent($pageType)
    {
        $userManager = UserManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        $language = $userManager->getCurrentUserLanguage();
        $footerPage = $contentManager->getFooterPageByTypeAndLanguage($pageType, $language->getId());
        if (!is_null($footerPage)){
            return $footerPage->getContent();
        }
        return false;
    }

    /**
     * @param Term $term
     * @param $data
     */
    public function saveTerm(Term $term, $data)
    {
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $termDAO = TermDAO::getInstance($this->getServiceLocator());
        $termCopyDAO = TermCopyDAO::getInstance($this->getServiceLocator());
        if (!empty($data)){
            foreach($data as $id => $languageData){
                $termCopy = $term->getTermCopyByLanguage($id);
                $language = $languageManager->getLanguageById($id);
                $term->setIsChecked(!empty($languageData['checked']));
                $term->setIsRequired(!empty($languageData['required']));
                if (is_null($termCopy)){
                    $termCopy = new TermCopy();
                }
                $termCopy->setLanguage($language);
                $termCopy->setTerm($term);
                $termCopy->setCopy($languageData['copy']);
                $termCopyDAO->save($termCopy, false, false);
            }
            $termDAO->save($term, false,false);
            $termDAO->flush();
            $termCopyDAO->clearCache();
            $termDAO->clearCache();
        }
    }

    /**
     * @param $languageId
     * @param bool $hydrate
     * @param bool $skipCache
     * @return array
     */
    public function getTermsByLanguageId($languageId,$hydrate = false, $skipCache = false)
    {
        return TermDAO::getInstance($this->getServiceLocator())->getTermsByLanguageId($languageId, $hydrate, $skipCache);
    }

    /**
     * @param Term $term
     */
    public function deleteTerm(Term $term)
    {
       TermDAO::getInstance($this->getServiceLocator())->remove($term);
    }

    /**
     * @param bool $skipCache
     * @return int
     */
    public function getTermsCount($skipCache = false)
    {
        return TermDAO::getInstance($this->getServiceLocator())->count($skipCache);
    }

    /**
     * @return array
     */
    public function getRegistrationFormTerms()
    {
        $language = UserManager::getInstance($this->getServiceLocator())->getCurrentUserLanguage();
        return $this->getTermsByLanguageId($language->getId(), true);

    }

    /**
     * @param $regionId
     * @param $type
     * @param bool $hydrate
     * @param bool $skipCache
     * @return \Application\Model\Entities\DefaultReportContent|array
     */
    public function getDefaultReportContentByTypeAndRegion($regionId, $type, $hydrate = false, $skipCache = false) {
        return DefaultReportContentDAO::getInstance($this->getServiceLocator())->getDefaultReportContentByTypeAndRegion($regionId, $type, $hydrate, $skipCache);
    }

    public function saveDefaultReportContent($defaultReportContent, $flush = true, $clearCache = true) {
        DefaultReportContentDAO::getInstance($this->getServiceLocator())->save($defaultReportContent, $flush, $clearCache);
    }

    public function flushAndClearCacheDefaultReportContent() {
        DefaultReportContentDAO::getInstance($this->getServiceLocator())->flush();
        DefaultReportContentDAO::getInstance($this->getServiceLocator())->clearCache();
    }

}