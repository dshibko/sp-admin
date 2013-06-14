<?php

namespace Admin\Controller;

use \Application\Manager\LanguageManager;
use \Application\Manager\ExceptionManager;
use \Neoco\Controller\AbstractActionController;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Model\Entities\FooterPage;
use Admin\Form\FooterPageForm;
use \Application\Manager\ContentManager;

class FooterPagesController extends AbstractActionController
{
    const ADMIN_FOOTER_PAGE_TERMS_ROUTE = 'admin-content-footer-pages-terms';

    public function indexAction()
    {
        return $this->redirect()->toRoute(self::ADMIN_FOOTER_PAGE_TERMS_ROUTE);
    }

    public function termsPageAction()
    {
        $lanaguageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        $form = null;
        $params = array(
            'route' => self::ADMIN_FOOTER_PAGE_TERMS_ROUTE
        );
        try {
            $footerPageLanguageFieldsets = $lanaguageManager->getLanguagesFieldsets('\Admin\Form\FooterPageFieldset');
            $form = new FooterPageForm($footerPageLanguageFieldsets);
            $request = $this->getRequest();
            $pageData = $contentManager->getFooterPageByType(FooterPage::TERMS_PAGE);
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $footerPageData = $contentManager->getFooterPageLanguageData($footerPageLanguageFieldsets);
                    $contentManager->saveFooterPage($footerPageData, FooterPage::TERMS_PAGE);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_PAGE_SAVED);
                    return $this->redirect()->toRoute(self::ADMIN_FOOTER_PAGE_TERMS_ROUTE);
                } else {
                    $form->handleErrorMessages($form->getMessages(), $this->flashMessenger());
                }
            }
            $form->initForm($pageData);
        } catch (\Exception $e) {
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }

        return array(
            'form' => $form,
            'params' => $params,
            'title' => $this->getServiceLocator()->get('translator')->translate('Terms Page')
        );
    }
}
