<?php

namespace Admin\Controller;

use \Neoco\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

    public function indexAction() {

        return new ViewModel(array(
        ));

    }

}
