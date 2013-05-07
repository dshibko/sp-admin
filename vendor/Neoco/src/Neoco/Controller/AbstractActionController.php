<?php

namespace Neoco\Controller;

use Zend\Session\Container;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Exception;
use Zend\View\Model\ConsoleModel;
use Zend\View\Model\ViewModel;
use \Application\Model\DAOs\UserDAO;

class AbstractActionController extends \Zend\Mvc\Controller\AbstractActionController {

    /**
     * @var Zend\Session\Container
     */
    protected $sessionContainer;

    /**
     * @return Zend\Session\Container
     */
    public function getSessionContainer()
    {
        if ($this->sessionContainer == null)
            $this->sessionContainer = new Container('SessionContainer');
        return $this->sessionContainer;
    }

}