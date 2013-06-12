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
    'app_name' => 'Score Predictor',
    'admin_assets_path_prefix' => '/admin-',
    'skip-cache-uri-patterns' => array('/admin/*'),
    'is_geo_ip_blocked' => false,
    'facebook_api_key' => '639647056062341',
    'facebook_secret_key' => '73bc16bfe1d05f4c213a34a6c2d2ebfa',
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
    'default_club_logo' => '/img/club-logo.png',
    'default_pre_match_report_title' => 'Match Report',
    'default_pre_match_report_intro' => 'This is default match report intro',
    'default_pre_match_report_header_image' => '/img/match-report.png',
    'default_post_match_report_title' => 'Post Match Report',
    'default_post_match_report_intro' => 'This is default post match report intro',
    'default_post_match_report_header_image' => '/img/match-report.png',
    'default_player_background' => '/img/default-player-bg.png',
    'default_player_avatar' => '/img/default_player.png',
);