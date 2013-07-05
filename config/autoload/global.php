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
                    'dbname' => 'dbname', // to change
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

    'club_name' => 'TrueFan Utd',
    'app_name' => 'Score Predictor',
    'admin_assets_path_prefix' => '/admin-',
    'skip-cache-uri-patterns' => array('/admin/*'),
    'is_geo_ip_blocked' => false,
    'show_error_messages' => false,
    'facebook_api_key' => '',// to change
    'facebook_secret_key' => '',// to change
    'facebook_permissions' => 'email,
                              user_likes,
                              friends_likes,
                              user_birthday,
                              friends_birthday,
                              user_location,
                              friends_location,
                              user_relationships,
                              user_relationship_details,
                              user_status,
                              user_checkins,
                              user_education_history,
                              user_work_history,
                              publish_actions',
    'favicon_path' => '/img/fav.ico',
    'default_club_logo' => '/img/club-logo.png',
    'default_player_background' => '/img/default-player-bg.png',
    'move_up_image_source' => '/img/up-place.png',
    'move_down_image_source'  => '/img/down-place.png',
    'default_logotype_image_source' => '/img/del-tf-logo.png'
);