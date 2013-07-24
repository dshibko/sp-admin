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
                'custom-hot-fix' => array(
                    'options' => array(
                        'route'    => 'hot-fix <action>',
                        'defaults' => array(
                            'controller' => 'Custom\Controller\HotFix',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Custom\Controller\Export' => 'Custom\Controller\CustomExportController',
            'Custom\Controller\HotFix' => 'Custom\Controller\CustomHotFixController',
        ),
    ),
);
