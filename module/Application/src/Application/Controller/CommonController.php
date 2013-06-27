<?php

namespace Application\Controller;

class CommonController extends \Zend\Mvc\Controller\AbstractActionController
{

    public function utcTimeAction()
    {

        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/html');

        $utcTime = new \DateTime();
        $response->setContent($utcTime->getTimestamp());

        return $response;

    }

}