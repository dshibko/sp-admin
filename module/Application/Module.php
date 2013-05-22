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
use Zend\Crypt\Password\Bcrypt;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        //TODO set fallback to en_US
        //TODO set translator for validation
        //\Zend\Validator\AbstractValidator::setDefaultTranslator($translator);// Set translator for validation
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
            'Zend\Loader\ClassMapAutoloader' => array(
                array(
                    'Facebook' => 'vendor/facebook/facebook.php',
                ),
            ),
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
                    $h = new \Neoco\View\Helper\GetConstant();
                    $config = $sm->getServiceLocator()->get('config');
                    $h->setConfig($config);
                    return $h;
                },
                'getCurrentUser' => function($sm) {
                    $h = new \Neoco\View\Helper\GetCurrentUser();
                    $h->setServiceLocator($sm->getServiceLocator());
                    return $h;
                },
                'renderMessages' => function($sm) {
                    $h = new \Neoco\View\Helper\RenderMessages();
                    $translator = $sm->getServiceLocator()->get('translator');
                    $h->setTranslator($translator);
                    return $h;
                },
                'renderFacebookButton' => function($sm) {
                    $facebook = $sm->getServiceLocator()->get('facebook');
                    $config = $sm->getServiceLocator()->get('config');
                    $facebookLoginButton = new \Neoco\View\Helper\FacebookLoginButton();
                    $facebookLoginButton->setFacebookAPI($facebook)
                                        ->setScope($config['facebook_permissions'])
                                        ->setRequest($sm->getServiceLocator()->get('Request'));


                    return $facebookLoginButton;
                },
                'renderDefaultAvatars' => function($sm){
                    $defaultAvatars = new \Neoco\View\Helper\DefaultAvatars();
                    $defaultAvatars->setServiceLocator($sm->getServiceLocator());
                    return $defaultAvatars;
                }
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
                            return md5($credential);
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
                'Application\Form\SetUpForm' => function($sm){
                    return new \Application\Form\SetUpForm($sm);
                },
                'Application\Form\Filter\RegistrationFilter' => function($sm){
                    return new \Application\Form\Filter\RegistrationFilter($sm);
                },
                'facebook' => function($sm){
                    $config = $sm->get('config');
                    $facebook = new \Facebook(array(
                        'appId' => $config['facebook_api_key'],
                        'secret' => $config['facebook_secret_key']
                    ));
                    return $facebook;
                }
            ),
        );
    }
}
