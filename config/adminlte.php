<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'AdminLTE 3',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => '<b>MIS</b>',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#71-layout
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#721-authentication-views-classes
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#722-admin-panel-classes
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#73-sidebar
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => true,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#74-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-urls
    |
    */

    'use_route_url' => false,

    'dashboard_url' => 'admin/dashboard',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => 'register',

    'password_reset_url' => 'password/reset',

    'password_email_url' => 'password/email',

    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#92-laravel-mix
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#8-menu-configuration
    |
    */

    'menu' => [
        [
            'text' => 'search',
            'search' => false,
            'topnav' => true,
        ],
        [
            'text' => 'dashboard',
            'url'  => 'admin/dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt'
        ],
		[
            'text'    => 'purchase_management',
            'icon'    => 'fas fa-fw fa-cart-plus',
            'can'  => ['purchase_order_create','purchase_order_read','purchase_order_delete','supplier_create','supplier_read','supplier_delete','batch_create','batch_read','batch_delete'],
            'submenu' => [
                [
                    'text' => 'purchase_orders',
                    'url'  => 'admin/purchase',
                    'icon' => 'fas fa-fw fa-cart-plus',
                    'can'  => ['purchase_order_create','purchase_order_read','purchase_order_delete'],
					'active' => ['admin/purchase','regex:@^admin/purchase/.*$@']
                ],
				[
                    'text' => 'suppliers',
                    'url'  => 'admin/supplier',
                    'icon' => 'fas fa-fw fa-people-arrows',
                    'can'  => ['supplier_create','supplier_read','supplier_delete'],
					'active' => ['admin/supplier','regex:@^admin/supplier/.*$@']
                ],
				[
                    'text' => 'batches',
                    'url'  => 'admin/batch',
                    'icon' => 'fas fa-fw fa-stopwatch',
                    'can'  => ['batch_create','batch_read','batch_delete'],
					'active' => ['admin/batch','regex:@^admin/batch/.*$@']
                ],
				[
                    'text' => 'godowns',
                    'url'  => 'admin/godown',
                    'icon' => 'fas fa-fw fa-warehouse',
                    'can'  => ['godown_create','godown_read','godown_delete'],
					'active' => ['admin/godown','regex:@^admin/godown/.*$@']
                ],
				[
                    'text' => 'transport',
                    'url'  => 'admin/transport',
                    'icon' => 'fas fa-fw fa-truck',
                    'can'  => ['transport_create','transport_read','transport_delete'],
					'active' => ['admin/transport','regex:@^admin/transport/.*$@']
                ],
            ],
        ],
		[
            'text'    => 'stock_management',
            'icon'    => 'fas fa-fw fa-cubes',
            'can'  => ['stock_read'],
            'submenu' => [
                [
                    'text' => 'stock',
                    'url'  => 'admin/stock',
                    'icon' => 'fas fa-fw fa-cubes',
                    'can'  => ['stock_read'],
					'active' => ['admin/stock','regex:@^admin/stock/\d+/.*$@']
                ],
				[
                    'text' => 'opening_stock',
                    'url'  => 'admin/stock/create',
                    'icon' => 'fas fa-fw fa-cube',
                    'can'  => ['stock_create'],
					'active' => ['admin/stock/create']
                ],
				[
                    'text' => 'production',
                    'url'  => 'admin/production',
                    'icon' => 'fas fa-fw fa-cube',
                    'can'  => ['production_create'],
					'active' => ['admin/production']
                ]
            ],
        ],
		[
            'text'    => 'sales_management',
            'icon'    => 'fas fa-fw fa-share-square',
            'can'  => ['sales_read','sales_create','customer_read','customer_create','customer_delete'],
            'submenu' => [
                [
                    'text' => 'sales',
                    'url'  => 'admin/sales',
                    'icon' => 'fas fa-fw fa-share',
                    'can'  => ['sales_read','sales_create'],
					'active' => ['admin/sales','regex:@^admin/sales/.*$@']
                ],
                [
                    'text' => 'customers',
                    'url'  => 'admin/customer',
                    'icon' => 'fas fa-fw fa-male',
                    'can'  => ['customer_read','customer_create','customer_delete'],
					'active' => ['admin/customer','regex:@^admin/customer/.*$@']
                ],
            ],
        ],
        [
            'text'    => 'product_management',
            'icon'    => 'fas fa-fw fa-stream',
            'can'  => ['product_create','product_read','product_delete','product_category_read','product_category_create','product_category_delete'],
            'submenu' => [
                [
                    'text' => 'products',
                    'url'  => 'admin/product',
                    'icon' => 'fas fa-fw fa-list',
                    'can'  => ['product_create','product_read','product_delete'],
					'active' => ['admin/product','regex:@^admin/product/.*$@']
                ],
                [
                    'text' => 'categories',
                    'url'  => 'admin/category',
                    'icon' => 'fas fa-fw fa-th-list',
                    'can'  => ['product_category_read','product_category_create','product_category_delete'],
					'active' => ['admin/category','regex:@^admin/category/.*$@']
                ],
            ],
        ],
		[
            'text'    => 'accounts_management',
            'icon'    => 'fas fa-fw fa-hand-holding-usd',
            'can'  => ['account_head_create','account_head_read','account_head_delete','transaction_create'],
            'submenu' => [
                [
                    'text' => 'account_heads',
                    'url'  => 'admin/accountHead',
                    'icon' => 'fas fa-fw fa-hand-holding-usd',
                    'can'  => ['account_head_create','account_head_read','account_head_delete'],
					'active' => ['admin/accountHead','admin/accountHead/create','regex:@^admin/accountHead/\d+$@','regex:@^admin/accountHead/\d+/edit$@']
                ],
                [
                    'text' => 'bank_accounts',
                    'url'  => 'admin/bankAccount',
                    'icon' => 'fas fa-fw fa-university',
                    'can'  => ['bank_account_create','bank_account_create','bank_account_create'],
					'active' => ['admin/bankAccount','regex:@^admin/bankAccount/.*$@']
                ],
				[
                    'text' => 'payments/receipts',
                    'url'  => 'admin/accountHead/paymentsReceipts',
                    'icon' => 'fas fa-fw fa-file-invoice-dollar',
                    'can'  => ['transaction_create','transaction_read','transaction_delete'],
					'active' => ['admin/accountHead/receipt']
                ],
                [
                    'text' => 'invoice_books',
                    'url'  => 'admin/invoiceBooks',
                    'icon' => 'fas fa-fw fa-file-invoice-dollar',
                    'can'  => ['invoice_books_create','invoice_books_read','invoice_books_delete'],
					'active' => ['admin/invoiceBooks','regex:@^admin/invoiceBooks/.*$@']
                ],
				[
                    'text' => 'cheques',
                    'url'  => 'admin/accountHead/cheques',
                    'icon' => 'fas fa-fw fa-money-check',
                    'can'  => ['transaction_update','transaction_read','transaction_delete'],
					'active' => ['admin/accountHead/cheques']
                ],
				[
                    'text' => 'opening_balances',
                    'url'  => 'admin/accountHead/openingBalance',
                    'icon' => 'fas fa-fw fa-balance-scale',
                    'can'  => ['transaction_create'],
					'active' => ['admin/accountHead/openingBalance']
                ],
            ],
        ],
		[
            'text'    => 'report_management',
            'icon'    => 'fas fa-fw fa-clipboard-list',
            'can'  => ['report_read'],
            'submenu' => [
                [
                    'text' => 'profit_loss',
                    'url'  => 'admin/report/profitLoss',
                    'icon' => 'fas fa-fw fa-chart-line',
                    'can'  => ['report_read'],
					'active' => ['admin/report/profitLoss']
                ],
                [
                    'text' => 'cashInOut',
                    'url'  => 'admin/report/cashInOut',
                    'icon' => 'fas fa-fw fa-money-bill-alt',
                    'can'  => ['report_read'],
					'active' => ['admin/report/cashInOut']
                ],
                [
                    'text' => 'accounts',
                    'url'  => 'admin/report/accounts',
                    'icon' => 'fas fa-fw fa-search-dollar',
                    'can'  => ['report_read'],
					'active' => ['admin/report/accounts']
                ],
				[
                    'text' => 'sales',
                    'url'  => 'admin/report/sales',
                    'icon' => 'fas fa-fw fa-hand-holding-usd',
                    'can'  => ['report_read'],
					'active' => ['admin/report/sales']
                ],
                [
                    'text' => 'day_summary',
                    'url'  => 'admin/report/daySummary',
                    'icon' => 'fas fa-fw fa-hand-holding-usd',
                    'can'  => ['report_read'],
					'active' => ['admin/report/daySummary']
                ],
                [
                    'text' => 'detail_summary',
                    'url'  => 'admin/report/rangeSummary',
                    'icon' => 'fas fa-fw fa-hand-holding-usd',
                    'can'  => ['report_read'],
					'active' => ['admin/report/rangeSummary']
                ],
                [
                    'text' => 'supplier_summary',
                    'url'  => 'admin/report/supplierSummary',
                    'icon' => 'fas fa-fw fa-hand-holding-usd',
                    'can'  => ['report_read'],
					'active' => ['admin/report/supplierSummary']
                ],
				[
                    'text' => 'duplicates',
                    'url'  => 'admin/report/duplicates',
                    'icon' => 'fas fa-fw fa-clone',
                    'can'  => ['report_read'],
					'active' => ['admin/report/duplicates']
                ],
                [
                    'text' => 'missing',
                    'url'  => 'admin/report/missing',
                    'icon' => 'fas fa-fw fa-strikethrough',
                    'can'  => ['report_read'],
					'active' => ['admin/report/missing']
                ],
            ],
        ],
		[
            'text'    => 'staff_management',
            'icon'    => 'fas fa-fw fa-users',
            'can'  => ['staff_create','staff_read','staff_delete'],
            'submenu' => [
                [
                    'text' => 'staff_type',
                    'url'  => 'admin/staffType',
                    'icon' => 'fas fa-fw fa-users-cog',
                    'can'  => ['staff_create','staff_create','staff_create'],
					'active' => ['admin/staffType','regex:@^admin/staffType/.*$@']
                ],
                [
                    'text' => 'staff',
                    'url'  => 'admin/staff',
                    'icon' => 'fas fa-fw fa-users',
                    'can'  => ['staff_create','staff_create','staff_create'],
					'active' => ['admin/staff','regex:@^admin/staff/.*$@']
                ],
                [
                    'text' => 'staff_attendance',
                    'url'  => 'admin/attendance',
                    'icon' => 'fas fa-fw fa-clipboard-check',
                    'can'  => ['attendance_create','attendance_read','attendance_update','attendance_delete'],
					'active' => ['admin/attendance','regex:@^admin/attendance/.*$@']
                ],
            ],
        ],
		[
            'text'    => 'user_management',
            'icon'    => 'fas fa-fw fa-user',
            'can'  => ['user_create','user_read','user_delete','roles_read','roles_create','roles_delete','setting_read','setting_create','setting_update','setting_delete'],
            'submenu' => [
                [
                    'text' => 'users',
                    'url'  => 'admin/user',
                    'icon' => 'fas fa-fw fa-user',
                    'can'  => ['user_create','user_read','user_delete'],
					'active' => ['admin/user','regex:@^admin/user/.*$@']
                ],
                [
                    'text' => 'roles',
                    'url'  => 'admin/role',
                    'icon' => 'fas fa-fw fa-user-tag',
                    'can'  => ['roles_read','roles_create','roles_delete'],
					'active' => ['admin/role','regex:@^admin/role/.*$@']
                ],
				[
                    'text' => 'settings',
                    'url'  => 'admin/setting',
                    'icon' => 'fas fa-fw fa-cogs',
                    'can'  => ['setting_read','setting_create','setting_update','setting_delete'],
					'active' => ['admin/setting']
                ],
            ],
        ],
        // [
        //     'text'        => 'pages',
        //     'url'         => 'admin/pages',
        //     'icon'        => 'far fa-fw fa-file',
        //     'label'       => 4,
        //     'label_color' => 'success',
        // ],
        // ['header' => 'user_management'],
        // [
        //     'text' => 'users',
        //     'url'  => 'admin/user',
        //     'icon' => 'fas fa-fw fa-user',
        //     'can'  => ['user_create','user_read','user_delete']
        // ],
        // [
        //     'text' => 'roles',
        //     'url'  => 'admin/role',
        //     'icon' => 'fas fa-fw fa-user-tag',
        // ],
        // [
        //     'text'    => 'multilevel',
        //     'icon'    => 'fas fa-fw fa-share',
        //     'submenu' => [
        //         [
        //             'text' => 'level_one',
        //             'url'  => '#',
        //         ],
        //         [
        //             'text'    => 'level_one',
        //             'url'     => '#',
        //             'submenu' => [
        //                 [
        //                     'text' => 'level_two',
        //                     'url'  => '#',
        //                 ],
        //                 [
        //                     'text'    => 'level_two',
        //                     'url'     => '#',
        //                     'submenu' => [
        //                         [
        //                             'text' => 'level_three',
        //                             'url'  => '#',
        //                         ],
        //                         [
        //                             'text' => 'level_three',
        //                             'url'  => '#',
        //                         ],
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         [
        //             'text' => 'level_one',
        //             'url'  => '#',
        //         ],
        //     ],
        // ],
        // ['header' => 'labels'],
        // [
        //     'text'       => 'important',
        //     'icon_color' => 'red',
        //     'url'        => '#',
        // ],
        // [
        //     'text'       => 'warning',
        //     'icon_color' => 'yellow',
        //     'url'        => '#',
        // ],
        // [
        //     'text'       => 'information',
        //     'icon_color' => 'cyan',
        //     'url'        => '#',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#83-custom-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#91-plugins
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
				[
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js',
                ],
				[
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js',
                ],
				[
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js',
                ],
				[
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
				[
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css',
                ],
            ],
        ],
        'daterangepicker' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/momentjs/latest/moment.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
		'bootstrapSwitch' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap2/bootstrap-switch.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#93-livewire
    */

    'livewire' => false,
];
