<?php

namespace Neoco\Controller;

use \Application\Model\Helpers\MessagesConstants;
use \Zend\View\Helper\Navigation\Breadcrumbs;
use Zend\Session\Container;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Exception;
use Zend\View\Model\ConsoleModel;
use Zend\View\Model\ViewModel;
use \Application\Model\DAOs\UserDAO;

class AbstractActionController extends \Zend\Mvc\Controller\AbstractActionController {

    protected $activePage = -1;

    /**
     * @return \Zend\Navigation\Page\Mvc
     * @throws \Exception
     */
    public function getActivePage() {
        if ($this->activePage == -1) {
            $navigation = $this->getServiceLocator()->get('navigation');
            $breadcrumbs = new Breadcrumbs();
            $active = $breadcrumbs->findActive($navigation);
            $this->activePage = $active != null && is_array($active) ? $active['page'] : null;
            if ($this->activePage == null)
                throw new \Exception(MessagesConstants::ERROR_ACTIVE_PAGE_NOT_FOUND);
        }
        return $this->activePage;
    }

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