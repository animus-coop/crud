<?php

return [
    'files_upload_path' => 'uploads',
    'views_path_prefix' => 'admin',
    'menu'  => [
        [
            'group' => 'Dashboard',
            'items' => [
                [
                    'roles' => [1,2],//admin and another one
                    'label' => 'Dashboard',
                    'subitems'  => [
                        [
                            'label' => 'Dashboard',
                            'route' => 'admin.dashboard',
                            'icon'  => 'fas fa-fire'
                        ]
                    ]
                ],
                [
                    'label' => 'General',
                    'roles' => [1],//admin
                    'subitems'  => [
                        [
                            'label' => 'Usuarios',
                            'route' => 'user.index',
                            'icon'  => 'fa fa-users'
                        ]
                    ]
                ]
            ]
        ]
    ]
];
