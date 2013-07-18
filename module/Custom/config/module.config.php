<?php

return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'custom-export' => array(
                    'options' => array(
                        'route'    => 'export <action>',
                        'defaults' => array(
                            'controller' => 'Custom\Controller\Export',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Custom\Controller\Export' => 'Custom\Controller\CustomExportController',
        ),
    ),
);
