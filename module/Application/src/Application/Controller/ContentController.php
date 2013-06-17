<?php

namespace Application\Controller;

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
        $form = new FeedbackForm();
        try{
            $request = $this->getRequest();
            if ($request->isPost()){
                $form->setData($request->getPost());
                if ($form->isValid()){
                   //TODO send email to admin
                }else{
                    $this->formErrors($form, $this);
                }
            }
        }catch(\Exception $e){
           ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this);
        }
        return array(
            'form' => $form
        );
    }

    public function facebookCanvasAction()
    {
        $view = new ViewModel(array());
        $view->setTerminal(true);
        return $view;
    }
}