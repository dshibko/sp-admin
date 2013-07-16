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
    'controllers' => array(
        'invokables' => array(
            'Opta\Controller\Dispatcher' => 'Opta\Controller\DispatcherController',
        ),
    ),
);
