<?php

namespace Admin\Controller;

use Admin\Form\LogotypeForm;
use Application\Manager\ContentManager;
use Application\Manager\LanguageManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class LogotypeController extends AbstractActionController {

    const ADMIN_CONTENT_LOGOTYPE_ROUTE = 'admin-content-logotype';

    public function indexAction() {
        $form = null;
        $lanaguageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try {
            $logotypeLanguageFieldsets = $lanaguageManager->getLanguagesFieldsets('\Admin\Form\LogotypeFieldset');
            $form = new LogotypeForm($logotypeLanguageFieldsets);
            $logotypes = $contentManager->getLogotypes();
            $request = $this->getRequest();
            if ($request->isPost()){
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post);
                if ($form->isValid()){
                    $logotypeData = $contentManager->getLogotypeLanguageData($form);
                    $contentManager->saveLogotype($logotypeData);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_LOGOTYPE_SAVED);
                    return $this->redirect()->toRoute(self::ADMIN_CONTENT_LOGOTYPE_ROUTE);
                }else{
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
                }
            }

            $form->initForm($logotypes);
        } catch(\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'title' => 'Logotype',
            'form' => $form,
        );

    }

}
