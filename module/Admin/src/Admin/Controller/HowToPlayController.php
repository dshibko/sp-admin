<?php
namespace Admin\Controller;

use Admin\Form\HowToPlayContentForm;
use Application\Manager\ContentManager;
use Application\Manager\ExceptionManager;
use Application\Manager\ImageManager;
use Application\Manager\LanguageManager;
use Application\Model\Entities\HowToPlayContent;
use Application\Model\Entities\Language;
use Application\Model\Helpers\MessagesConstants;
use Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HowToPlayController extends AbstractActionController
{
    const MAX_BLOCKS_NUMBER = 8;
    const ADMIN_HOW_TO_PLAY_ROUTE = 'admin-content-how-to-play';

    private function getLanguageFromRoute()
    {
        $language = null;
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $languageId =  (string) $this->params()->fromRoute('language', '');
        if (!empty($languageId)){
            $language = $languageManager->getLanguageById($languageId);
        }
        if (is_null($language)){
            $language = $languageManager->getDefaultLanguage();
        }
        return $language;
    }

    private function setBreadCrumbsByLanguage(Language $language)
    {
        $parentTitle = $this->getActivePage()->getParent()->getTitle();
        $this->getActivePage()->getParent()->setParams(array('action' => null, 'block' => null, 'customTitle' => $parentTitle . " - " . $language->getDisplayName()));
        return $this;
    }
    public function indexAction()
    {
        $language = null;
        $languages = array();
        $blocks = array();
        $languageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try {
            $language = $this->getLanguageFromRoute();
            $languages = $languageManager->getAllLanguages(true);
            $blocks = $contentManager->getLanguageHowToPlayBlocks($language, true);
        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return new ViewModel(array(
            'route' => self::ADMIN_HOW_TO_PLAY_ROUTE,
            'languages' => $languages,
            'blocks' => $blocks,
            'currentLanguage' => $language,
            'maxBlocksNumber' => self::MAX_BLOCKS_NUMBER
        ));
    }

    public function addAction()
    {
        $language = null;

        $form = null;
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try {
            $language = $this->getLanguageFromRoute();
            $howToPlayBlocksCount = $language->getHowToPlayBlocks()->count();
            if ($howToPlayBlocksCount >= self::MAX_BLOCKS_NUMBER){
                $this->flashMessenger()->addErrorMessage(sprintf(MessagesConstants::ERROR_MAX_HOW_TO_PLAY_BLOCKS_COUNT_EXCEEDED, self::MAX_BLOCKS_NUMBER));
                return $this->redirect()->toRoute(self::ADMIN_HOW_TO_PLAY_ROUTE, array('language' => $language->getId(), 'block' => null));
            }
            $form = new HowToPlayContentForm('how-to-play', (bool)$language->getIsDefault());
            $this->setBreadCrumbsByLanguage($language);
            $params = array(
                'language' => $language->getId(),
                'action' => 'add'
            );
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()){
                    $data = $form->getData();
                    $imageManager = ImageManager::getInstance($this->getServiceLocator());
                    $foregroundImage = null;

                    if (isset($data['foregroundImage']['error'])&& $data['foregroundImage']['error']  == UPLOAD_ERR_OK){
                        $foregroundImagePath = $imageManager->saveUploadedImage($form->get('foregroundImage'), ImageManager::IMAGE_TYPE_CONTENT);
                        $foregroundImage = $imageManager->prepareContentImage($foregroundImagePath, ImageManager::$HOWTOPLAY_FOREGROUND_SIZES);
                    }

                    $howToPlayContent = new HowToPlayContent();
                    $data['foregroundImage'] = $foregroundImage;
                    $data['language'] = $language;
                    $howToPlayContent->populate($data);
                    $contentManager->saveHowToPlayContent($howToPlayContent);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_HOW_TO_PLAY_CONTENT_CREATED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_HOW_TO_PLAY_ROUTE, array('action' => 'edit','language' => $language->getId(), 'block' => $howToPlayContent->getId())));
                }else{
                    $this->formErrors($form, $this);
                }
            }
        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = !empty($language) ? array('language' => $language->getId()) : array();
            return $this->redirect()->toRoute(self::ADMIN_HOW_TO_PLAY_ROUTE, $routeParams);
        }

        return new ViewModel(array(
            'form' => $form,
            'route' => self::ADMIN_HOW_TO_PLAY_ROUTE,
            'order' => $howToPlayBlocksCount + 1,
            'title' => 'Add Block',
            'params' => $params
        ));
    }

    public function editAction()
    {
        $language = null;
        $form = null;
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try {
            $blockId  =  (string) $this->params()->fromRoute('block', '');
            if (empty($blockId)){
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INCORRECT_BLOCK_ID);
                return $this->redirect()->toRoute(self::ADMIN_HOW_TO_PLAY_ROUTE);
            }
            $howToPlayContent = $contentManager->getHowToPlayContentById($blockId);
            if (!$howToPlayContent instanceof HowToPlayContent){
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_HOW_TO_PLAY_BLOCK);
                return $this->redirect()->toRoute(self::ADMIN_HOW_TO_PLAY_ROUTE);
            }
            $language = $this->getLanguageFromRoute();
            $form = new HowToPlayContentForm('how-to-play', (bool)$language->getIsDefault());
            $howToPlayBlocksCount = $language->getHowToPlayBlocks()->count();
            $this->setBreadCrumbsByLanguage($language);
            $params = array(
                'language' => $language->getId(),
                'action' => 'edit',
                'block' => $howToPlayContent->getId()
            );
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()){
                    $data = $form->getData();
                    $imageManager = ImageManager::getInstance($this->getServiceLocator());
                    if (isset($data['foregroundImage']['error'])&& $data['foregroundImage']['error']  == UPLOAD_ERR_OK){
                        $foregroundImagePath = $imageManager->saveUploadedImage($form->get('foregroundImage'), ImageManager::IMAGE_TYPE_CONTENT);
                        $foregroundImage = $imageManager->prepareContentImage($foregroundImagePath, ImageManager::$GAMEPLAY_FOREGROUND_SIZES);
                        if (!is_null($howToPlayContent->getForegroundImage())){
                            $imageManager->deleteContentImage($howToPlayContent->getForegroundImage());
                        }
                        $howToPlayContent->setForegroundImage($foregroundImage);
                    }elseif(empty($data['foregroundImage'])){
                        if (!is_null($howToPlayContent->getForegroundImage())){
                            $imageManager->deleteContentImage($howToPlayContent->getForegroundImage());
                        }
                        $howToPlayContent->setForegroundImage(null);
                    }

                    $data['language'] = $language;
                    $howToPlayContent->populate($data);
                    $contentManager->saveHowToPlayContent($howToPlayContent);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_HOW_TO_PLAY_CONTENT_UPDATED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_HOW_TO_PLAY_ROUTE, $params));
                }else{
                    $this->formErrors($form, $this);
                }
            }
            $form->populateValues($howToPlayContent->getArrayCopy());
        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = !empty($language) ? array('language' => $language->getId()) : array();
            return $this->redirect()->toRoute(self::ADMIN_HOW_TO_PLAY_ROUTE, $routeParams);
        }

        return new ViewModel(array(
            'form' => $form,
            'route' => self::ADMIN_HOW_TO_PLAY_ROUTE,
            'order' => $howToPlayBlocksCount,
            'title' => 'Edit Block',
            'params' => $params
        ));
    }

    public function deleteAction()
    {
        try {
            $blockId  =  (string) $this->params()->fromRoute('block', '');
            $contentManager = ContentManager::getInstance($this->getServiceLocator());
            if (empty($blockId)){
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INCORRECT_BLOCK_ID);
                return $this->redirect()->toRoute(self::ADMIN_HOW_TO_PLAY_ROUTE);
            }

            $howToPlayContent = $contentManager->getHowToPlayContentById($blockId);
            if (!$howToPlayContent instanceof HowToPlayContent){
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_HOW_TO_PLAY_BLOCK);
                return $this->redirect()->toRoute(self::ADMIN_HOW_TO_PLAY_ROUTE);
            }
            $language = $this->getLanguageFromRoute();
            $contentManager->deleteHowToPlayContentBlock($howToPlayContent);
            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_HOW_TO_PLAY_CONTENT_DELETED);
            return $this->redirect()->toRoute(self::ADMIN_HOW_TO_PLAY_ROUTE, array('language' => $language->getId(), 'block' => null));
        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            $routeParams = !empty($language) ? array('language' => $language->getId()) : array();
            return $this->redirect()->toRoute(self::ADMIN_HOW_TO_PLAY_ROUTE, $routeParams);
        }

    }
}