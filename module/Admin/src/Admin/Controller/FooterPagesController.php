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
    const ADMIN_FOOTER_PAGES_ROUTE = 'admin-content-footer-pages';

    private $footerPages = array(
        FooterPage::TERMS_PAGE => 'Terms Page',
        FooterPage::CONTACT_US_PAGE => 'Contact Us Page',
        FooterPage::COOKIES_PAGE => 'Cookies Privacy Page',
        FooterPage::PRIVACY_PAGE => 'Privacy Page',
        FooterPage::HELP_AND_SUPPORT => 'Help & Support Page'
    );

    private $defaultRouteParams = array('pageType'=> FooterPage::TERMS_PAGE, 'action'=>'page');

    public function indexAction()
    {
        return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_FOOTER_PAGES_ROUTE, $this->defaultRouteParams));
    }

    public function pageAction()
    {
        $pageType = (string)$this->params()->fromRoute('pageType', '');
        if (empty($pageType)){
            return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_FOOTER_PAGES_ROUTE, $this->defaultRouteParams));
        }
        if (!isset($this->footerPages[$pageType])){
            $this->flashMessenger()->addErrorMessage(MessagesConstants::ERROR_UNDEFINED_FOOTER_PAGE_TYPE);
            return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_FOOTER_PAGES_ROUTE, $this->defaultRouteParams));
        }
        $lanaguageManager = LanguageManager::getInstance($this->getServiceLocator());
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        $form = null;
        $params = array(
            'pageType' => $pageType,
            'action' => 'page'
        );
        $pageTitle = $this->footerPages[$pageType];

        try {
            $footerPageLanguageFieldsets = $lanaguageManager->getLanguagesFieldsets('\Admin\Form\FooterPageFieldset');
            $form = new FooterPageForm($footerPageLanguageFieldsets);
            $request = $this->getRequest();
            $pageData = $contentManager->getFooterPageByType($pageType);
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $footerPageData = $contentManager->getFooterPageLanguageData($footerPageLanguageFieldsets);
                    $contentManager->saveFooterPage($footerPageData, $pageType);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_FOOTER_PAGE_SAVED);
                    return $this->redirect()->toUrl($this->url()->fromRoute(self::ADMIN_FOOTER_PAGES_ROUTE, $params));
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
            'title' => $pageTitle
        );
    }
}
