<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('app.direction', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', __('Admin Dashboard')) - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
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
            --font-family-sans: 'Inter', 'Cairo', system-ui, -apple-system, sans-serif;
            
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
            --sidebar-width: 280px;
            --header-height: 70px;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: var(--font-family-sans);
            background-color: var(--admin-secondary-50);
            color: var(--admin-secondary-900);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--admin-secondary-800), var(--admin-secondary-900));
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: var(--z-sidebar);
            overflow-y: auto;
            transition: transform var(--transition-normal);
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .sidebar-header {
            padding: var(--space-lg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }
        
        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .sidebar-nav {
            padding: var(--space-lg) 0;
        }
        
        .nav-section {
            margin-bottom: var(--space-xl);
        }
        
        .nav-section-title {
            padding: 0 var(--space-lg);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--admin-secondary-400);
            margin-bottom: var(--space-sm);
        }
        
        .nav-item {
            margin-bottom: var(--space-xs);
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            padding: var(--space-md) var(--space-lg);
            color: var(--admin-secondary-300);
            text-decoration: none;
            font-weight: 500;
            transition: all var(--transition-fast);
            position: relative;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }
        
        .nav-link.active {
            background: linear-gradient(90deg, var(--admin-primary-600), var(--admin-primary-500));
            color: white;
            box-shadow: var(--shadow-md);
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: white;
        }
        
        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        .nav-badge {
            background: var(--error-500);
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
            min-width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left var(--transition-normal);
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        /* Header */
        .admin-header {
            background: white;
            border-bottom: 1px solid var(--admin-secondary-200);
            padding: 0 var(--space-xl);
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: var(--z-header);
            box-shadow: var(--shadow-sm);
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: var(--space-lg);
        }
        
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--admin-secondary-600);
            cursor: pointer;
            padding: var(--space-sm);
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
        }
        
        .sidebar-toggle:hover {
            background: var(--admin-secondary-100);
            color: var(--admin-secondary-800);
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--admin-secondary-900);
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            color: var(--admin-secondary-500);
            font-size: 0.875rem;
        }
        
        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }
        
        .breadcrumb-link {
            color: var(--admin-primary-600);
            text-decoration: none;
            transition: color var(--transition-fast);
        }
        
        .breadcrumb-link:hover {
            color: var(--admin-primary-700);
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: var(--space-lg);
        }
        
        .header-notifications {
            position: relative;
        }
        
        .notification-btn {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--admin-secondary-600);
            cursor: pointer;
            padding: var(--space-sm);
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            position: relative;
        }
        
        .notification-btn:hover {
            background: var(--admin-secondary-100);
            color: var(--admin-secondary-800);
        }
        
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--error-500);
            color: white;
            font-size: 0.625rem;
            padding: 2px 4px;
            border-radius: 8px;
            min-width: 16px;
            text-align: center;
            transform: translate(25%, -25%);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all var(--transition-fast);
            position: relative;
        }
        
        .user-menu:hover {
            background: var(--admin-secondary-100);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--admin-primary-500), var(--admin-primary-600));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--admin-secondary-900);
            font-size: 0.875rem;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: var(--admin-secondary-500);
        }
        
        /* Content Area */
        .content-area {
            flex: 1;
            padding: var(--space-xl);
            overflow-x: hidden;
        }
        
        /* Utility Classes */
        .card {
            background: white;
            border: 1px solid var(--admin-secondary-200);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        
        .card-header {
            padding: var(--space-lg);
            border-bottom: 1px solid var(--admin-secondary-200);
            background: var(--admin-secondary-50);
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--admin-secondary-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }
        
        .card-body {
            padding: var(--space-lg);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            padding: var(--space-sm) var(--space-lg);
            border: 1px solid transparent;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all var(--transition-fast);
            white-space: nowrap;
        }
        
        .btn:focus {
            outline: 2px solid var(--admin-primary-500);
            outline-offset: 2px;
        }
        
        .btn-primary {
            background: var(--admin-primary-600);
            color: white;
            box-shadow: var(--shadow-sm);
        }
        
        .btn-primary:hover {
            background: var(--admin-primary-700);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: white;
            color: var(--admin-secondary-700);
            border-color: var(--admin-secondary-300);
        }
        
        .btn-secondary:hover {
            background: var(--admin-secondary-50);
            border-color: var(--admin-secondary-400);
        }
        
        .btn-success {
            background: var(--success-500);
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        
        .btn-warning {
            background: var(--warning-500);
            color: white;
        }
        
        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background: var(--error-500);
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
        
        .btn-sm {
            padding: var(--space-xs) var(--space-md);
            font-size: 0.75rem;
        }
        
        .btn-lg {
            padding: var(--space-md) var(--space-xl);
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: var(--space-lg);
        }
        
        .form-label {
            display: block;
            margin-bottom: var(--space-sm);
            font-weight: 500;
            color: var(--admin-secondary-700);
            font-size: 0.875rem;
        }
        
        .form-input {
            width: 100%;
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--admin-secondary-300);
            border-radius: var(--radius-md);
            background: white;
            color: var(--admin-secondary-900);
            font-size: 0.875rem;
            transition: all var(--transition-fast);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--admin-primary-500);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .alert {
            padding: var(--space-md);
            border-radius: var(--radius-md);
            border: 1px solid;
            margin-bottom: var(--space-lg);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-color: #bbf7d0;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border-color: #fecaca;
        }
        
        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border-color: #fde68a;
        }
        
        .alert-info {
            background: #eff6ff;
            color: #1e40af;
            border-color: #bfdbfe;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }
        
        .table th,
        .table td {
            padding: var(--space-md);
            text-align: left;
            border-bottom: 1px solid var(--admin-secondary-200);
        }
        
        .table th {
            background: var(--admin-secondary-50);
            font-weight: 600;
            color: var(--admin-secondary-700);
        }
        
        .table tbody tr:hover {
            background: var(--admin-secondary-50);
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: var(--space-xs) var(--space-sm);
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-warning {
            background: #fffbeb;
            color: #92400e;
        }
        
        .badge-danger {
            background: #fef2f2;
            color: #991b1b;
        }
        
        .badge-info {
            background: #eff6ff;
            color: #1e40af;
        }
        
        .badge-secondary {
            background: var(--admin-secondary-100);
            color: var(--admin-secondary-700);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .content-area {
                padding: var(--space-md);
            }
            
            .user-info {
                display: none;
            }
            
            .page-title {
                font-size: 1.25rem;
            }
        }
        
        /* Animation for fade-in elements */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
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
            right: 0;
            background: white;
            border: 1px solid var(--admin-secondary-200);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            z-index: var(--z-dropdown);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all var(--transition-fast);
        }
        
        .dropdown.open .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            padding: var(--space-md);
            color: var(--admin-secondary-700);
            text-decoration: none;
            font-size: 0.875rem;
            transition: background-color var(--transition-fast);
        }
        
        .dropdown-item:hover {
            background: var(--admin-secondary-50);
        }
        
        .dropdown-divider {
            height: 1px;
            background: var(--admin-secondary-200);
            margin: var(--space-sm) 0;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">{{ config('app.name') }}</div>
            </div>
            
            <div class="sidebar-nav">
                <!-- Dashboard -->
                <div class="nav-section">
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            {{ __('Dashboard') }}
                        </a>
                    </div>
                </div>
                
                <!-- Catalog Management -->
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Catalog') }}</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            {{ __('Categories') }}
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            {{ __('Products') }}
                        </a>
                    </div>
                </div>
                
                <!-- Order Management -->
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Orders') }}</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            {{ __('Orders') }}
                        </a>
                    </div>
                </div>
                
                <!-- Customer Management -->
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Customers') }}</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            {{ __('Users') }}
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.conversations.index') }}" class="nav-link {{ request()->routeIs('admin.conversations.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comments"></i>
                            {{ __('Conversations') }}
                            @if(isset($unreadConversationsCount) && $unreadConversationsCount > 0)
                                <span class="nav-badge">{{ $unreadConversationsCount }}</span>
                            @endif
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-star"></i>
                            {{ __('Testimonials') }}
                            @if(isset($pendingTestimonialsCount) && $pendingTestimonialsCount > 0)
                                <span class="nav-badge">{{ $pendingTestimonialsCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                
                <!-- Marketing -->
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Marketing') }}</div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ticket-alt"></i>
                            {{ __('Coupons') }}
                        </a>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div>
                        <h1 class="page-title">@yield('page-title', __('Dashboard'))</h1>
                        @hasSection('breadcrumb')
                            <nav class="breadcrumb">
                                @yield('breadcrumb')
                            </nav>
                        @endif
                    </div>
                </div>
                
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
                                <div class="user-role">{{ __('Administrator') }}</div>
                            </div>
                            <i class="fas fa-chevron-down" style="margin-left: var(--space-sm); color: var(--admin-secondary-500);"></i>
                        </div>
                        
                        <div class="dropdown-menu">
                            <a href="{{ route('admin.profile.show') }}" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                {{ __('Profile') }}
                            </a>
                            <a href="{{ route('admin.profile.change-password') }}" class="dropdown-item">
                                <i class="fas fa-key"></i>
                                {{ __('Change Password') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('home') }}" class="dropdown-item">
                                <i class="fas fa-globe"></i>
                                {{ __('View Site') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
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
                        <ul style="margin: 0; padding-left: var(--space-lg);">
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
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>