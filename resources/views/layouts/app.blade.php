<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('app.direction', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Translate Scripts -->
    <script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,ar',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    
    <style>
        :root {
            /* === PRIMARY COLORS === */
            --primary-50: #f0f9ff;
            --primary-100: #e0f2fe;
            --primary-200: #bae6fd;
            --primary-300: #7dd3fc;
            --primary-400: #38bdf8;
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --primary-700: #0369a1;
            --primary-800: #075985;
            --primary-900: #0c4a6e;
            --primary-950: #082f49;
            
            /* === SECONDARY COLORS === */
            --secondary-50: #fdf4ff;
            --secondary-100: #fae8ff;
            --secondary-200: #f5d0fe;
            --secondary-300: #f0abfc;
            --secondary-400: #e879f9;
            --secondary-500: #d946ef;
            --secondary-600: #c026d3;
            --secondary-700: #a21caf;
            --secondary-800: #86198f;
            --secondary-900: #701a75;
            --secondary-950: #4a044e;
            
            /* === ACCENT COLORS === */
            --accent-50: #fff7ed;
            --accent-100: #ffedd5;
            --accent-200: #fed7aa;
            --accent-300: #fdba74;
            --accent-400: #fb923c;
            --accent-500: #f97316;
            --accent-600: #ea580c;
            --accent-700: #c2410c;
            --accent-800: #9a3412;
            --accent-900: #7c2d12;
            --accent-950: #431407;
            
            /* === NEUTRAL COLORS === */
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --gray-950: #020617;
            
            /* === STATUS COLORS === */
            --success-50: #f0fdf4;
            --success-100: #dcfce7;
            --success-200: #bbf7d0;
            --success-300: #86efac;
            --success-400: #4ade80;
            --success-500: #22c55e;
            --success-600: #16a34a;
            --success-700: #15803d;
            --success-800: #166534;
            --success-900: #14532d;
            --success-950: #052e16;
            
            --warning-50: #fffbeb;
            --warning-100: #fef3c7;
            --warning-200: #fde68a;
            --warning-300: #fcd34d;
            --warning-400: #fbbf24;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            --warning-700: #b45309;
            --warning-800: #92400e;
            --warning-900: #78350f;
            --warning-950: #451a03;
            
            --error-50: #fef2f2;
            --error-100: #fee2e2;
            --error-200: #fecaca;
            --error-300: #fca5a5;
            --error-400: #f87171;
            --error-500: #ef4444;
            --error-600: #dc2626;
            --error-700: #b91c1c;
            --error-800: #991b1b;
            --error-900: #7f1d1d;
            --error-950: #450a0a;
            
            --info-50: #eff6ff;
            --info-100: #dbeafe;
            --info-200: #bfdbfe;
            --info-300: #93c5fd;
            --info-400: #60a5fa;
            --info-500: #3b82f6;
            --info-600: #2563eb;
            --info-700: #1d4ed8;
            --info-800: #1e40af;
            --info-900: #1e3a8a;
            --info-950: #172554;
            
            /* === SEMANTIC COLORS === */
            --background: var(--gray-50);
            --surface: #ffffff;
            --surface-variant: var(--gray-100);
            --on-background: var(--gray-900);
            --on-surface: var(--gray-800);
            --on-surface-variant: var(--gray-600);
            
            --border-color: var(--gray-200);
            --border-hover: var(--gray-300);
            --focus-ring: var(--primary-500);
            
            /* === TYPOGRAPHY === */
            --font-family-sans: 'Inter', 'Cairo', system-ui, -apple-system, sans-serif;
            --font-family-mono: 'Fira Code', 'Monaco', 'Cascadia Code', monospace;
            
            /* === SPACING === */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 3rem;
            --space-3xl: 4rem;
            
            /* === BORDER RADIUS === */
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            
            /* === SHADOWS === */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            
            /* === TRANSITIONS === */
            --transition-fast: 150ms ease;
            --transition-normal: 200ms ease;
            --transition-slow: 300ms ease;
            
            /* === Z-INDEX === */
            --z-dropdown: 1000;
            --z-sticky: 1020;
            --z-fixed: 1030;
            --z-modal-backdrop: 1040;
            --z-modal: 1050;
            --z-popover: 1060;
            --z-tooltip: 1070;
            --z-toast: 1080;
        }
        
        /* Google Translate Styling */
        #google_translate_element {
            display: inline-block !important;
        }
        
        .goog-te-gadget {
            font-size: 0 !important;
            color: transparent !important;
        }

        .goog-te-gadget .goog-te-combo {
            padding: 8px 12px !important;
            border: 1px solid var(--border-color) !important;
            border-radius: var(--radius-md) !important;
            background: var(--surface) !important;
            color: var(--gray-800) !important;
            font-size: 14px !important;
            font-family: inherit !important;
            min-width: 120px !important;
            cursor: pointer !important;
        }

        .goog-te-gadget .goog-te-combo:hover {
            border-color: var(--primary-500) !important;
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.1) !important;
        }

        .goog-te-banner-frame {
            display: none !important;
        }

        .goog-logo-link {
            display: none !important;
        }

        body {
            top: 0px !important;
        }

        .skiptranslate {
            display: none !important;
        }
        
        /* Real-time Messaging Styles */
        .new-message-indicator {
            position: fixed;
            bottom: 100px;
            right: 20px;
            background: var(--primary-500);
            color: white;
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            z-index: var(--z-toast);
            transform: translateY(100px);
            transition: transform 0.3s ease;
            display: none;
        }

        .new-message-indicator.show {
            display: block;
            transform: translateY(0);
        }

        .connection-status {
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: var(--space-xs);
        }

        .connection-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .connection-dot.connected {
            background: var(--success-500);
        }

        .connection-dot.disconnected {
            background: var(--error-500);
        }

        .unread-count {
            background: var(--error-500);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        /* Error notification styles */
        .error-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: var(--z-toast);
            background: var(--error-500);
            color: white;
            padding: var(--space-md) var(--space-lg);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-xl);
            max-width: 350px;
            word-wrap: break-word;
            animation: slideInRight 0.3s ease-out;
        }

        .success-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: var(--z-toast);
            background: var(--success-500);
            color: white;
            padding: var(--space-md) var(--space-lg);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-xl);
            max-width: 350px;
            word-wrap: break-word;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        /* Message animations */
        .message-item {
            transition: all 0.3s ease;
        }

        .message-item:hover {
            transform: translateY(-1px);
        }

        @keyframes messageHighlight {
            0% { background-color: rgba(59, 130, 246, 0.1); }
            100% { background-color: transparent; }
        }

        @keyframes connectionPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .connection-dot {
            animation: connectionPulse 2s infinite;
        }
        
        /* Dark mode variables */
        @media (prefers-color-scheme: dark) {
            :root {
                --background: var(--gray-900);
                --surface: var(--gray-800);
                --surface-variant: var(--gray-700);
                --on-background: var(--gray-100);
                --on-surface: var(--gray-200);
                --on-surface-variant: var(--gray-400);
                --border-color: var(--gray-700);
                --border-hover: var(--gray-600);
            }
        }
        
        /* RTL specific variables */
        [dir="rtl"] {
            --font-family-sans: 'Cairo', 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: var(--font-family-sans);
            background-color: var(--background);
            color: var(--on-background);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Utility classes */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--space-md);
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
            outline: 2px solid var(--focus-ring);
            outline-offset: 2px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            color: white;
            box-shadow: var(--shadow-sm);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: var(--surface);
            color: var(--on-surface);
            border-color: var(--border-color);
            box-shadow: var(--shadow-sm);
        }
        
        .btn-secondary:hover {
            background: var(--surface-variant);
            border-color: var(--border-hover);
            box-shadow: var(--shadow-md);
        }
        
        .btn-accent {
            background: linear-gradient(135deg, var(--accent-500), var(--accent-600));
            color: white;
            box-shadow: var(--shadow-sm);
        }
        
        .btn-accent:hover {
            background: linear-gradient(135deg, var(--accent-600), var(--accent-700));
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        
        .btn-lg {
            padding: var(--space-md) var(--space-xl);
            font-size: 1rem;
            border-radius: var(--radius-lg);
        }
        
        .btn-sm {
            padding: var(--space-xs) var(--space-md);
            font-size: 0.75rem;
            border-radius: var(--radius-sm);
        }
        
        .card {
            background: var(--surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: all var(--transition-normal);
        }
        
        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }
        
        .card-header {
            padding: var(--space-lg);
            border-bottom: 1px solid var(--border-color);
            background: var(--surface-variant);
        }
        
        .card-body {
            padding: var(--space-lg);
        }
        
        .form-group {
            margin-bottom: var(--space-lg);
        }
        
        .form-label {
            display: block;
            margin-bottom: var(--space-sm);
            font-weight: 500;
            color: var(--on-surface);
        }
        
        .form-input {
            width: 100%;
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background: var(--surface);
            color: var(--on-surface);
            font-size: 0.875rem;
            transition: all var(--transition-fast);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--focus-ring);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        
        .alert {
            padding: var(--space-md);
            border-radius: var(--radius-md);
            border: 1px solid;
            margin-bottom: var(--space-lg);
        }
        
        .alert-success {
            background: var(--success-50);
            color: var(--success-800);
            border-color: var(--success-200);
        }
        
        .alert-error {
            background: var(--error-50);
            color: var(--error-800);
            border-color: var(--error-200);
        }
        
        .alert-warning {
            background: var(--warning-50);
            color: var(--warning-800);
            border-color: var(--warning-200);
        }
        
        .alert-info {
            background: var(--info-50);
            color: var(--info-800);
            border-color: var(--info-200);
        }
        
        /* Responsive grid */
        .grid {
            display: grid;
            gap: var(--space-lg);
        }
        
        .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
        
        @media (max-width: 768px) {
            .grid-cols-2,
            .grid-cols-3,
            .grid-cols-4 {
                grid-template-columns: 1fr;
            }
        }
        
        @media (min-width: 768px) and (max-width: 1024px) {
            .grid-cols-3,
            .grid-cols-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Header styles */
        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: var(--z-sticky);
        }
        
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--space-md) 0;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: var(--space-lg);
            list-style: none;
        }
        
        .nav-link {
            color: var(--on-surface);
            text-decoration: none;
            font-weight: 500;
            transition: color var(--transition-fast);
            position: relative;
            display: flex;
            align-items: center;
            gap: var(--space-xs);
        }
        
        .nav-link:hover {
            color: var(--primary-600);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-500);
            border-radius: 1px;
        }
        
        /* Notification badge */
        .nav-badge {
            background: var(--error-500);
            color: white;
            font-size: 0.625rem;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: var(--space-xs);
            min-width: 18px;
            text-align: center;
            font-weight: 700;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Footer styles */
        .footer {
            background: var(--gray-900);
            color: var(--gray-100);
            padding: var(--space-2xl) 0 var(--space-lg);
            margin-top: var(--space-3xl);
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 0 var(--space-sm);
            }
            
            .navbar {
                flex-direction: column;
                gap: var(--space-md);
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: var(--space-md);
            }
            
            #google_translate_element {
                margin-top: var(--space-sm);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="logo">
                    {{ config('app.name') }}
                </a>
                
                <!-- Navigation Links -->
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a></li>
                        <li class="dropdown" style="position: relative;">
        <a href="{{ route('educational-cards.index') }}" class="nav-link {{ request()->routeIs('educational-cards.*') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i>
            Education
        </a>        
                    @auth
                        <!-- Conversations -->
                        <li>
                            <a href="{{ route('user.conversations.index') }}" class="nav-link {{ request()->routeIs('user.conversations.*') ? 'active' : '' }}">
                                <i class="fas fa-comments"></i>
                                Support
                                @php
                                    $unreadConversationsCount = \App\Models\Conversation::where('user_id', Auth::id())->where('is_read_by_user', false)->count();
                                @endphp
                                @if($unreadConversationsCount > 0)
                                    <span class="nav-badge" id="conversationsBadge">{{ $unreadConversationsCount }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <!-- User Menu -->
                        <li class="dropdown">
                            <a href="{{ route('user.profile.show') }}" class="nav-link">
                                <i class="fas fa-user"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>
                        
                        <!-- Cart Icon -->
                        <li>
                            <a href="{{ route('cart.index') }}" class="nav-link">
                                <i class="fas fa-shopping-cart"></i>
                                Cart
                            </a>
                        </li>
                        
                        <!-- Wishlist Icon -->
                        <li>
                            <a href="{{ route('wishlist.index') }}" class="nav-link">
                                <i class="fas fa-heart"></i>
                                Wishlist
                            </a>
                        </li>
                        
                        <!-- Logout -->
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Login</a></li>
                        <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a></li>
                    @endauth
                    
                    <!-- Google Translate Widget -->
                    <li style="margin-left: 15px; padding: 5px;">
                        <div id="google_translate_element"></div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container" style="margin-top: var(--space-lg);">
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container" style="margin-top: var(--space-lg);">
            <div class="alert alert-error fade-in">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container" style="margin-top: var(--space-lg);">
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

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="grid grid-cols-4">
                <div>
                    <h3 style="margin-bottom: var(--space-md); color: white;">{{ config('app.name') }}</h3>
                    <p style="color: var(--gray-400);">Your trusted partner for educational products and services.</p>
                </div>
                <div>
                    <h4 style="margin-bottom: var(--space-md); color: white;">Quick Links</h4>
                    <ul style="list-style: none; color: var(--gray-400);">
                        <li style="margin-bottom: var(--space-sm);"><a href="{{ route('home') }}" style="color: inherit; text-decoration: none;">Home</a></li>
                        <li style="margin-bottom: var(--space-sm);"><a href="{{ route('products.index') }}" style="color: inherit; text-decoration: none;">Products</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="margin-bottom: var(--space-md); color: white;">Account</h4>
                    <ul style="list-style: none; color: var(--gray-400);">
                        @auth
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('user.profile.show') }}" style="color: inherit; text-decoration: none;">My Profile</a></li>
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('orders.index') }}" style="color: inherit; text-decoration: none;">My Orders</a></li>
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('user.conversations.index') }}" style="color: inherit; text-decoration: none;">My Conversations</a></li>
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('wishlist.index') }}" style="color: inherit; text-decoration: none;">Wishlist</a></li>
                        @else
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('login') }}" style="color: inherit; text-decoration: none;">Login</a></li>
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('register') }}" style="color: inherit; text-decoration: none;">Register</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 style="margin-bottom: var(--space-md); color: white;">Contact & Support</h4>
                    <p style="color: var(--gray-400); margin-bottom: var(--space-sm);">
                        <i class="fas fa-envelope"></i>
                        info@example.com
                    </p>
                    <p style="color: var(--gray-400); margin-bottom: var(--space-sm);">
                        <i class="fas fa-phone"></i>
                        +962 6 123 4567
                    </p>
                    <p style="color: var(--gray-400); margin-bottom: var(--space-sm);">
                        <i class="fas fa-map-marker-alt"></i>
                        Irbid, Jordan
                    </p>
                    @auth
                        <p style="color: var(--gray-400);">
                            <i class="fas fa-comments"></i>
                            <a href="{{ route('user.conversations.create') }}" style="color: var(--primary-400); text-decoration: none;">Contact Support</a>
                        </p>
                    @endauth
                </div>
            </div>
            
            <div style="border-top: 1px solid var(--gray-700); margin-top: var(--space-2xl); padding-top: var(--space-lg); text-align: center; color: var(--gray-400);">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Real-time Messaging Scripts -->
    <script>
        // Global variables for real-time messaging
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
            userId: {{ auth()->check() ? auth()->id() : 'null' }}
        };

        // Real-time notification system for conversations
        @auth
        function updateConversationsBadge() {
            fetch('/user/conversations/unread-count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const badge = document.getElementById('conversationsBadge');
                if (data.unread_count > 0) {
                    if (badge) {
                        badge.textContent = data.unread_count;
                        badge.style.display = 'inline';
                    } else {
                        // Create badge if it doesn't exist
                        const supportLink = document.querySelector('a[href="{{ route('user.conversations.index') }}"]');
                        if (supportLink && !supportLink.querySelector('.nav-badge')) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'nav-badge';
                            newBadge.id = 'conversationsBadge';
                            newBadge.textContent = data.unread_count;
                            supportLink.appendChild(newBadge);
                        }
                    }
                } else {
                    if (badge) {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.log('Failed to update conversations badge:', error);
            });
        }

        // Check for new conversations every 30 seconds
        setInterval(updateConversationsBadge, 30000);

        // Update badge on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(updateConversationsBadge, 1000);
        });
        @endauth

        // Auto-hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }, 5000);
            });
        });

        // Enhanced form validation and double-submission prevention
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton && !submitButton.disabled) {
                        // Prevent double submission
                        setTimeout(() => {
                            submitButton.disabled = true;
                            const originalText = submitButton.innerHTML;
                            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                            
                            // Re-enable after 5 seconds as fallback
                            setTimeout(() => {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalText;
                            }, 5000);
                        }, 100);
                    }
                });
            });
        });

        // Smooth scroll for anchor links
        document.addEventListener('DOMContentLoaded', function() {
            const anchorLinks = document.querySelectorAll('a[href^="#"]');
            anchorLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Enhanced error handling for AJAX requests
        window.handleAjaxError = function(xhr, textStatus, errorThrown) {
            console.error('AJAX Error:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                textStatus: textStatus,
                errorThrown: errorThrown
            });

            let errorMessage = 'An error occurred. Please try again.';
            
            if (xhr.status === 419) {
                errorMessage = 'Your session has expired. Please refresh the page.';
                // Auto-refresh after showing message
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to perform this action.';
            } else if (xhr.status === 422) {
                try {
                    const errors = JSON.parse(xhr.responseText);
                    if (errors.errors) {
                        errorMessage = Object.values(errors.errors).flat().join('\n');
                    }
                } catch (e) {
                    errorMessage = 'Validation error occurred.';
                }
            } else if (xhr.status === 500) {
                errorMessage = 'Server error occurred. Please contact support if this persists.';
            }

            // Show error notification
            showErrorNotification(errorMessage);
        };

        // Global error notification function
        window.showErrorNotification = function(message) {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.error-notification');
            existingNotifications.forEach(notification => notification.remove());
            
            // Create new notification
            const notification = document.createElement('div');
            notification.className = 'error-notification';
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-left: auto;">×</button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto-remove after 7 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideOutRight 0.3s ease-in';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }
            }, 7000);
        };

        // Global success notification function
        window.showSuccessNotification = function(message) {
            // Remove existing success notifications
            const existingNotifications = document.querySelectorAll('.success-notification');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className = 'success-notification';
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: var(--space-sm);">
                    <i class="fas fa-check-circle"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer; margin-left: auto;">×</button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideOutRight 0.3s ease-in';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }
            }, 5000);
        };

        // Auto-resize textareas
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 200) + 'px';
                });
            });
        });

        // Enhanced keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for search (if search exists)
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                const searchInput = document.querySelector('input[type="search"], input[name="search"], #search');
                if (searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                }
            }

            // Escape to close modals or clear focus
            if (e.key === 'Escape') {
                const activeElement = document.activeElement;
                if (activeElement && activeElement.blur) {
                    activeElement.blur();
                }
                
                // Close any open modals or dropdowns
                const modals = document.querySelectorAll('.modal, .dropdown-open');
                modals.forEach(modal => {
                    modal.classList.remove('show', 'open', 'dropdown-open');
                });
            }
        });

        // Connection status monitoring
        window.addEventListener('online', function() {
            showSuccessNotification('Connection restored');
            // Update conversations badge when back online
            @auth
            setTimeout(updateConversationsBadge, 1000);
            @endauth
        });

        window.addEventListener('offline', function() {
            showErrorNotification('You are currently offline. Some features may not work.');
        });

        // Performance monitoring (basic)
        window.addEventListener('load', function() {
            const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
            console.log(`🚀 Page loaded in ${loadTime}ms`);
            
            // Report slow loading
            if (loadTime > 3000) {
                console.warn('⚠️ Slow page load detected:', loadTime + 'ms');
            }
        });

        // Debug helper functions
        window.debugApp = function() {
            console.log('🔧 App Debug Info:');
            console.log('Laravel Config:', window.Laravel);
            console.log('Current URL:', window.location.href);
            console.log('CSRF Token:', window.Laravel.csrfToken);
            console.log('Authenticated:', window.Laravel.isAuthenticated);
            console.log('User ID:', window.Laravel.userId);
            console.log('Navigation badges:', document.querySelectorAll('.nav-badge'));
            console.log('Active real-time messaging:', window.realTimeMessaging);
            console.log('Performance:', {
                loadTime: window.performance.timing.loadEventEnd - window.performance.timing.navigationStart,
                domReady: window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart,
                online: navigator.onLine
            });
        };

        // Global app state
        window.app = {
            version: '1.0.0',
            debug: {{ config('app.debug') ? 'true' : 'false' }},
            environment: '{{ config('app.env') }}',
            locale: '{{ app()->getLocale() }}',
            timezone: '{{ config('app.timezone') }}'
        };

        // Service Worker registration (if exists)
        if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('SW registered: ', registration);
                    })
                    .catch(function(registrationError) {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }

        console.log('🎉 Application initialized successfully');
    </script>

    @stack('scripts')
</body>
</html>