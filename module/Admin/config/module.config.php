<?php

return array(
    'router' => array(
        'routes' => array(
            'admin-home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-account' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/account',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Index',
                        'action'     => 'myAccount',
                    ),
                ),
            ),
            'admin-login' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'process' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action]',
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
            'admin-forgot' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/forgot[/:hash]',
                    'constraints' => array(
                        'hash' => '[a-z0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'forgot',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
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
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/admin-layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/admin-login-layout'           => __DIR__ . '/../view/layout/layout2.phtml',
            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
            'error/admin-redirect' => __DIR__ . '/../view/error/redirect.phtml',
            'admin/partials/breadcrumbs' => __DIR__ . '/../view/partials/breadcrumbs.phtml',
            'admin/partials/menu' => __DIR__ . '/../view/partials/menu.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'navigation' => array(
        'default' => array(
            'admin' => array(
                'title' => 'Home',
                'label' => 'icon-home',
                'route' => 'admin-home',
                'pages' => array(
                    'home' => array(
                        'title' => 'Dashboard',
                        'label' => 'icon-dashboard',
                        'route' => 'admin-home',
                    ),
                ),
            ),
        ),
    ),
);
