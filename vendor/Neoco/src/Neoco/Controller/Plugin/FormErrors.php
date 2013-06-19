<?php
namespace Neoco\Controller\Plugin;

use \Zend\Mvc\Controller\Plugin\AbstractPlugin;

class FormErrors extends AbstractPlugin
{
    public function __invoke(\Zend\Form\Form $form, \Zend\Mvc\Controller\AbstractActionController $controller)
    {
        foreach ($form->getMessages() as $el => $messages) {
            $controller->flashMessenger()->addErrorMessage($form->get($el)->getLabel() . ": " . (is_array($messages) ? implode(", ", $messages) : $messages));
        }
    }
}