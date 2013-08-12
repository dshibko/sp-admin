<?php
use \Application\Model\Entities\Role;

$anonymousRole = Role::GUEST;

return array(
    'zfcrbac' => array(
        'enableLazyProviders' => true,
        'template' => 'error/admin-redirect',
        'firewallRoute' => true,
        'firewallController' => false,
        'firewalls' => array(
            'ZfcRbac\Firewall\Route' => array(
                array('route' => 'admin-login', 'roles' => Role::GUEST),
                array('route' => 'admin-forgot', 'roles' => Role::GUEST),
                array('route' => 'admin-home', 'roles' => Role::GUEST),
                array('route' => 'custom', 'roles' => Role::GUEST),
                array('route' => 'admin', 'roles' => Role::REGIONAL_MANAGER),
                array('route' => 'predict', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'tables', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'full-table', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'prizes', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'fixtures', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'results', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'setup', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'user-settings', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'delete-account', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'how-to-play', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'logout', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'create-private-league', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'join-private-league', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'delete-private-league', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'leave-private-league', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
                array('route' => 'remove-user-from-private-league', 'roles' => array(Role::USER, Role::REGIONAL_MANAGER, Role::SUPER_ADMIN)),
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
                else {
                    $user = \Application\Manager\ApplicationManager::getInstance($sm)->getCurrentUser();
                    if ($user === null)
                        return $anonymousRole;
                    else
                        return $user->getRole()->getName();
                }
            }
        )
    )
);