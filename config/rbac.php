<?php

return [
    /* Configurations for application */
    'role' => [
        // Default role assigned to users when they are created
        // This should match the default role in your RBAC configuration
        'default' => 'user',

        // Highest role in the hierarchy, typically for administrators
        // This should match the highest role in your RBAC configuration
        // It is used to determine the highest level of access in the system
        'highest' => 'superadmin',
    ],

    /* List of roles and permissions */
    'list' => [
        'roles' => [
            'superadmin',
            'admin',
            'user',
        ],
        'permissions' => [
            'dashboard.view',

            'dashboard.view_map',
            'dashboard.manage_iot',

            'dashboard.filter_iot',
            'dashboard.create_user',
            'dashboard.view_user',
            'dashboard.edit_user',
            'dashboard.delete_user',

            'history.view',
            'history.filter',

            'profile.view',
            'profile.edit.basic',
            'profile.edit.other',
            'profile.edit.credential',

            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'user.filter',

            'statistic.view',

            'irrigation_setting.view',
            'irrigation_setting.update',
        ],
    ],

    /* Roles that can assign other roles */
    'assign' => [
        'superadmin' => ['superadmin', 'admin', 'user'],
        'admin' => ['admin', 'user'],
        'user' => ['user'],
    ],

    /* Permissions for each role */
    'permissions' => [
        'superadmin' => [
            'dashboard.view',

            'dashboard.filter_iot',
            'dashboard.create_user',
            'dashboard.view_user',
            'dashboard.edit_user',
            'dashboard.delete_user',

            'history.view',
            'history.filter',

            'profile.view',
            'profile.edit.basic',
            'profile.edit.credential',

            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'user.filter',

            'statistic.view',
        ],
        'admin' => [
            'dashboard.view',

            'dashboard.filter_iot',
            'dashboard.create_user',
            'dashboard.view_user',
            'dashboard.edit_user',
            'dashboard.delete_user',

            'history.view',
            'history.filter',

            'profile.view',
            'profile.edit.basic',
            'profile.edit.other',
            'profile.edit.credential',

            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'user.filter',

            'statistic.view',
        ],
        'user' => [
            'dashboard.view',

            'dashboard.view_map',
            'dashboard.manage_iot',

            'history.view',
            'history.filter',

            'profile.view',
            'profile.edit.basic',
            'profile.edit.other',
            'profile.edit.credential',

            'irrigation_setting.view',
            'irrigation_setting.update',
        ],
    ],
];
