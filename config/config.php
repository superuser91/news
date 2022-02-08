<?php

return [
    'guard' => 'admin',
    'guard_model' => 'App\Models\Admin',
    'news' => [
        'prefix' => 'admin',
        'middleware' => ['auth:admin'],
        'posts' => [
            'policy' => 'App\\Policies\\PostPolicy',
            'route_show' => 'posts.show',
            'statuses' => [
                'PUBLISHED' => 'Xuất bản',
                'DRAFT' => 'Nháp',
            ],
        ],
        'categories' => [
            'policy' => 'App\\Policies\\CategoryPolicy',
            'route_show' => 'categories.show',
        ]
    ]
];
