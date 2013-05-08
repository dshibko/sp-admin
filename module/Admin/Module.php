<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use \Zend\ModuleManager\ModuleManager;
use \Zend\I18n\View\Helper\Translate;
use \DoctrineModule\Authentication\Adapter\ObjectRepository;
use \Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'initOnDispatchError'), 0);
    }

    public function initOnDispatchError(MvcEvent $e)
    {
        $matches = $e->getRouteMatch();
        if ($matches != null) {
            $controller = $matches->getParam('controller');
            if (strpos($controller, __NAMESPACE__) === 0) {
                if ($e->getResult() instanceof \Zend\View\Model\ViewModel &&
                    $e->getResult()->getTemplate() == 'error/admin-redirect'
                ) {
                    $viewModel = $e->getViewModel();
                    $viewModel->setTemplate('layout/admin-login-layout');
                }
            }
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}