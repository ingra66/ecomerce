<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filament Path
    |--------------------------------------------------------------------------
    |
    | The default is `admin` but you can change it to whatever works best and
    | doesn't conflict with the routing in your application.
    |
    */

    'path' => env('FILAMENT_PATH', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Filament Core Path
    |--------------------------------------------------------------------------
    |
    | This is the path which Filament will use to load its core routes and assets.
    | You may change it if it conflicts with your other routes.
    |
    */

    'core_path' => env('FILAMENT_CORE_PATH', 'filament'),

    /*
    |--------------------------------------------------------------------------
    | Filament Domain
    |--------------------------------------------------------------------------
    |
    | You may change the domain where Filament should be active. If the domain
    | is empty, all domains will be valid.
    |
    */

    'domain' => env('FILAMENT_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Homepage URL
    |--------------------------------------------------------------------------
    |
    | This is the URL that Filament will redirect the user to when they click
    | on the sidebar logo.
    |
    */

    'home_url' => '/',

    /*
    |--------------------------------------------------------------------------
    | Brand Name
    |--------------------------------------------------------------------------
    |
    | This is the name that will be displayed in the sidebar of the admin panel.
    |
    */

    'brand' => env('FILAMENT_BRAND', 'beltspot'),

    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the general authentication features of
    | Filament.
    |
    */

    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => \Filament\Pages\Auth\Login::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the general layout of the admin panel.
    |
    | You may configure the max content width from `xl` to `7xl`, or `full`
    | for no max width.
    |
    */

    'layout' => [
        'actions' => [
            'modal' => [
                'actions' => [
                    'alignment' => 'left',
                ],
            ],
        ],
        'forms' => [
            'actions' => [
                'alignment' => 'left',
                'vertical_alignment' => 'center',
            ],
        ],
        'footer' => [
            'should_show_logo' => false,
        ],
        'max_content_width' => null,
        'notifications' => [
            'vertical_alignment' => 'top',
            'alignment' => 'right',
        ],
        'sidebar' => [
            'is_collapsible_on_desktop' => true,
            'groups' => [
                'are_collapsible' => true,
            ],
            'width' => null,
            'collapsed_width' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | This is the default theme that will be used by Filament.
    |
    */

    'default_theme' => 'dark',

    /*
    |--------------------------------------------------------------------------
    | Dark mode
    |--------------------------------------------------------------------------
    |
    | By enabling this feature, your users are able to select between a light
    | and dark appearance for the admin panel, or let their system decide.
    |
    */

    'dark_mode' => [
        'enabled' => true,
        'default' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the database settings of Filament.
    |
    */

    'database' => [
        'connection' => env('FILAMENT_DATABASE_CONNECTION'),
        'tables' => [
            'notifications' => 'notifications',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Broadcasting
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the broadcasting settings of Filament.
    |
    */

    'broadcasting' => [
        'echo' => [
            'broadcaster' => env('VITE_ECHO_SERVER', 'pusher'),
            'key' => env('VITE_ECHO_KEY'),
            'cluster' => env('VITE_ECHO_CLUSTER'),
            'force_tls' => env('VITE_ECHO_FORCE_TLS', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the cache settings of Filament.
    |
    */

    'cache' => [
        'should_clear_cache_on_upload' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the localization settings of Filament.
    |
    */

    'localization' => [
        'default_locale' => 'es',
        'fallback_locale' => 'en',
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout Components
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the layout components of Filament.
    |
    */

    'layout_components' => [
        'actions' => [
            'modal' => [
                'actions' => [
                    'alignment' => 'left',
                ],
            ],
            'notifications' => [
                'vertical_alignment' => 'top',
                'alignment' => 'right',
            ],
        ],
        'forms' => [
            'actions' => [
                'alignment' => 'left',
                'vertical_alignment' => 'center',
            ],
            'have_inline_labels' => false,
        ],
        'footer' => [
            'should_show_logo' => false,
        ],
        'max_content_width' => null,
        'sidebar' => [
            'is_collapsible_on_desktop' => true,
            'groups' => [
                'are_collapsible' => true,
            ],
            'width' => null,
            'collapsed_width' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This is the storage disk Filament will use to put generated files. You may
    | use any of the disks defined in the `config/filesystems.php`.
    |
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the assets of Filament.
    |
    */

    'assets' => [
        'theme' => 'resources/css/filament/admin/theme.css',
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | You may customize the middleware used by Filament here.
    |
    */
    'middleware' => [
        'auth',
        'admin.seller.only',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the pages of Filament.
    |
    */

    'pages' => [
        'namespace' => 'App\\Filament\\Pages',
        'path' => app_path('Filament/Pages'),
        'register' => [
            \Filament\Pages\Auth\Login::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the resources of Filament.
    |
    */

    'resources' => [
        'namespace' => 'App\\Filament\\Resources',
        'path' => app_path('Filament/Resources'),
        'register' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the widgets of Filament.
    |
    */

    'widgets' => [
        'namespace' => 'App\\Filament\\Widgets',
        'path' => app_path('Filament/Widgets'),
        'register' => [
            \Filament\Widgets\AccountWidget::class,
            \Filament\Widgets\FilamentInfoWidget::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the Livewire components of Filament.
    |
    */

    'livewire' => [
        'namespace' => 'App\\Filament',
        'path' => app_path('Filament'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Panels
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the panels of Filament.
    |
    */

    'panels' => [
        'default' => \App\Providers\FilamentAdminServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the plugins of Filament.
    |
    */

    'plugins' => [
        'register' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Vite
    |--------------------------------------------------------------------------
    |
    | This is the configuration for the Vite integration of Filament.
    |
    */

    'vite' => [
        'theme' => 'resources/css/filament/admin/theme.css',
    ],
]; 