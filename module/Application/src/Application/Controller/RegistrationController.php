<?php

namespace Application\Controller;

use Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Manager\ExceptionManager;
use Application\Helper\Avatar;
use Application\Manager\RegistrationManager;
use Application\Manager\ApplicationManager;
use Application\Form\SetUpForm;

class RegistrationController extends AbstractActionController {
    //TODO set permission only for guests
    public function indexAction() {
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        //if member - redirect to dashboard
        if (!empty($user)){
            return $this->redirect()->toRoute('persist');
        }
        //$facebook = $this->getServiceLocator()->get('facebook');
        //print_r($facebook); die;
        try{
            $form = $this->getServiceLocator()->get('Application\Form\RegistrationForm');
            $form->get('submit')->setValue('Register');
            $request = $this->getRequest();
            /* if ($facebook->getUser()){
                $user_id = $facebook->getUser();
            }*/
            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(),
                    $request->getFiles()->toArray()
                );
                $form->setData($post)->prepareData();
                if ($form->isValid()) {
                    $avatar = new Avatar($form->get('avatar'), $this->getServiceLocator());
                    $avatar->setDefaultAvatar(!empty($post['default_avatar']) ? $post['default_avatar'] : null);
                    if ($avatar->validate()){
                        $data = $form->getData();
                        $data['avatar'] =  $avatar->save()->resize()->getPath();
                        RegistrationManager::getInstance($this->getServiceLocator())->register($data);
                        //TODO send welcome email
                        return $this->redirect()->toRoute('setup');
                    } else {
                        $form->setMessages(array('avatar' => $avatar->getErrorMessages()));
                    }
                }
            }
        }catch (\Exception $e){
            ExceptionManager::getInstance($this->getServiceLocator())->handleControllerException($e, $this); //TODO doesn't set flash messages
        }

        return new ViewModel(array(
            'form' => $form,
            'facebook' => null,
            'default_avatar' => $this->getRequest()->getPost('default_avatar', null),
            'flashMessages' => $this->flashMessenger()->getMessages()
        ));
    }

    public function setUpAction(){
        $user = ApplicationManager::getInstance($this->getServiceLocator())->getCurrentUser();
        //if guest - redirect to login page
        if (empty($user)){
            return $this->redirect()->toRoute('login');
        }
        //if active user - redirect to dashboard
        if ($user->getActive()){
            return $this->redirect()->toRoute('persist');
        }

        $form = $this->getServiceLocator()->get('Application\Form\SetUpForm');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data  = $form->getData();
                RegistrationManager::getInstance($this->getServiceLocator())->setUp($data);
                return $this->redirect()->toRoute('predict');
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'flashMessages' => $this->flashMessenger()->getMessages()
        ));
    }

}
