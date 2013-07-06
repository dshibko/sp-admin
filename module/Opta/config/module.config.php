<?php

return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'opta' => array(
                    'options' => array(
                        'route'    => 'opta <type>',
                        'defaults' => array(
                            'controller' => 'Opta\Controller\Dispatcher',
                            'action'     => 'dispatch'
                        ),
                    ),
                ),
            ),
        ),
    ),
//    'router' => array(
//        'routes' => array(
//            'clear-app-cache' => array(
//                'type' => 'segment',
//                'options' => array(
//                    'route'    => '/clear-app-cache[/][:entities]',
//                    'defaults' => array(
//                        'controller' => 'Opta\Controller\ClearAppCache',
//                        'action'     => 'index',
//                    ),
//                ),
//            ),
//        ),
//    ),
    'controllers' => array(
        'invokables' => array(
            'Opta\Controller\Dispatcher' => 'Opta\Controller\DispatcherController',
            'Opta\Controller\ClearAppCache' => 'Opta\Controller\ClearAppCacheController',
        ),
    ),
);
