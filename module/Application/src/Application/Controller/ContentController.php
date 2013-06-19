<?php

namespace Application\Controller;

use Application\Manager\MailManager;
use Application\Model\Helpers\MessagesConstants;
use \Neoco\Controller\AbstractActionController;
use \Application\Manager\ExceptionManager;
use \Application\Manager\ApplicationManager;
use \Application\Form\FeedbackForm;
use Zend\View\Model\ViewModel;
use \Application\Manager\UserManager;
use \Application\Manager\ContentManager;
use \Application\Model\Entities\FooterPage;

class ContentController extends AbstractActionController
{
    const HELP_AND_SUPPORT_PAGE_ROUTE = 'help';

    public function privacyAction()
    {
        $content = '';
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try{
            $content = $contentManager->getFooterPageContent(FooterPage::PRIVACY_PAGE);
        }catch(\Exception $e){
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e,$this);
        }

        return array(
            'content' => $content,
            'title' => 'Privacy'
        );
    }

    public function termsAction()
    {
        $content = '';
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try{
            $content = $contentManager->getFooterPageContent(FooterPage::TERMS_PAGE);
        }catch(\Exception $e){
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e,$this);
        }

        return array(
            'content' => $content,
            'title' => 'Terms'
        );
    }

    public function cookiesAction()
    {
        $content = '';
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try{
            $content = $contentManager->getFooterPageContent(FooterPage::COOKIES_PAGE);
        }catch(\Exception $e){
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e,$this);
        }

        return array(
            'content' => $content,
            'title' =>'Cookies Privacy'
        );
    }

    public function contactAction()
    {
        $content = '';
        $contentManager = ContentManager::getInstance($this->getServiceLocator());
        try{
            $content = $contentManager->getFooterPageContent(FooterPage::CONTACT_US_PAGE);
        }catch(\Exception $e){
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e,$this);
        }

        return array(
            'content' => $content,
            'title' => 'Contact Us'
        );
    }
    public function helpAction()
    {
        try{
            $form = new FeedbackForm();
            $contentManager = ContentManager::getInstance($this->getServiceLocator());
            $mailManager = MailManager::getInstance($this->getServiceLocator());

            $content = $contentManager->getFooterPageContent(FooterPage::HELP_AND_SUPPORT);
            $request = $this->getRequest();
            if ($request->isPost()){
                $form->setData($request->getPost());
                if ($form->isValid()){
                    $data = $form->getData();
                    $mailManager->sendHelpAndSupportEmail($data['email'], $data['name'], $data['query']);
                    $this->flashMessenger()->addSuccessMessage(MessagesConstants::SUCCESS_HELP_AND_SUPPORT_MESSAGE_SENT);
                    return $this->redirect()->toRoute(self::HELP_AND_SUPPORT_PAGE_ROUTE);
                }else{
                    $this->formErrors($form, $this);
                }
            }
            return array(
                'form' => $form,
                'content' => $content,
                'title' => 'Help & Support'
            );
        }catch(\Exception $e){
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
            return $this->errorAction($e);
        }
    }

    public function facebookCanvasAction()
    {
        $view = new ViewModel(array());
        $view->setTerminal(true);
        return $view;
    }
}