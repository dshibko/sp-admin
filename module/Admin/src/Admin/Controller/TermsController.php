<?php

namespace Admin\Controller;

use Admin\Form\TermForm;
use Application\Manager\ApplicationManager;
use Application\Manager\ContentManager;
use Application\Manager\LanguageManager;
use Application\Model\Entities\Term;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;

class TermsController extends AbstractActionController
{

    const ADMIN_CONTENT_TERMS_ROUTE = 'admin-content-terms';
    const MAX_TERMS_COUNT = 2;

    public function indexAction()
    {
        $terms = array();
        $lanaguageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try {
            $terms = $contentManager->getTermsByLanguageId($lanaguageManager->getDefaultLanguage()->getId(), true);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'maxTermsCount' => self::MAX_TERMS_COUNT,
            'terms' => $terms,
            'title' => 'Terms',
        );
    }

    public function addAction()
    {

        $form = null;
        $lanaguageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        $params = array();
        try {
            $termsCount = $contentManager->getTermsCount();
            if ($termsCount >= self::MAX_TERMS_COUNT){
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_MAX_TERMS_COUNT_EXCEEDED);
                return $this->redirect()->toRoute(self::ADMIN_CONTENT_TERMS_ROUTE);
            }
            $termsLanguageFieldsets = $lanaguageManager->getLanguagesFieldsets('\Admin\Form\TermFieldset');
            $form = new TermForm($termsLanguageFieldsets);
            $params = array(
                'action' => 'add'
            );
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $termData = $contentManager->getTermLanguageData($form);
                    $term = new Term();
                    $contentManager->saveTerm($term, $termData);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_TERM_SAVED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_CONTENT_TERMS_ROUTE, array('action' => 'edit', 'id' => $term->getId())));
                } else {
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
                }
            }
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        return array(
            'params' => $params,
            'title' => 'Add Term',
            'form' => $form
        );
    }

    public function editAction()
    {
        $termId = (string)$this->params()->fromRoute('id', '');
        if (empty($termId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_TERM_ID);
            return $this->redirect()->toRoute(self::ADMIN_CONTENT_TERMS_ROUTE);
        }
        $form = null;
        $lanaguageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        $params = array();
        try {
            $term = $contentManager->getTermById($termId);
            if (is_null($term)) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_TERM);
                return $this->redirect()->toRoute(self::ADMIN_CONTENT_TERMS_ROUTE);
            }
            $termsLanguageFieldsets = $lanaguageManager->getLanguagesFieldsets('\Admin\Form\TermFieldset');
            $form = new TermForm($termsLanguageFieldsets);
            $params = array(
                'action' => 'edit',
                'id' => $term->getId()
            );
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $termData = $contentManager->getTermLanguageData($form);
                    $contentManager->saveTerm($term, $termData);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_TERM_SAVED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_CONTENT_TERMS_ROUTE, array('action' => 'edit', 'id' => $term->getId())));
                } else {
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
                }
            }
            $form->initForm($term);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        return array(
            'params' => $params,
            'title' => 'Add Term',
            'form' => $form
        );
    }

    public function deleteAction()
    {
        $termId = (string)$this->params()->fromRoute('id', '');
        if (empty($termId)) {
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_INVALID_TERM_ID);
            return $this->redirect()->toRoute(self::ADMIN_CONTENT_TERMS_ROUTE);
        }
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try {
            $term = $contentManager->getTermById($termId);
            if (is_null($term)) {
                $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_CANNOT_FIND_TERM);
                return $this->redirect()->toRoute(self::ADMIN_CONTENT_TERMS_ROUTE);
            }
            $contentManager->deleteTerm($term);
            $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_TERM_DELETED);

        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        return $this->redirect()->toRoute(self::ADMIN_CONTENT_TERMS_ROUTE);
    }

}
