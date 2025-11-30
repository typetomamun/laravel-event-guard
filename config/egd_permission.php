<?php

return [
    /*
    |--------------------------------------------------------------------------
    | EventGuard Models
    |--------------------------------------------------------------------------
    */
    'models' => [
        'permission' => App\Models\EventGuard\EGDPermission::class,
        'role' => App\Models\EventGuard\EGDRole::class,
        'event' => App\Models\EventGuard\EGDEvent::class,
        'event_type' => App\Models\EventGuard\EGDEventType::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | EventGuard Table Names
    |--------------------------------------------------------------------------
    */
    'table_names' => [
        'event_types' => 'egd_event_types',
        'events' => 'egd_events',
        'roles' => 'egd_roles',
        'permissions' => 'egd_permissions',
        'role_permission' => 'egd_role_permission',
        'model_has_permissions' => 'egd_model_has_permissions',
        'model_has_roles' => 'egd_model_has_roles',
    ],

    /*
    |--------------------------------------------------------------------------
    | Column Names
    |--------------------------------------------------------------------------
    */
    'column_names' => [
        'role_pivot_key' => null,
        'permission_pivot_key' => null,
        'model_morph_key' => 'model_id',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'eventguard.permission.cache',
        'store' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Event Types
    |--------------------------------------------------------------------------
    */
    'event_types' => [
        'shop' => [
            'name' => 'Shop',
            'description' => 'E-commerce shop',
            'roles' => ['owner', 'manager', 'staff', 'customer'],
            'permissions' => [
                'view shop',
                'edit shop',
                'delete shop',
                'manage products',
                'manage orders',
                'manage staff',
                'view reports',
                'manage settings',
            ],
        ],
        'forum' => [
            'name' => 'Forum',
            'description' => 'Discussion forum',
            'roles' => ['owner', 'moderator', 'member', 'guest'],
            'permissions' => [
                'view forum',
                'edit forum',
                'delete forum',
                'create topics',
                'edit topics',
                'delete topics',
                'create replies',
                'edit replies',
                'delete replies',
                'manage members',
                'moderate content',
                'pin topics',
                'lock topics',
            ],
        ],
        'announcement' => [
            'name' => 'Announcement',
            'description' => 'Announcement board',
            'roles' => ['owner', 'editor', 'viewer'],
            'permissions' => [
                'view announcements',
                'create announcements',
                'edit announcements',
                'delete announcements',
                'publish announcements',
                'schedule announcements',
            ],
        ],
        'group' => [
            'name' => 'Group',
            'description' => 'User group or community',
            'roles' => ['owner', 'admin', 'moderator', 'member'],
            'permissions' => [
                'view group',
                'edit group',
                'delete group',
                'invite members',
                'remove members',
                'manage posts',
                'manage events',
                'manage settings',
            ],
        ],
    ],
];