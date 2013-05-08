<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'host', // to change
                    'port' => 'port', // to change
                    'user' => 'user', // to change
                    'password' => 'password', // to change
                    'dbname' => 'dbname' // to change
                )
            )
        ),
        'configuration' => array(
            'orm_default' => array(
                'generate_proxies'  => false,
                'metadata_cache'    => 'apc',
                'query_cache'       => 'apc',
                'result_cache'      => 'apc',
            )
        ),
    ),
    'email' => array(
        'fromEmail' => 'hello@neoco.com',
        'fromName' => 'Score Predictor',
    ),
    'app_name' => 'Score Predictor',
    'admin_assets_path_prefix' => '/admin-',
    'skip-cache-uri-patterns' => array('/admin/*')
);