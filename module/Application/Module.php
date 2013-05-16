<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use \DoctrineModule\Authentication\Adapter\ObjectRepository;
use \Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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

    /**
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'getConstant' => function($sm) {
                    $config = $sm->getServiceLocator()->get('config');
                    return new \Neoco\View\Helper\GetConstant($config);
                },
                'getCurrentUser' => function($sm) {
                    return new \Neoco\View\Helper\GetCurrentUser($sm->getServiceLocator());
                },
                'renderMessages' => function($sm) {
                    $translator = $sm->getServiceLocator()->get('translator');
                    return new \Neoco\View\Helper\RenderMessages($translator);
                },
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'AuthStorage' => function($sm) {
                    return new \Application\Model\Helpers\AuthStorage();
                },
                'AuthService' => function($sm) {
                    $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                    $doctrineAuthAdapter = new ObjectRepository(array(
                        'objectManager' => $entityManager,
                        'identityClass' => 'Application\Model\Entities\User',
                        'identityProperty' => 'email',
                        'credentialProperty' => 'password',
                        'credentialCallable' => function($identity, $credential) {
                            return md5($credential); // TODO to define password strategy
                        }
                    ));

                    $authService = new AuthenticationService();
                    $authService->setAdapter($doctrineAuthAdapter);
                    $authService->setStorage($sm->get('AuthStorage'));

                    return $authService;
                },
                'Application\Form\RegistrationForm' => function($sm){
                    return new \Application\Form\RegistrationForm($sm);
                },
                'Application\Form\Filter\RegistrationFilter' => function($sm){
                    return new \Application\Form\Filter\RegistrationFilter($sm);
                },
            ),
        );
    }
}
