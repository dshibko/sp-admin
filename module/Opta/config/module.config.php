<?php

return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'dispatch' => array(
                    'options' => array(
                        'route'    => 'opta --dispatch',
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
