<?php

namespace Application\Controller;

use Application\Manager\MessageManager;
use Neoco\Controller\AbstractActionController;

class UserMessagesController extends AbstractActionController {

    public function hideMessageAction()
    {
        $messageId = (int)$this->getRequest()->getPost('id');
        $result = 0;
        if (!empty($messageId)) {
            $messageManager = MessageManager::getInstance($this->getServiceLocator());
            $messageManager->setMessageViewed($messageId);
            $result = 1;
        }

        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/html');

        $response->setContent($result);

        return $response;
    }
}