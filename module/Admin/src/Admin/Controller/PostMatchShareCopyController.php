<?php

namespace Admin\Controller;

use \Application\Manager\ImageManager;
use \Admin\Form\PostMatchReportCopyForm;
use \Application\Model\Entities\ShareCopy;
use \Admin\Form\PreMatchReportCopyForm;
use \Application\Manager\ShareManager;
use \Zend\View\Model\ViewModel;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class PostMatchShareCopyController extends AbstractActionController {

    const ADMIN_POST_MATCH_SHARE_COPY_ROUTE = 'admin-post-match-share-copy';

    public function indexAction() {

        $forms = array();

        try {

            $shareManager = ShareManager::getInstance($this->getServiceLocator());
            $achievementBlocks = $shareManager->getAchievementBlocks();
            foreach ($achievementBlocks as $achievementBlock) {
                $form = new PostMatchReportCopyForm();
                $achievementArr = $achievementBlock->getArrayCopy();
                $achievementArr['facebook'] = $achievementBlock->getFacebookShareCopy()->getCopy();
                $achievementArr['twitter'] = $achievementBlock->getTwitterShareCopy()->getCopy();
                $form->populateValues($achievementArr);
                $forms [$achievementBlock->getId()] = $form;
            }

            $request = $this->getRequest();
            if ($request->isPost()) {
                $id = $request->getPost('id', 0);
                if (array_key_exists($id, $forms)) {
                    $form = $forms[$id];
                    $post = array_merge_recursive(
                        $request->getPost()->toArray(),
                        $request->getFiles()->toArray()
                    );
                    $form->setData($post);
                    if ($form->isValid()) {
                        $achievementBlock = $shareManager->getAchievementBlockById($id);
                        $imageManager = ImageManager::getInstance($this->getServiceLocator());
                        $iconPathValue = $form->get('iconPath')->getValue();
                        $data = $form->getData();
                        if (!array_key_exists('stored', $iconPathValue) || $iconPathValue['stored'] == 0) {
                            $iconPath = $imageManager->saveUploadedImage($form->get('iconPath'), ImageManager::IMAGE_TYPE_AWARD);
                            $imageManager->resizeImage($iconPath, ImageManager::ACHIEVEMENT_ICON_WIDTH, ImageManager::ACHIEVEMENT_ICON_HEIGHT);
                            $imageManager->deleteImage($achievementBlock->getIconPath());
                            $data['iconPath'] = $iconPath;
                        } else
                            $data['iconPath'] = null;
                        $achievementBlock->populate($data);
                        $achievementBlock->getFacebookShareCopy()->setCopy($data['facebook']);
                        $achievementBlock->getTwitterShareCopy()->setCopy($data['twitter']);
                        $shareManager->saveAchievementBlock($achievementBlock);
                        $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_POST_MATCH_SHARE_COPY_UPDATED);
                        return $this->redirect()->toRoute(self::ADMIN_POST_MATCH_SHARE_COPY_ROUTE);
                    } else
                        foreach ($form->getMessages() as $el => $messages)
                            $this->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " .
                                (is_array($messages) ? implode(", ", $messages): $messages));
                }
            }

        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'forms' => $forms,
        );

    }

}
