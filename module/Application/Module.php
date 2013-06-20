<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Form\RegistrationForm;
use Application\Manager\ContentManager;
use \Application\Manager\UserManager;
use \Application\Manager\ApplicationManager;
use \DoctrineModule\Authentication\Adapter\ObjectRepository;
use \Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Crypt\Password\Bcrypt;

class Module
{

    const SETUP_PAGE_ROUTE = 'setup';
    const PREDICT_PAGE_ROUTE = 'predict';
    const LOGIN_PAGE_ROUTE = 'login';

    public function onBootstrap(MvcEvent $e)
    {
        date_default_timezone_set('UTC');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sharedEvents = $eventManager->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, array($this, 'onAppDispatch'), 100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onAppDispatchError'), -1);
        $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'), 500);
    }

    public function onFinish(\Zend\Mvc\MvcEvent $e) {
        $response = $e->getResponse();
        if ($response instanceof \Zend\Http\AbstractMessage) {
            $header = new \Zend\Http\Header\ContentType();
            $header->value = 'text/html; charset=utf-8';
            $response->getHeaders()->addHeader($header);
        }
    }

    public function onAppDispatch(\Zend\Mvc\MvcEvent $e) {
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $user = ApplicationManager::getInstance($e->getApplication()->getServiceManager())->getCurrentUser();
        if ($user == null) {
            $userManager = UserManager::getInstance($e->getApplication()->getServiceManager());
            $language = $userManager->getUserLanguage()->getLanguageCode();
        } else
            $language = $user->getLanguage()->getLanguageCode();
        $translator->setLocale($language);
        $matches = $e->getRouteMatch();
        $detect = new \Neoco\Mobile\Detect();

        if ($matches != null) {
            if ($user != null) {
                $routeName = $matches->getMatchedRouteName();
                if (!$user->getIsActive()) {
                    if ($detect->isMobile() || $detect->isTablet()){
                        if ($routeName != self::SETUP_PAGE_ROUTE){
                            return $this->toRedirect(self::SETUP_PAGE_ROUTE, $e);
                        }
                    }else{
                        if ($e->getRequest()->isPost() && $routeName == self::SETUP_PAGE_ROUTE){
                            return true;
                        }
                        if ($routeName != self::PREDICT_PAGE_ROUTE || $e->getRequest()->isPost()){
                            return $this->toRedirect(self::PREDICT_PAGE_ROUTE, $e);
                        }
                    }
                }
            }
        }
        return true;
    }

    public function onAppDispatchError(\Zend\Mvc\MvcEvent $e) {
        $matches = $e->getRouteMatch();
        if ($matches != null) {
            $controller = $matches->getParam('controller');
            if (strpos($controller, __NAMESPACE__) === 0) {
                $response = $e->getResponse();
                if ($response->getStatusCode() == \Zend\Http\Response::STATUS_CODE_403) {
                    $response->setStatusCode(\Zend\Http\Response::STATUS_CODE_302);
                    $headers = new \Zend\Http\Headers();
                    $url = new \Zend\View\Helper\Url();
                    $url->setRouter($e->getApplication()->getServiceManager()->get('router'));
                    $headers->addHeaderLine("Location", $url->__invoke(self::LOGIN_PAGE_ROUTE));
                    $response->setHeaders($headers);
                    $response->send();
                    $e->stopPropagation();
                    return false;
                }
                // TODO to remove next 3 lines
                if ($response->getStatusCode() == \Zend\Http\Response::STATUS_CODE_404) {
                    die;
                }
            }
        }
        return true;
    }

    /**
     * @param string $route
     * @param \Zend\Mvc\MvcEvent $e
     * @return bool
     */
    private function toRedirect($route, \Zend\Mvc\MvcEvent $e) {
        $e->getTarget()->plugin('redirect')->toRoute($route);
        $e->stopPropagation();
        return false;
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
                    'I18n_Pofile' => 'vendor/poparser/I18n_Pofile.php'
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
                'getContent' => function($sm) {
                    $h = new \Neoco\View\Helper\GetContent();
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
                },
                'footerImage' => function($sm){
                    $footerImage = new \Neoco\View\Helper\FooterImage();
                    $footerImage->setServiceLocator($sm->getServiceLocator());
                    return $footerImage;
                },
                'footerSocials' => function($sm){
                    $footerSocials = new \Neoco\View\Helper\FooterSocials();
                    $footerSocials->setServiceLocator($sm->getServiceLocator());
                    return $footerSocials;
                },
                'settingsHelper' => function($sm){
                    $settingsHelper = new \Neoco\View\Helper\SettingsHelper();
                    $settingsHelper->setServiceLocator($sm->getServiceLocator());
                    return $settingsHelper;
                },
                'squadSelect' => function($sm) {
                    return new \Neoco\View\Helper\SquadSelect();
                },
                'clubLogo' => function($sm) {
                    $h = new \Neoco\View\Helper\ClubLogo();
                    $config = $sm->getServiceLocator()->get('config');
                    $h->setDefaultLogo($config['default_club_logo']);
                    return $h;
                },
                'getUserAvatar' => function($sm) {
                    return new \Neoco\View\Helper\GetUserAvatar();
                },
                'getUnreadItems' => function($sm) {
                    $h = new \Neoco\View\Helper\UnreadItems();
                    $h->setServiceLocator($sm->getServiceLocator());
                    return $h;
                },
                'getLeagueStats' => function($sm) {
                    $h = new \Neoco\View\Helper\LeagueStats();
                    $h->setServiceLocator($sm->getServiceLocator());
                    return $h;
                },
                'renderUserLeagueMovement' => function($sm){
                    $userLeagueMovement = new \Neoco\View\Helper\UserLeagueMovement();
                    $userLeagueMovement->setServiceLocator($sm->getServiceLocator());
                    return $userLeagueMovement;
                },
                'renderLogotype' => function($sm){
                    $logotype = new \Neoco\View\Helper\Logotype();
                    $logotype->setServiceLocator($sm->getServiceLocator());
                    return $logotype;
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
                    $registrationForm = new RegistrationForm($sm);
                    $terms = ContentManager::getInstance($sm)->getRegistrationFormTerms();
                    if (!empty($terms)){
                        $registrationForm->setTerms($terms);
                    }
                    $registrationForm->init();
                    return $registrationForm;
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
                },
                'poparser' => function($sm){
                     return new \I18n_Pofile();
                },
                'badWordValidator' => function($sm){
                    $badWordValidator = new \Neoco\Validator\BadWordValidator();
                    $badWordValidator->setServiceLocator($sm);
                    return $badWordValidator;
                },
                'confirmPasswordValidator' => function($sm){
                    $confirmPasswordValidator = new \Neoco\Validator\ConfirmPasswordValidator();
                    $user = ApplicationManager::getInstance($sm)->getCurrentUser();
                    if (!is_null($user)){
                        $confirmPasswordValidator->setPassword($user->getPassword());
                        return $confirmPasswordValidator;
                    }
                    return $confirmPasswordValidator;
                }
            ),
        );
    }
}
