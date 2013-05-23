<?php

return array(
    'router' => array(
        'routes' => array(
            'admin-home' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/admin[/]',
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
            'admin-users' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/users/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\User',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-user-view' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/users/:id',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\User',
                        'action'     => 'view',
                    ),
                ),
            ),
            'admin-users-export' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/users/export',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\User',
                        'action'     => 'export',
                    ),
                ),
            ),
            'admin-seasons' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/seasons[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Season',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-content' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/content/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Content',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-content-landing' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/content/landing/[region-:region][/:action][/block-:block]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'region' => '[0-9]+',
                        'block' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Content',
                        'action'     => 'landing',
                    ),
                ),
            ),
            'admin-content-reports' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/content/reports/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Content',
                        'action'     => 'reports',
                    ),
                ),
            ),
            'admin-content-languages' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/content/languages/[:action][/language-:language]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'language' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Languages',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-settings' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/settings/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Settings',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-settings-region-language' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/settings/region-language/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Settings',
                        'action'     => 'region',
                    ),
                ),
            ),
            'admin-settings-footer-images' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/settings/footer-images/[region-:region][/:action][/image-:image]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'region' => '[0-9]+',
                        'image' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Settings',
                        'action'     => 'footerImages',
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
            'Admin\Controller\Content' => 'Admin\Controller\ContentController',
            'Admin\Controller\Settings' => 'Admin\Controller\SettingsController',
            'Admin\Controller\Languages' => 'Admin\Controller\LanguagesController',
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
            'admin/content/edit-block' => __DIR__ . '/../view/admin/content/add-block.phtml',
            'error/admin-redirect' => __DIR__ . '/../view/error/redirect.phtml',
            'admin/partials/breadcrumbs' => __DIR__ . '/../view/partials/breadcrumbs.phtml',
            'admin/partials/menu' => __DIR__ . '/../view/partials/menu.phtml',
            'admin/partials/select' => __DIR__ . '/../view/partials/select.phtml',
            'admin/languages/edit' => __DIR__ . '/../view/admin/languages/add.phtml'
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
                    'content' => array(
                        'title' => 'Content',
                        'label' => 'icon-tasks',
                        'route' => 'admin-content',
                        'sub-menu' => true,
                        'pages' => array(
                            'landing' => array(
                                'title' => 'Landing',
                                'route' => 'admin-content-landing',
                                'useRouteMatch' => true,
                                'pages' => array(
                                    array(
                                        'title' => 'Add Gameplay Block',
                                        'label' => 'icon-plus',
                                        'route' => 'admin-content-landing',
                                        'action' => 'addBlock',
                                    ),
                                    array(
                                        'title' => 'Edit Gameplay Block',
                                        'label' => 'icon-edit',
                                        'route' => 'admin-content-landing',
                                        'action' => 'editBlock',
                                    ),
                                    array(
                                        'title' => 'Delete Gameplay Block',
                                        'label' => 'icon-minus',
                                        'route' => 'admin-content-landing',
                                        'action' => 'deleteBlock',
                                    ),
                                ),
                            ),
                            'reports' => array(
                                'title' => 'Match Reports',
                                'route' => 'admin-content-reports',
                            ),
                            'languages' => array(
                                'title' => 'Languages',
                                'route' => 'admin-content-languages',
                            ),
                        ),
                    ),
                    'settings' => array(
                        'title' => 'Settings',
                        'label' => 'icon-cogs',
                        'route' => 'admin-settings',
                        'sub-menu' => true,
                        'pages' => array(
                            'region-language' => array(
                                'title' => 'Region/Language',
                                'route' => 'admin-settings-region-language',
                            ),
                            'footer-images' => array(
                                'title' => 'Footer Images',
                                'route' => 'admin-settings-footer-images',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
