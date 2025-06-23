<?php
// config/educational.php

return [
    /*
    |--------------------------------------------------------------------------
    | إعدادات النظام التعليمي
    |--------------------------------------------------------------------------
    */

    // إعدادات عامة
    'enabled' => env('EDUCATIONAL_SYSTEM_ENABLED', true),
    'currency' => env('EDUCATIONAL_CURRENCY', 'دينار'),
    'currency_symbol' => env('EDUCATIONAL_CURRENCY_SYMBOL', 'د.أ'),

    // إعدادات البطاقات التعليمية
    'cards' => [
        'default_duration_days' => env('EDUCATIONAL_CARDS_DEFAULT_DURATION', 365),
        'max_duration_days' => env('EDUCATIONAL_CARDS_MAX_DURATION', 1095), // 3 سنوات
        'code_length' => env('EDUCATIONAL_CARDS_CODE_LENGTH', 8),
        'pin_length' => env('EDUCATIONAL_CARDS_PIN_LENGTH', 6),
        'auto_expire' => env('EDUCATIONAL_CARDS_AUTO_EXPIRE', true),
        'extend_before_expiry_days' => env('EDUCATIONAL_CARDS_EXTEND_BEFORE_EXPIRY', 30),
    ],

    // إعدادات المخزون
    'inventory' => [
        'low_stock_threshold' => env('EDUCATIONAL_LOW_STOCK_THRESHOLD', 10),
        'auto_reserve' => env('EDUCATIONAL_AUTO_RESERVE_INVENTORY', true),
        'release_reserved_after_hours' => env('EDUCATIONAL_RELEASE_RESERVED_AFTER', 24),
        'enable_negative_stock' => env('EDUCATIONAL_ENABLE_NEGATIVE_STOCK', false),
    ],

    // إعدادات الشحن
    'shipping' => [
        'default_preparation_days' => env('EDUCATIONAL_SHIPPING_PREPARATION_DAYS', 2),
        'default_delivery_days' => env('EDUCATIONAL_SHIPPING_DELIVERY_DAYS', 3),
        'auto_tracking' => env('EDUCATIONAL_AUTO_TRACKING', false),
        'tracking_provider' => env('EDUCATIONAL_TRACKING_PROVIDER', 'custom'),
        'free_shipping_threshold' => env('EDUCATIONAL_FREE_SHIPPING_THRESHOLD', 0),
    ],

    // إعدادات التسعير
    'pricing' => [
        'min_price' => env('EDUCATIONAL_MIN_PRICE', 0),
        'max_price' => env('EDUCATIONAL_MAX_PRICE', 9999.99),
        'auto_calculate_shipping' => env('EDUCATIONAL_AUTO_CALCULATE_SHIPPING', true),
        'tax_rate' => env('EDUCATIONAL_TAX_RATE', 0), // 0% افتراضي
        'enable_bulk_discounts' => env('EDUCATIONAL_ENABLE_BULK_DISCOUNTS', false),
    ],

    // إعدادات الإشعارات
    'notifications' => [
        'admin_email' => env('EDUCATIONAL_ADMIN_EMAIL', 'admin@example.com'),
        'send_low_stock_alerts' => env('EDUCATIONAL_SEND_LOW_STOCK_ALERTS', true),
        'send_overdue_shipment_alerts' => env('EDUCATIONAL_SEND_OVERDUE_ALERTS', true),
        'send_card_expiry_reminders' => env('EDUCATIONAL_SEND_EXPIRY_REMINDERS', true),
        'card_expiry_reminder_days' => env('EDUCATIONAL_CARD_EXPIRY_REMINDER_DAYS', 7),
    ],

    // إعدادات التكامل الخارجي
    'integrations' => [
        'enable_webhooks' => env('EDUCATIONAL_ENABLE_WEBHOOKS', false),
        'webhook_secret' => env('EDUCATIONAL_WEBHOOK_SECRET'),
        'payment_gateway' => env('EDUCATIONAL_PAYMENT_GATEWAY', 'cash'),
        'shipping_provider_api' => env('EDUCATIONAL_SHIPPING_API'),
    ],

    // إعدادات الصيانة والتنظيف
    'maintenance' => [
        'auto_expire_cards' => env('EDUCATIONAL_AUTO_EXPIRE_CARDS', true),
        'cleanup_old_data' => env('EDUCATIONAL_CLEANUP_OLD_DATA', true),
        'keep_cancelled_cards_days' => env('EDUCATIONAL_KEEP_CANCELLED_CARDS_DAYS', 365),
        'keep_delivered_shipments_days' => env('EDUCATIONAL_KEEP_DELIVERED_SHIPMENTS_DAYS', 730),
        'daily_maintenance_time' => env('EDUCATIONAL_MAINTENANCE_TIME', '02:00'),
    ],

    // إعدادات الأمان
    'security' => [
        'max_cards_per_order' => env('EDUCATIONAL_MAX_CARDS_PER_ORDER', 10),
        'max_booklets_per_order' => env('EDUCATIONAL_MAX_BOOKLETS_PER_ORDER', 5),
        'enable_ip_restrictions' => env('EDUCATIONAL_ENABLE_IP_RESTRICTIONS', false),
        'allowed_ips' => env('EDUCATIONAL_ALLOWED_IPS', ''),
        'rate_limit_per_minute' => env('EDUCATIONAL_RATE_LIMIT_PER_MINUTE', 60),
    ],

    // إعدادات واجهة المستخدم
    'ui' => [
        'items_per_page' => env('EDUCATIONAL_ITEMS_PER_PAGE', 15),
        'enable_search' => env('EDUCATIONAL_ENABLE_SEARCH', true),
        'enable_filters' => env('EDUCATIONAL_ENABLE_FILTERS', true),
        'show_statistics' => env('EDUCATIONAL_SHOW_STATISTICS', true),
        'theme' => env('EDUCATIONAL_THEME', 'default'),
    ],

    // إعدادات التصدير
    'export' => [
        'max_records_per_export' => env('EDUCATIONAL_MAX_EXPORT_RECORDS', 10000),
        'enable_csv_export' => env('EDUCATIONAL_ENABLE_CSV_EXPORT', true),
        'enable_excel_export' => env('EDUCATIONAL_ENABLE_EXCEL_EXPORT', false),
        'enable_pdf_export' => env('EDUCATIONAL_ENABLE_PDF_EXPORT', false),
    ],

    // إعدادات الكاش
    'cache' => [
        'enable_caching' => env('EDUCATIONAL_ENABLE_CACHING', true),
        'cache_duration_minutes' => env('EDUCATIONAL_CACHE_DURATION', 60),
        'cache_prefix' => env('EDUCATIONAL_CACHE_PREFIX', 'edu_'),
    ],

    // إعدادات السجلات
    'logging' => [
        'log_user_actions' => env('EDUCATIONAL_LOG_USER_ACTIONS', true),
        'log_admin_actions' => env('EDUCATIONAL_LOG_ADMIN_ACTIONS', true),
        'log_system_events' => env('EDUCATIONAL_LOG_SYSTEM_EVENTS', true),
        'log_level' => env('EDUCATIONAL_LOG_LEVEL', 'info'),
    ],

    // قوائم القيم الافتراضية
    'defaults' => [
        'product_types' => [
            [
                'name' => 'بطاقات تعليمية',
                'code' => 'cards',
                'is_digital' => true,
                'requires_shipping' => false,
            ],
            [
                'name' => 'دوسيات ورقية',
                'code' => 'booklets',
                'is_digital' => false,
                'requires_shipping' => true,
            ],
        ],

        'shipping_regions' => [
            ['name' => 'عمان', 'shipping_cost' => 2.00],
            ['name' => 'إربد', 'shipping_cost' => 3.00],
            ['name' => 'الزرقاء', 'shipping_cost' => 2.50],
            ['name' => 'المفرق', 'shipping_cost' => 4.00],
            ['name' => 'جرش', 'shipping_cost' => 3.50],
            ['name' => 'عجلون', 'shipping_cost' => 4.00],
            ['name' => 'البلقاء', 'shipping_cost' => 3.00],
            ['name' => 'مادبا', 'shipping_cost' => 3.50],
            ['name' => 'الكرك', 'shipping_cost' => 5.00],
            ['name' => 'الطفيلة', 'shipping_cost' => 6.00],
            ['name' => 'معان', 'shipping_cost' => 7.00],
            ['name' => 'العقبة', 'shipping_cost' => 8.00],
        ],

        'generation_years' => range(2000, date('Y') + 5),

        'subjects' => [
            'الرياضيات', 'الفيزياء', 'الكيمياء', 'الأحياء',
            'اللغة العربية', 'اللغة الإنجليزية', 'التاريخ', 'الجغرافيا',
            'التربية الإسلامية', 'الحاسوب', 'العلوم', 'الدراسات الاجتماعية'
        ],

        'digital_packages' => [
            ['name' => 'باقة شهرية', 'duration_days' => 30, 'lessons_count' => 20],
            ['name' => 'باقة ثلاثة أشهر', 'duration_days' => 90, 'lessons_count' => 60],
            ['name' => 'باقة نصف سنة', 'duration_days' => 180, 'lessons_count' => 120],
            ['name' => 'باقة سنوية كاملة', 'duration_days' => 365, 'lessons_count' => null],
        ],

        'physical_packages' => [
            ['name' => 'دوسية الفصل الأول', 'pages_count' => 100, 'weight_grams' => 200],
            ['name' => 'دوسية الفصل الثاني', 'pages_count' => 120, 'weight_grams' => 240],
            ['name' => 'دوسية السنة الكاملة', 'pages_count' => 250, 'weight_grams' => 500],
            ['name' => 'مراجعة نهائية', 'pages_count' => 80, 'weight_grams' => 160],
        ],
    ],

    // إعدادات API
    'api' => [
        'enable_api' => env('EDUCATIONAL_ENABLE_API', true),
        'api_rate_limit' => env('EDUCATIONAL_API_RATE_LIMIT', 100),
        'api_version' => env('EDUCATIONAL_API_VERSION', 'v1'),
        'require_authentication' => env('EDUCATIONAL_API_REQUIRE_AUTH', false),
    ],
];