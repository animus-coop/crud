<?php

return [
    'files_upload_path' => 'uploads',
    'views_path_prefix' => 'admin',
    'menu'  => [
        [
            'group' => 'Dashboard',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'subitems'  => [
                        [
                            'label' => 'Dashboard',
                            'route' => 'admin.dashboard',
                            'roles' => [1,2],//admin and another one
                            'icon'  => 'fas fa-fire'
                        ]
                    ]
                ],
                [
                    'label' => 'General',
                    'subitems'  => [
                        [
                            'label' => 'Usuarios',
                            'route' => 'user.index',
                            'roles' => [1],//only admin
                            'icon'  => 'fa fa-users'
                        ]
                    ]
                ]
            ]
        ]
    ]
];
