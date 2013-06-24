<?php

namespace Neoco\View\Helper;

use Zend\View\Helper\AbstractHelper;
use \Zend\Mvc\Controller\Plugin\FlashMessenger;

class FlashMessages extends AbstractHelper
{

    /**
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator;
    /**
     * @var FlashMessenger
     */
    protected $flashMessenger;


    /**
     * @param $messages
     * @param array $classes
     * @return string
     */
    private function render($messages, array $classes)
    {
        $html = '';
        if (!empty($messages)){
            $html = '<div class="'.implode(' ', $classes).'"><ul>';
            foreach ($messages as $message){
                $html .= '<li>'.$message.'</li>';
            }
            $html .= '</ul></div>';
        }
        return $html;
    }

    /**
     * @param \Zend\I18n\Translator\Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return FlashMessenger
     */
    public function getFlashMessenger()
    {
        if(is_null($this->flashMessenger)){
            $this->flashMessenger = new FlashMessenger();
        }
        return $this->flashMessenger;
    }

    /**
     * @param string $namespace
     * @param array $classes
     * @return string
     */
    public function __invoke($namespace = FlashMessenger::NAMESPACE_DEFAULT, array $classes = array())
    {

        $this->getFlashMessenger()->setNamespace($namespace);
        $messages = array();
        if ($this->getFlashMessenger()->hasMessages() || $this->getFlashMessenger()->hasCurrentMessages()){
            $messages = array_merge(
                $this->getFlashMessenger()->getMessages(),  $this->getFlashMessenger()->getCurrentMessages()
            );
            $this->getFlashMessenger()->clearMessages();
            $this->getFlashMessenger()->clearCurrentMessages();
        }

        return $this->render($messages, $classes);
    }


    
}