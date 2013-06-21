<?php
namespace Neoco\Controller\Plugin;

use \Zend\Mvc\Controller\Plugin\AbstractPlugin;

class FormErrors extends AbstractPlugin
{
    public function __invoke(\Zend\Form\Form $form, \Zend\Mvc\Controller\AbstractActionController $controller)
    {
        $translator = $controller->getServiceLocator()->get('translator');
        //TODO need refactoring
        foreach ($form->getMessages() as $el => $messages) {
            $label = $translator->translate($form->get($el)->getLabel());

            $error = '';
            if(is_array($messages)){
                foreach($messages as $message){
                    if (!is_array($message)){
                        $error = implode(", ", $messages);
                        break;
                    }else{
                        //Fieldset errors
                        $label = ucfirst(strtolower($form->get($el)->getName()));
                        foreach($message as $fieldsetError){
                            if(is_array($fieldsetError)){
                                $error = implode(', ', $fieldsetError);
                            }else{
                                $error = $fieldsetError;
                            }
                            break 2;
                        }
                    }
                }
            }else{
                $error = $messages;
            }
            $controller->flashMessenger()->addErrorMessage( (!empty($label) ? $label . ": " : '' ) . $translator->translate($error));
        }

    }
}