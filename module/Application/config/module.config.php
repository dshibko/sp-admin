<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'predict' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/predict[/][ahead-:ahead]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Predict',
                        'action'     => 'index',
                    ),
                ),
            ),
            'tables' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/tables[/][table-:table]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Tables',
                        'action'     => 'index',
                    ),
                ),
            ),
            'full-table' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/tables/full-table-:table',
                    'defaults' => array(
                        'controller' => 'Application\Controller\FullTable',
                        'action'     => 'index',
                    ),
                ),
            ),
            'create-private-league' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/create-private-league',
                    'defaults' => array(
                        'controller' => 'Application\Controller\PrivateLeague',
                        'action'     => 'create',
                    ),
                ),
            ),
            'join-private-league' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => 'join-private-league',
                    'defaults' => array(
                        'controller' => 'Application\Controller\PrivateLeague',
                        'action'     => 'join',
                    ),
                ),
            ),
            'prizes' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/prizes/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Prize',
                        'action'     => 'index',
                    ),
                ),
            ),
            'fixtures' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/fixtures[/]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Fixtures',
                        'action'     => 'index',
                    ),
                ),
            ),
            'results' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/results[/][back-:back]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Results',
                        'action'     => 'index',
                    ),
                ),
            ),
            'match' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/match/:code',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Match',
                        'action'     => 'index',
                    ),
                ),
            ),
            'registration' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/registration',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Registration',
                        'action'     => 'index',
                    ),
                ),
            ),
            'setup' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/setup',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Registration',
                        'action'     => 'setup',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Auth',
                        'action'     => 'logout',
                    ),
                ),
            ),
            'facebook' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/facebook',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Registration',
                        'action'     => 'facebookLogin',
                    ),
                ),
            ),
            'reset' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/reset[/:hash]',
                    'constraints' => array(
                        'hash' => '[a-z0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'reset',
                    ),
                ),
            ),
            'forgot' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/forgot',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'forgot',
                    ),
                ),
            ),
            'user-settings' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/settings[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'User',
                        'action'        => 'settings',
                    ),
                ),
            ),
            'delete-account' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/delete[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'User',
                        'action'        => 'delete',
                    ),
                ),
            ),
            'deauthorise-facebook-app' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/deauthorise',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'User',
                        'action'        => 'deAuthoriseFacebookApp',
                    ),
                ),
            ),
            //Privacy Page
            'privacy' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/privacy',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Content',
                        'action'        => 'privacy',
                    ),
                ),
            ),
            //Terms Page
            'terms' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/terms',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Content',
                        'action'        => 'terms',
                    ),
                ),
            ),
            //How to Play Page
            'how-to-play' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/how-to-play[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Content',
                        'action'        => 'howToPlay',
                    ),
                ),
            ),
            //Help Page
            'help' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/help',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Content',
                        'action'        => 'help',
                    ),
                ),
            ),

            //Cookies Page
            'cookies' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/cookies',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Content',
                        'action'        => 'cookies',
                    ),
                ),
            ),
            //Facebook Canvas Page
            'facebook-canvas' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/canvas/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Content',
                        'action'        => 'facebookCanvas',
                    ),
                ),
            ),
            //Contact Page
            'contact' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/contact',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Content',
                        'action'        => 'contact',
                    ),
                ),
            ),
            '500' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/500',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Common',
                        'action'     => 'error500',
                    ),
                ),
            ),
            //Common Requests
            'common' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/common/:action',
                    'defaults' => array(
                        'controller'    => 'Application\Controller\Common',
                    ),
                ),
            ),
            'clear-app-cache' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/clear-app-cache[/][:entities]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'ClearAppCache',
                        'action'        => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_EN',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Registration' => 'Application\Controller\RegistrationController',
            'Application\Controller\Auth' => 'Application\Controller\AuthController',
            'Application\Controller\User' => 'Application\Controller\UserController',
            'Application\Controller\Predict' => 'Application\Controller\PredictController',
            'Application\Controller\Fixtures' => 'Application\Controller\FixturesController',
            'Application\Controller\Results' => 'Application\Controller\ResultsController',
            'Application\Controller\Content' => 'Application\Controller\ContentController',
            'Application\Controller\Tables' => 'Application\Controller\TablesController',
            'Application\Controller\FullTable' => 'Application\Controller\FullTableController',
            'Application\Controller\Match' => 'Application\Controller\MatchController',
            'Application\Controller\Prize' => 'Application\Controller\PrizeController',
            'Application\Controller\Common' => 'Application\Controller\CommonController',
            'Application\Controller\ClearAppCache' => 'Application\Controller\ClearAppCacheController',
            'Application\Controller\PrivateLeague' => 'Application\Controller\PrivateLeagueController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'formErrors' => 'Neoco\Controller\Plugin\FormErrors',
        )
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Model/Entities')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Model' => 'application_entities'
                )
            )
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'partials/header'         => __DIR__ . '/../view/partials/header.phtml',
            'partials/footer'         => __DIR__ . '/../view/partials/footer.phtml',
            'partials/cookie-bar'     => __DIR__ . '/../view/partials/cookie-bar.phtml',
            'partials/menu'           => __DIR__ . '/../view/partials/menu.phtml',
            'partials/invite-friends' => __DIR__ . '/../view/partials/invite-friends.phtml',
            'partials/right-column'   => __DIR__ . '/../view/partials/right-column.phtml',
            'application/content/terms' => __DIR__ . '/../view/application/content/footer-page.phtml',
            'application/content/privacy' => __DIR__ . '/../view/application/content/footer-page.phtml',
            'application/content/cookies' => __DIR__ . '/../view/application/content/footer-page.phtml',
            'application/content/contact' => __DIR__ . '/../view/application/content/footer-page.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'navigation' => array(
        'default' => array(
            'predict' => array(
                'title' => 'Predict',
                'route' => 'predict',
            ),
            'results' => array(
                'title' => 'Results',
                'route' => 'results',
            ),
            'fixtures' => array(
                'title' => 'Fixtures',
                'route' => 'fixtures',
            ),
            'tables' => array(
                'title' => 'League Tables',
                'route' => 'tables',
                'pages' => array(
                    'full-table' => array(
                        'title' => 'Full Table',
                        'route' => 'full-table',
                    ),
                ),
            ),
            'prize' => array(
                'title' => 'Prizes',
                'route' => 'prizes',
            ),
        ),
    ),
);
