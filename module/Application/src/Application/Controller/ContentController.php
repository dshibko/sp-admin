<?php

namespace Application\Controller;

use \Neoco\Controller\AbstractActionController;
use \Application\Manager\ExceptionManager;
use \Application\Manager\ApplicationManager;
use \Application\Model\Helpers\MessagesConstants;
use \Application\Form\FeedbackForm;
use Zend\View\Model\ViewModel;

class ContentController extends AbstractActionController
{
    public function privacyAction()
    {
        return array();
    }

    public function termsAction()
    {
        return array();
    }

    public function cookiesAction()
    {
        return array();
    }

    public function contactAction()
    {
        return array();
    }
    public function helpAction()
    {
        try{
            $form = new FeedbackForm();
            $request = $this->getRequest();
            if ($request->isPost()){
                $form->setData($request->getPost());
                if ($form->isValid()){
                   //TODO send email to admin
                }else{
                    $this->formErrors($form, $this);
                }
            }
            return array(
                'form' => $form
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