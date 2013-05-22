<?php

namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Manager\UserManager;

class IndexController extends AbstractActionController {
    
    public function indexAction() {
        return new ViewModel(array(

        ));
    }

}
