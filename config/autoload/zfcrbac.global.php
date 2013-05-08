<?php

$anonymousRole = 'Guest';

return array(
    'zfcrbac' => array(
        'enableLazyProviders' => true,
        'template' => 'error/admin-redirect',
        'firewallRoute' => true,
        'firewalls' => array(
            'ZfcRbac\Firewall\Route' => array(
                array('route' => 'admin-login', 'roles' => 'Guest'),
                array('route' => 'admin-forgot', 'roles' => 'Guest'),
                array('route' => 'admin', 'roles' => 'Regional Manager'),
            ),
        ),
        'providers' => array(
            'ZfcRbac\Provider\AdjacencyList\Role\DoctrineDbal' => array(
                'connection' => 'doctrine.connection.orm_default',
                'options' => array(
                    'table'         => 'role',
                    'id_column'     => 'id',
                    'name_column'   => 'name',
                    'join_column'   => 'parent_id'
                )
            ),
            'ZfcRbac\Provider\Generic\Permission\DoctrineDbal' => array(
                'connection' => 'doctrine.connection.orm_default',
                'options' => array(
                    'permission_table'      => 'permission',
                    'role_table'            => 'role',
                    'role_join_table'       => 'role_permission',
                    'permission_id_column'  => 'id',
                    'permission_join_column'=> 'perm_id',
                    'role_id_column'        => 'id',
                    'role_join_column'      => 'role_id',
                    'permission_name_column'=> 'name',
                    'role_name_column'      => 'name'
                )
            ),
        ),
        'identity_provider' => 'standard_identity'
    ),
    'service_manager' => array(
        'factories' => array(
            'standard_identity' => function ($sm) use ($anonymousRole) {
                $identity = $sm->get('AuthService')->getIdentity();
                if ($identity == null) return $anonymousRole;
                else
                    return \Application\Manager\ApplicationManager::getInstance($sm)->getCurrentUser()->getRole()->getName();
            }
        )
    )
);