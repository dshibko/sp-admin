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
            'admin-leagues' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/leagues[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'League',
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
            'admin-clubs' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/clubs/[:action][/:club]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'club' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Clubs',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-players' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/players/[:action][/:player]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'player' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Players',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-fixtures' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/fixtures/[:action][/:fixture]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'fixture' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Fixtures',
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
            'admin-content-footer-pages' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/content/footer-pages[/:action][/:pageType]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'pageType' => '[a-zA-Z][a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\FooterPages',
                        'action'     => 'index',
                    ),
                ),
            ),

            /*'admin-content-footer-pages-terms' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/content/footer-pages/terms',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\FooterPages',
                        'action'     => 'termsPage',
                    ),
                ),
            ),*/
            'admin-pre-match-share-copy' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/pre-match-share-copy/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\PreMatchShareCopy',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-post-match-share-copy' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/post-match-share-copy/',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\PostMatchShareCopy',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin-content-footer-images' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/content/footer-images/[region-:region][/:action][/image-:image]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'region' => '[0-9]+',
                        'image' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Content',
                        'action'     => 'footerImages',
                    ),
                ),
            ),
            'admin-content-footer-socials' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/admin/content/footer-socials/[region-:region][/:action][/social-:social]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'region' => '[0-9]+',
                        'social' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Content',
                        'action'     => 'footerSocials',
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
            'Admin\Controller\Season' => 'Admin\Controller\SeasonController',
            'Admin\Controller\Settings' => 'Admin\Controller\SettingsController',
            'Admin\Controller\Languages' => 'Admin\Controller\LanguagesController',
            'Admin\Controller\Clubs' => 'Admin\Controller\ClubsController',
            'Admin\Controller\Players' => 'Admin\Controller\PlayersController',
            'Admin\Controller\League' => 'Admin\Controller\LeagueController',
            'Admin\Controller\Fixtures' => 'Admin\Controller\FixturesController',
            'Admin\Controller\User' => 'Admin\Controller\UserController',
            'Admin\Controller\FooterPages' => 'Admin\Controller\FooterPagesController',
            'Admin\Controller\PreMatchShareCopy' => 'Admin\Controller\PreMatchShareCopyController',
            'Admin\Controller\PostMatchShareCopy' => 'Admin\Controller\PostMatchShareCopyController',
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
            'admin/season/edit' => __DIR__ . '/../view/admin/season/add.phtml',
            'admin/content/edit-block' => __DIR__ . '/../view/admin/content/add-block.phtml',
            'admin/content/edit-footer-social' => __DIR__ . '/../view/admin/content/add-footer-social.phtml',
            'error/admin-redirect' => __DIR__ . '/../view/error/redirect.phtml',
            'admin/partials/breadcrumbs' => __DIR__ . '/../view/partials/breadcrumbs.phtml',
            'admin/partials/menu' => __DIR__ . '/../view/partials/menu.phtml',
            'admin/partials/select' => __DIR__ . '/../view/partials/select.phtml',
            'admin/languages/edit' => __DIR__ . '/../view/admin/languages/add.phtml',
            'admin/league/edit-mini-league' => __DIR__ . '/../view/admin/league/add-mini-league.phtml',
            'admin/fixtures/add' => __DIR__ . '/../view/admin/fixtures/edit.phtml'
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
                    'users' => array(
                        'title' => 'Users',
                        'label' => 'icon-underline',
                        'route' => 'admin-users',
                        'pages' => array(
                            array(
                                'title' => 'View',
                                'label' => 'icon-eye-open',
                                'route' => 'admin-user-view',
                            ),
                        )
                    ),
                    'admin-account' => array(
                        'title' => 'My Account',
                        'label' => 'icon-user',
                        'route' => 'admin-account',
                        'exclude-from-menu' => true,
                    ),
                    'seasons' => array(
                        'title' => 'Seasons',
                        'label' => 'icon-calendar',
                        'route' => 'admin-seasons',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'title' => 'Create Season',
                                'label' => 'icon-plus',
                                'route' => 'admin-seasons',
                                'action' => 'add',
                            ),
                            array(
                                'title' => 'Edit Season',
                                'label' => 'icon-edit',
                                'route' => 'admin-seasons',
                                'action' => 'edit',
                            ),
                            array(
                                'title' => 'Delete Season',
                                'label' => 'icon-minus',
                                'route' => 'admin-seasons',
                                'action' => 'delete',
                            ),
                        )
                    ),
                    'leagues' => array(
                        'title' => 'Leagues',
                        'label' => 'icon-table',
                        'route' => 'admin-leagues',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'title' => 'Add Mini League',
                                'label' => 'icon-plus',
                                'route' => 'admin-leagues',
                                'action' => 'addMiniLeague',
                            ),
                            array(
                                'title' => 'Edit Mini League',
                                'label' => 'icon-edit',
                                'route' => 'admin-leagues',
                                'action' => 'editMiniLeague',
                            ),
                            array(
                                'title' => 'Edit League',
                                'label' => 'icon-edit',
                                'route' => 'admin-leagues',
                                'action' => 'edit',
                            ),
                            array(
                                'title' => 'Delete League',
                                'label' => 'icon-minus',
                                'route' => 'admin-leagues',
                                'action' => 'delete',
                            )
                        )
                    ),
                    'clubs' => array(
                        'title' => 'Clubs',
                        'label' => 'icon-group',
                        'route' => 'admin-clubs',
                        'action' => 'index',
                        'pages'  => array(
                            array(
                                'title' => 'Edit Club',
                                'label' => 'icon-edit',
                                'route' => 'admin-clubs',
                                'action' => 'edit',
                            ),
                        )
                    ),
                    'players' => array(
                        'title' => 'Players',
                        'label' => 'icon-user',
                        'route' => 'admin-players',
                        'action' => 'index',
                        'pages'  => array(
                            array(
                                'title' => 'Edit Player',
                                'label' => 'icon-edit',
                                'route' => 'admin-players',
                                'action' => 'edit',
                            ),
                        )
                    ),
                    'fixtures' => array(
                        'title' => 'Fixtures',
                        'label' => 'icon-time',
                        'route' => 'admin-fixtures',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'title' => 'Add Fixture',
                                'label' => 'icon-plus',
                                'route' => 'admin-fixtures',
                                'action' => 'add',
                            ),
                            array(
                                'title' => 'Edit Fixture',
                                'label' => 'icon-edit',
                                'route' => 'admin-fixtures',
                                'action' => 'edit',
                            ),
                        )
                    ),
                    'content' => array(
                        'title' => 'Content',
                        'label' => 'icon-tasks',
                        'route' => 'admin-content',
                        'sub-menu' => true,
                        'pages' => array(
                            'landing' => array(
                                'title' => 'Landing',
                                'label' => 'icon-list-alt',
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
                            'languages' => array(
                                'title' => 'Languages',
                                'label' => 'icon-refresh',
                                'route' => 'admin-content-languages',
                                'pages' => array(
                                    array(
                                        'title' => 'Add New Language',
                                        'label' => 'icon-plus',
                                        'route' => 'admin-content-languages',
                                        'action' => 'add',
                                    ),
                                    array(
                                        'title' => 'Edit Language',
                                        'label' => 'icon-edit',
                                        'route' => 'admin-content-languages',
                                        'action' => 'edit',
                                    ),
                                ),
                            ),
                            'share-copy' => array(
                                'title' => 'Reports Share Copy',
                                'label' => 'icon-bar-chart',
                                'route' => 'admin-pre-match-share-copy',//TODO CHANGED !!!!!!!!!!!!!!!
                            ),
                            'footer-images' => array(
                                'title' => 'Footer Images',
                                'label' => 'icon-picture',
                                'route' => 'admin-content-footer-images',
                            ),
                            'footer-social' => array(
                                'title' => 'Footer Socials',
                                'label' => 'icon-twitter',
                                'route' => 'admin-content-footer-socials',
                                'useRouteMatch' => true,
                                'pages' => array(
                                    array(
                                        'title' => 'Add Footer Social',
                                        'label' => 'icon-plus',
                                        'route' => 'admin-content-footer-socials',
                                        'action' => 'addFooterSocial',
                                    ),
                                    array(
                                        'title' => 'Edit Footer Social',
                                        'label' => 'icon-edit',
                                        'route' => 'admin-content-footer-socials',
                                        'action' => 'editFooterSocial',
                                    ),
                                ),
                            ),
                            'footer-pages' => array(
                                'title' => 'Footer Pages',
                                'label' => 'icon-pencil',
                                'route' => 'admin-content-footer-pages',
                                'sub-menu' => true,
                                'pages' => array(
                                    array(
                                        'title' => 'Terms',
                                        'label' => 'icon-plus',
                                        'route' => 'admin-content-footer-pages',
                                        'action' => 'page',
                                        'params' => array(
                                            'pageType' => \Application\Model\Entities\FooterPage::TERMS_PAGE
                                        )
                                    ),
                                    array(
                                        'title' => 'Privacy',
                                        'label' => 'icon-plus',
                                        'route' => 'admin-content-footer-pages',
                                        'action' => 'page',
                                        'params' => array(
                                            'pageType' => \Application\Model\Entities\FooterPage::PRIVACY_PAGE
                                        )
                                    ),
                                    array(
                                        'title' => 'Contact Us',
                                        'label' => 'icon-plus',
                                        'route' => 'admin-content-footer-pages',
                                        'action' => 'page',
                                        'params' => array(
                                            'pageType' => \Application\Model\Entities\FooterPage::CONTACT_US_PAGE
                                        )
                                    ),
                                    array(
                                        'title' => 'Cookies Policy',
                                        'label' => 'icon-plus',
                                        'route' => 'admin-content-footer-pages',
                                        'action' => 'page',
                                        'params' => array(
                                            'pageType' => \Application\Model\Entities\FooterPage::COOKIES_PAGE
                                        )
                                    )
                                )
                            ),
                        ),
                    ),
                    'settings' => array(
                        'title' => 'Settings',
                        'label' => 'icon-cogs',
                        'route' => 'admin-settings',
                    ),

                ),
            ),
        ),
    ),
);
