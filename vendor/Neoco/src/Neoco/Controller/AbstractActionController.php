<?php

namespace Neoco\Controller;

use \Neoco\Exception\InfoException;
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
    protected $translator;

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
                throw new \Exception($this->getTranslator()->translate(MessagesConstants::ERROR_ACTIVE_PAGE_NOT_FOUND));
        }
        return $this->activePage;
    }

    /**
     * @var \Zend\Session\Container
     */
    protected $sessionContainer;

    /**
     * @return \Zend\Session\Container
     */
    public function getSessionContainer()
    {
        if ($this->sessionContainer == null)
            $this->sessionContainer = new Container('SessionContainer');
        return $this->sessionContainer;
    }

    public function generateSecurityKey(array $data) {
        return md5(serialize($data));
    }

    public function checkSecurityKey(array $data, $post) {
        if (!array_key_exists('check_key', $post) || empty($post['check_key']) ||
            md5(serialize($data)) != $post['check_key'])
            throw new \Exception($this->getTranslator()->translate(MessagesConstants::ERROR_SECURITY_CHECK_FAILED));
        return true;
    }

    public function encodeInt($int) {
        return pow($int + 5, 4) + 3;
    }

    public function decodeInt($int) {
        $int = sqrt(sqrt($int - 3));
        return $int - 5;
    }

    public function encodeBigInt($int) {
        if ($int > 999) {
            return $this->encodeInt(floor($int / 1000)) . '-' . $this->encodeInt($int % 1000);
        }
        return $this->encodeInt($int);
    }

    public function decodeBigInt($encodedInt) {
        $intArray = explode('-', $encodedInt);
        return isset($intArray[1]) ? $this->decodeInt($intArray[0]) * 1000 + $this->decodeInt($intArray[1]) : $this->decodeInt($intArray[0]);
    }

    public function infoAction(InfoException $e)
    {
        $title = $e->getTitle();
        $content = $e->getContent();
        $view = new ViewModel(array('title' => $title, 'content' => $content));
        $view->setTemplate('error/info.phtml');
        return $view;
    }

    public function errorAction(\Exception $e)
    {
        $response = $this->response;
        $response->setStatusCode(500);
        $view = new ViewModel(array('exception' => $e));
        $view->setTemplate('error/500.phtml');
        return $view;
    }

    private $getConstantHelper;

    public function getConstant($key) {
        if ($this->getConstantHelper === null) {
            $this->getConstantHelper = new \Neoco\View\Helper\GetConstant();
            $this->getConstantHelper->setConfig($this->getServiceLocator()->get('config'));
        }
        return $this->getConstantHelper->__invoke($key);
    }

    public function getTranslator()
    {
        if (is_null($this->translator)){
            $this->translator = $this->getServiceLocator()->get('translator');
        }
        return $this->translator;
    }

}