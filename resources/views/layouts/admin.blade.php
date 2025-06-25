<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        :root {
            /* Admin Color Scheme */
            --admin-primary-50: #eff6ff;
            --admin-primary-100: #dbeafe;
            --admin-primary-200: #bfdbfe;
            --admin-primary-300: #93c5fd;
            --admin-primary-400: #60a5fa;
            --admin-primary-500: #3b82f6;
            --admin-primary-600: #2563eb;
            --admin-primary-700: #1d4ed8;
            --admin-primary-800: #1e40af;
            --admin-primary-900: #1e3a8a;
            
            --admin-secondary-50: #f8fafc;
            --admin-secondary-100: #f1f5f9;
            --admin-secondary-200: #e2e8f0;
            --admin-secondary-300: #cbd5e1;
            --admin-secondary-400: #94a3b8;
            --admin-secondary-500: #64748b;
            --admin-secondary-600: #475569;
            --admin-secondary-700: #334155;
            --admin-secondary-800: #1e293b;
            --admin-secondary-900: #0f172a;
            
            /* Status Colors */
            --success-500: #10b981;
            --warning-500: #f59e0b;
            --error-500: #ef4444;
            --info-500: #3b82f6;
            
            /* Typography */
            --font-family-sans: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            
            /* Spacing */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 3rem;
            
            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            
            /* Transitions */
            --transition-fast: 150ms ease;
            --transition-normal: 200ms ease;
            
            /* Z-Index */
            --z-sidebar: 1000;
            --z-header: 1010;
            --z-dropdown: 1020;
            --z-modal: 1050;
            
            /* Admin Layout */
            --sidebar-width: 300px;
            --header-height: 80px;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }
        
        body {
            font-family: var(--font-family-sans);
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            color: var(--admin-secondary-900);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            direction: rtl;
        }
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
            direction: rtl;
        }
        
        /* Sidebar - تحويل لليمين */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--admin-secondary-800), var(--admin-secondary-900));
            color: white;
            position: fixed;
            top: 0;
            right: 0; /* تحويل من left إلى right */
            height: 100vh;
            z-index: var(--z-sidebar);
            overflow-y: auto;
            transition: transform var(--transition-normal);
            box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1); /* تحويل اتجاه الظل */
        }
        
        .sidebar.collapsed {
            transform: translateX(100%); /* تحويل اتجاه الحركة */
        }
        
        .sidebar-header {
            padding: var(--space-xl) var(--space-lg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            background: linear-gradient(135deg, var(--admin-secondary-800), var(--admin-secondary-700));
        }
        
        .sidebar-logo {
            font-size: 1.75rem;
            font-weight: 900;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            width: 100%;
        }
        
        .sidebar-nav {
            padding: var(--space-xl) 0;
        }
        
        .nav-section {
            margin-bottom: var(--space-2xl);
        }
        
        .nav-section-title {
            padding: 0 var(--space-lg);
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--admin-secondary-400);
            margin-bottom: var(--space-lg);
            position: relative;
        }
        
        .nav-section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            right: var(--space-lg);
            width: 30px;
            height: 2px;
            background: linear-gradient(90deg, var(--admin-primary-500), var(--admin-primary-600));
        }
        
        .nav-item {
            margin-bottom: var(--space-sm);
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: var(--space-lg);
            padding: var(--space-lg) var(--space-xl);
            color: var(--admin-secondary-300);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all var(--transition-fast);
            position: relative;
            border-radius: 0 50px 50px 0; /* شكل منحني من اليسار */
            margin-left: var(--space-lg); /* مسافة من اليسار */
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(-8px); /* تحريك لليسار عند الhover */
            padding-right: calc(var(--space-xl) + 8px);
        }
        
        .nav-link.active {
            background: linear-gradient(90deg, var(--admin-primary-600), var(--admin-primary-500));
            color: white;
            box-shadow: var(--shadow-lg);
            transform: translateX(-8px);
            padding-right: calc(var(--space-xl) + 8px);
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: white;
            border-radius: 0 2px 2px 0;
        }
        
        .nav-icon {
            width: 24px;
            height: 24px;
            text-align: center;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .nav-badge {
            background: linear-gradient(135deg, var(--error-500), #dc2626);
            color: white;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 50px;
            margin-right: auto; /* تحويل من margin-left */
            min-width: 20px;
            text-align: center;
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }
        
        /* Main Content - تعديل المسافات */
        .main-content {
            flex: 1;
            margin-right: var(--sidebar-width); /* تحويل من margin-left */
            margin-left: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-right var(--transition-normal); /* تحويل الانتقال */
        }
        
        .main-content.expanded {
            margin-right: 0; /* تحويل من margin-left */
        }
        
        /* Header */
        .admin-header {
            background: white;
            border-bottom: 1px solid var(--admin-secondary-200);
            padding: 0 var(--space-2xl);
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: var(--z-header);
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(10px);
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: var(--space-xl);
            order: 2; /* تحويل ترتيب العناصر */
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: var(--space-xl);
            order: 1; /* تحويل ترتيب العناصر */
        }
        
        .sidebar-toggle {
            display: none;
            background: linear-gradient(135deg, var(--admin-secondary-100), var(--admin-secondary-50));
            border: 2px solid var(--admin-secondary-200);
            font-size: 1.25rem;
            color: var(--admin-secondary-600);
            cursor: pointer;
            padding: var(--space-md);
            border-radius: var(--radius-lg);
            transition: all var(--transition-fast);
        }
        
        .sidebar-toggle:hover {
            background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
            border-color: var(--admin-primary-300);
            color: var(--admin-primary-700);
            transform: scale(1.05);
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--admin-secondary-900), var(--admin-secondary-700));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            color: var(--admin-secondary-500);
            font-size: 0.9rem;
            margin-top: var(--space-xs);
            font-weight: 500;
        }
        
        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }
        
        .breadcrumb-link {
            color: var(--admin-primary-600);
            text-decoration: none;
            transition: all var(--transition-fast);
            padding: var(--space-xs) var(--space-sm);
            border-radius: var(--radius-sm);
        }
        
        .breadcrumb-link:hover {
            color: var(--admin-primary-700);
            background: var(--admin-primary-50);
        }
        
        .breadcrumb-item i {
            transform: scaleX(-1); /* عكس اتجاه الأسهم */
        }
        
        .header-notifications {
            position: relative;
        }
        
        .notification-btn {
            background: linear-gradient(135deg, var(--admin-secondary-100), var(--admin-secondary-50));
            border: 2px solid var(--admin-secondary-200);
            font-size: 1.25rem;
            color: var(--admin-secondary-600);
            cursor: pointer;
            padding: var(--space-md);
            border-radius: var(--radius-lg);
            transition: all var(--transition-fast);
            position: relative;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-btn:hover {
            background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
            border-color: var(--admin-primary-300);
            color: var(--admin-primary-700);
            transform: scale(1.05);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            left: -8px; /* تحويل من right */
            background: linear-gradient(135deg, var(--error-500), #dc2626);
            color: white;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 50px;
            min-width: 20px;
            text-align: center;
            font-weight: 700;
            transform: scale(1);
            animation: pulse 2s infinite;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: var(--space-lg);
            padding: var(--space-md) var(--space-lg);
            border-radius: var(--radius-xl);
            cursor: pointer;
            transition: all var(--transition-fast);
            position: relative;
            border: 2px solid var(--admin-secondary-200);
            background: linear-gradient(135deg, white, var(--admin-secondary-50));
        }
        
        .user-menu:hover {
            background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
            border-color: var(--admin-primary-300);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.1rem;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
            border: 3px solid white;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        
        .user-name {
            font-weight: 700;
            color: var(--admin-secondary-900);
            font-size: 0.95rem;
        }
        
        .user-role {
            font-size: 0.8rem;
            color: var(--admin-secondary-500);
            font-weight: 500;
        }
        
        /* Content Area */
        .content-area {
            flex: 1;
            padding: var(--space-2xl);
            overflow-x: hidden;
        }
        
        /* Utility Classes */
        .card {
            background: white;
            border: 1px solid var(--admin-secondary-200);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all var(--transition-fast);
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }
        
        .card-header {
            padding: var(--space-xl);
            border-bottom: 1px solid var(--admin-secondary-200);
            background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--admin-secondary-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }
        
        .card-body {
            padding: var(--space-xl);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            padding: var(--space-md) var(--space-xl);
            border: 2px solid transparent;
            border-radius: var(--radius-lg);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all var(--transition-fast);
            white-space: nowrap;
            font-family: var(--font-family-sans);
        }
        
        .btn:focus {
            outline: 3px solid rgba(59, 130, 246, 0.3);
            outline-offset: 2px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
            color: white;
            box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--admin-primary-700), var(--admin-primary-800));
            box-shadow: 0 8px 25px 0 rgba(59, 130, 246, 0.6);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: white;
            color: var(--admin-secondary-700);
            border-color: var(--admin-secondary-300);
            box-shadow: var(--shadow-sm);
        }
        
        .btn-secondary:hover {
            background: var(--admin-secondary-50);
            border-color: var(--admin-secondary-400);
            transform: translateY(-1px);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-500), #059669);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning-500), #d97706);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(245, 158, 11, 0.39);
        }
        
        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--error-500), #dc2626);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.39);
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
        }
        
        .btn-sm {
            padding: var(--space-sm) var(--space-lg);
            font-size: 0.8rem;
        }
        
        .btn-lg {
            padding: var(--space-lg) var(--space-2xl);
            font-size: 1.1rem;
        }
        
        .form-group {
            margin-bottom: var(--space-xl);
        }
        
        .form-label {
            display: block;
            margin-bottom: var(--space-md);
            font-weight: 600;
            color: var(--admin-secondary-700);
            font-size: 0.95rem;
        }
        
        .form-input {
            width: 100%;
            padding: var(--space-md) var(--space-lg);
            border: 2px solid var(--admin-secondary-300);
            border-radius: var(--radius-lg);
            background: white;
            color: var(--admin-secondary-900);
            font-size: 0.9rem;
            transition: all var(--transition-fast);
            font-family: var(--font-family-sans);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--admin-primary-500);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }
        
        .alert {
            padding: var(--space-lg);
            border-radius: var(--radius-lg);
            border: 2px solid;
            margin-bottom: var(--space-xl);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            font-weight: 500;
            box-shadow: var(--shadow-md);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border-color: #22c55e;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #fef2f2, #fecaca);
            color: #991b1b;
            border-color: #ef4444;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fffbeb, #fde68a);
            color: #92400e;
            border-color: #f59e0b;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1e40af;
            border-color: #3b82f6;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        
        .table th,
        .table td {
            padding: var(--space-lg);
            text-align: right; /* تحويل محاذاة النص */
            border-bottom: 1px solid var(--admin-secondary-200);
        }
        
        .table th {
            background: linear-gradient(135deg, var(--admin-secondary-50), var(--admin-secondary-100));
            font-weight: 700;
            color: var(--admin-secondary-700);
        }
        
        .table tbody tr:hover {
            background: var(--admin-secondary-50);
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-md);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, #fffbeb, #fde68a);
            color: #92400e;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, #fef2f2, #fecaca);
            color: #991b1b;
        }
        
        .badge-info {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1e40af;
        }
        
        .badge-secondary {
            background: var(--admin-secondary-100);
            color: var(--admin-secondary-700);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%); /* تحويل الاتجاه */
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-right: 0; /* تحويل من margin-left */
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .content-area {
                padding: var(--space-lg);
            }
            
            .user-info {
                display: none;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .admin-header {
                padding: 0 var(--space-lg);
            }
        }
        
        /* Animation for fade-in elements */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Dropdown */
        .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0; /* تحويل من right */
            background: white;
            border: 2px solid var(--admin-secondary-200);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            min-width: 220px;
            z-index: var(--z-dropdown);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all var(--transition-normal);
            backdrop-filter: blur(10px);
        }
        
        .dropdown.open .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            padding: var(--space-lg);
            color: var(--admin-secondary-700);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all var(--transition-fast);
            border: none;
            background: none;
            width: 100%;
            text-align: right;
            cursor: pointer;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, var(--admin-primary-50), var(--admin-primary-100));
            color: var(--admin-primary-700);
            transform: translateX(-4px);
            padding-right: calc(var(--space-lg) + 4px);
        }
        
        .dropdown-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--admin-secondary-200), transparent);
            margin: var(--space-sm) var(--space-md);
        }
        
        /* Icons RTL adjustments */
        .fas {
            margin-left: var(--space-sm);
            margin-right: 0;
        }
        
        .nav-link .fas {
            margin-left: 0;
            margin-right: var(--space-lg);
        }
        
        .card-title .fas {
            margin-left: 0;
            margin-right: var(--space-md);
        }
        
        .btn .fas {
            margin-left: 0;
            margin-right: var(--space-sm);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--admin-secondary-100);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--admin-primary-600), var(--admin-primary-700));
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">{{ config('app.name', 'لوحة التحكم') }}</div>
            </div>
            
            <div class="sidebar-nav">
                <!-- Dashboard -->
                <div class="nav-section">
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            لوحة التحكم
                        </a>
                    </div>
                </div>
                
                <!-- Catalog Management -->
                <div class="nav-section">
                    <div class="nav-section-title">إدارة المنتجات</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            التصنيفات
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            المنتجات
                        </a>
                    </div>
                </div>
                
                <!-- Order Management -->
                <div class="nav-section">
                    <div class="nav-section-title">إدارة الطلبات</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            الطلبات
                        </a>
                    </div>
                </div>
                <!-- Educational System Management -->
<div class="nav-section">
    <div class="nav-section-title">النظام التعليمي</div>
    
    <div class="nav-item">
        <a href="{{ route('admin.educational.generations.index') }}" class="nav-link {{ request()->routeIs('admin.educational.generations.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-graduation-cap"></i>
            الأجيال الدراسية
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.educational.subjects.index') }}" class="nav-link {{ request()->routeIs('admin.educational.subjects.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            المواد الدراسية
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.educational.teachers.index') }}" class="nav-link {{ request()->routeIs('admin.educational.teachers.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chalkboard-teacher"></i>
            المعلمين
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.educational.platforms.index') }}" class="nav-link {{ request()->routeIs('admin.educational.platforms.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-desktop"></i>
            المنصات التعليمية
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.educational.packages.index') }}" class="nav-link {{ request()->routeIs('admin.educational.packages.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-box-open"></i>
            الباقات التعليمية
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.educational.pricing.index') }}" class="nav-link {{ request()->routeIs('admin.educational.pricing.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-dollar-sign"></i>
            التسعير
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.educational.inventory.index') }}" class="nav-link {{ request()->routeIs('admin.educational.inventory.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-boxes"></i>
            المخزون
            @if(isset($lowStockCount) && $lowStockCount > 0)
                <span class="nav-badge">{{ $lowStockCount }}</span>
            @endif
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.educational.regions.index') }}" class="nav-link {{ request()->routeIs('admin.educational.regions.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-map-marker-alt"></i>
            مناطق الشحن
        </a>
    </div>
</div>
                <!-- Customer Management -->
                <div class="nav-section">
                    <div class="nav-section-title">إدارة العملاء</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            المستخدمين
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.conversations.index') }}" class="nav-link {{ request()->routeIs('admin.conversations.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comments"></i>
                            المحادثات
                            @if(isset($unreadConversationsCount) && $unreadConversationsCount > 0)
                                <span class="nav-badge">{{ $unreadConversationsCount }}</span>
                            @endif
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-star"></i>
                            الشهادات
                            @if(isset($pendingTestimonialsCount) && $pendingTestimonialsCount > 0)
                                <span class="nav-badge">{{ $pendingTestimonialsCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                
                <!-- Marketing -->
                <div class="nav-section">
                    <div class="nav-section-title">التسويق</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            كوبونات الخصم
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-right">
                    <!-- Notifications -->
                    <div class="header-notifications">
                        <button class="notification-btn">
                            <i class="fas fa-bell"></i>
                            @if((isset($unreadConversationsCount) && $unreadConversationsCount > 0) || (isset($pendingTestimonialsCount) && $pendingTestimonialsCount > 0))
                                <span class="notification-badge">
                                    {{ ($unreadConversationsCount ?? 0) + ($pendingTestimonialsCount ?? 0) }}
                                </span>
                            @endif
                        </button>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="dropdown">
                        <div class="user-menu" onclick="toggleDropdown(this)">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ Auth::user()->name }}</div>
                                <div class="user-role">مدير النظام</div>
                            </div>
                            <i class="fas fa-chevron-down" style="margin-right: var(--space-sm); color: var(--admin-secondary-500);"></i>
                        </div>
                        
                        <div class="dropdown-menu">
                            <a href="{{ route('admin.profile.show') }}" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                الملف الشخصي
                            </a>
                            <a href="{{ route('admin.profile.change-password') }}" class="dropdown-item">
                                <i class="fas fa-key"></i>
                                تغيير كلمة المرور
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('home') }}" class="dropdown-item">
                                <i class="fas fa-globe"></i>
                                عرض الموقع
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div>
                        <h1 class="page-title">@yield('page-title', 'لوحة التحكم')</h1>
                        @hasSection('breadcrumb')
                            <nav class="breadcrumb">
                                @yield('breadcrumb')
                            </nav>
                        @endif
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="content-area" style="padding-bottom: 0;">
                    <div class="alert alert-success fade-in">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="content-area" style="padding-bottom: 0;">
                    <div class="alert alert-error fade-in">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="content-area" style="padding-bottom: 0;">
                    <div class="alert alert-error fade-in">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul style="margin: 0; padding-right: var(--space-lg); margin-right: var(--space-lg);">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            <!-- Content -->
            <main class="content-area">
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')
    
    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('open');
            mainContent.classList.toggle('expanded');
        });
        
        // Dropdown functionality
        function toggleDropdown(element) {
            const dropdown = element.closest('.dropdown');
            const isOpen = dropdown.classList.contains('open');
            
            // Close all dropdowns
            document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
            
            // Toggle current dropdown
            if (!isOpen) {
                dropdown.classList.add('open');
            }
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
            }
        });
        
        // Initialize fade-in animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { 
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-30px)';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 300);
            });
        }, 5000);
        
        // Add enhanced hover effects
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.style.background = 'rgba(255, 255, 255, 0.1)';
                    this.style.transform = 'translateX(-8px)';
                    this.style.paddingRight = 'calc(var(--space-xl) + 8px)';
                }
            });
            
            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.style.background = '';
                    this.style.transform = '';
                    this.style.paddingRight = '';
                }
            });
        });
        
        // Enhanced button interactions
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mousedown', function() {
                this.style.transform = 'translateY(0px) scale(0.98)';
            });
            
            btn.addEventListener('mouseup', function() {
                this.style.transform = '';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
        
        // Navigation active state management
        function setActiveNavigation() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                const linkPath = link.getAttribute('href');
                if (currentPath.includes(linkPath) && linkPath !== '/admin') {
                    link.classList.add('active');
                } else if (linkPath === '/admin/dashboard' && currentPath === '/admin') {
                    link.classList.add('active');
                }
            });
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            setActiveNavigation();
            
            // Add smooth scrolling to sidebar
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.style.scrollBehavior = 'smooth';
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + M to toggle sidebar
            if (e.altKey && e.key === 'm') {
                e.preventDefault();
                document.getElementById('sidebarToggle').click();
            }
            
            // Esc to close dropdowns
            if (e.key === 'Escape') {
                document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open'));
            }
        });
    </script>
</body>
</html>