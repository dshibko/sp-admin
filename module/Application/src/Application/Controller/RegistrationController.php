<?php

namespace Application\Controller;

use Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Manager\ExceptionManager;
use Application\Helper\Avatar;
use Application\Manager\RegistrationManager;

class RegistrationController extends AbstractActionController {
    //TODO set default avatar on load
    public function indexAction() {
       // $facebook = $this->getServiceLocator()->get('facebook');
        try{
            $form = $this->getServiceLocator()->get('Application\Form\RegistrationForm');
            $form->get('submit')->setValue('Register');
            $request = $this->getRequest();

            /* if ($facebook->getUser()){
                $user_id = $facebook->getUser();
            }*/
            if ($request->isPost()) {
                $form->setInputFilter($this->getServiceLocator()->get('Application\Form\Filter\RegistrationFilter')->getInputFilter());
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
                        //TODO redirect to set-up page
                        //TODO sign in user
                        //TODO set inactive user
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

    }

}
