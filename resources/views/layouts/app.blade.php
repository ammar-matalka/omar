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
            transition: all var(--transition-normal);
        }
        
        /* RTL Support */
        [dir="rtl"] {
            direction: rtl;
        }
        
        [dir="rtl"] .navbar {
            direction: rtl;
        }
        
        [dir="rtl"] .nav-links {
            direction: rtl;
        }
        
        /* Language Toggle Button */
        .language-toggle {
            padding: 8px 15px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background: var(--surface);
            color: var(--on-surface);
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all var(--transition-fast);
            font-family: inherit;
        }
        
        .language-toggle:hover {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.1);
        }
        
        .language-toggle:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.1);
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
                    <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" data-translate="home">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" data-translate="products">Products</a></li>
                    <li><a href="{{ route('educational-cards.index') }}" class="nav-link {{ request()->routeIs('educational-cards.*') ? 'active' : '' }}" data-translate="educational-cards">Educational Cards</a></li>
                    
                    @auth
                        <!-- User Menu -->
                        <li class="dropdown">
                            <a href="{{ route('user.profile.show') }}" class="nav-link" data-translate="my-profile">
                                <i class="fas fa-user"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>
                        
                        <!-- Cart Icon -->
                        <li>
                            <a href="{{ route('cart.index') }}" class="nav-link" data-translate="cart">
                                <i class="fas fa-shopping-cart"></i>
                                Cart
                            </a>
                        </li>
                        
                        <!-- Wishlist Icon -->
                        <li>
                            <a href="{{ route('wishlist.index') }}" class="nav-link" data-translate="wishlist">
                                <i class="fas fa-heart"></i>
                                Wishlist
                            </a>
                        </li>
                        
                        <!-- Logout -->
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm" data-translate="logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="btn btn-secondary btn-sm" data-translate="login">Login</a></li>
                        <li><a href="{{ route('register') }}" class="btn btn-primary btn-sm" data-translate="register">Register</a></li>
                    @endauth
                    
                    <!-- Language Toggle Button -->
                    <li>
                        <button onclick="toggleLanguage()" id="languageBtn" class="language-toggle">
                            <span id="flagIcon">🇺🇸</span>
                            <span id="langText">English</span>
                            <span style="margin-left: 5px;">⇄</span>
                        </button>
                    </li>
                    
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
                    <p style="color: var(--gray-400);">
                        <i class="fas fa-map-marker-alt"></i>
                        Irbid, Jordan
                    </p>
                </div>
            </div>
            
            <div style="border-top: 1px solid var(--gray-700); margin-top: var(--space-2xl); padding-top: var(--space-lg); text-align: center; color: var(--gray-400);">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Language Toggle Script -->
    <script>
        // تعريف المتغيرات العامة
        let currentLanguage = localStorage.getItem('siteLanguage') || 'en';
        
        // قاموس الترجمات
        const translations = {
            'home': { en: 'Home', ar: 'الرئيسية' },
            'products': { en: 'Products', ar: 'المنتجات' },
            'educational-cards': { en: 'Educational Cards', ar: 'البطاقات التعليمية' },
            'cart': { en: 'Cart', ar: 'السلة' },
            'wishlist': { en: 'Wishlist', ar: 'المفضلة' },
            'login': { en: 'Login', ar: 'تسجيل الدخول' },
            'register': { en: 'Register', ar: 'تسجيل جديد' },
            'logout': { en: 'Logout', ar: 'تسجيل الخروج' },
            'my-profile': { en: 'My Profile', ar: 'ملفي الشخصي' },
            'my-orders': { en: 'My Orders', ar: 'طلباتي' },
            'quick-links': { en: 'Quick Links', ar: 'روابط سريعة' },
            'account': { en: 'Account', ar: 'الحساب' },
            'contact': { en: 'Contact', ar: 'اتصل بنا' },
            'footer-description': { 
                en: 'Your trusted partner for educational products and services.', 
                ar: 'شريكك الموثوق للمنتجات والخدمات التعليمية.' 
            }
        };
        
        // تطبيق اللغة عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            applyLanguage(currentLanguage);
            updateButton();
        });
        
        // دالة تبديل اللغة
        function toggleLanguage() {
            if (currentLanguage === 'en') {
                currentLanguage = 'ar';
                applyLanguage('ar');
            } else {
                currentLanguage = 'en';
                applyLanguage('en');
            }
            
            // حفظ اللغة في localStorage
            localStorage.setItem('siteLanguage', currentLanguage);
            updateButton();
        }
        
        // تحديث نص وأيقونة الزر
        function updateButton() {
            const flagIcon = document.getElementById('flagIcon');
            const langText = document.getElementById('langText');
            
            if (currentLanguage === 'ar') {
                flagIcon.textContent = '🇸🇦';
                langText.textContent = 'العربية';
            } else {
                flagIcon.textContent = '🇺🇸';
                langText.textContent = 'English';
            }
        }
        
        // تطبيق اللغة على الصفحة
        function applyLanguage(lang) {
            // تغيير اتجاه الصفحة
            document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
            document.documentElement.setAttribute('lang', lang === 'ar' ? 'ar' : 'en');
            
            // تطبيق الترجمات على العناصر
            const elementsToTranslate = document.querySelectorAll('[data-translate]');
            elementsToTranslate.forEach(element => {
                const key = element.getAttribute('data-translate');
                if (translations[key] && translations[key][lang]) {
                    // إذا كان العنصر يحتوي على أيقونة، احتفظ بها
                    const icon = element.querySelector('i');
                    if (icon) {
                        const iconHtml = icon.outerHTML;
                        element.innerHTML = iconHtml + ' ' + translations[key][lang];
                    } else {
                        element.textContent = translations[key][lang];
                    }
                }
            });
            
            // تطبيق خط اللغة العربية إذا لزم الأمر
            if (lang === 'ar') {
                document.body.style.fontFamily = "'Cairo', 'Inter', system-ui, -apple-system, sans-serif";
            } else {
                document.body.style.fontFamily = "'Inter', 'Cairo', system-ui, -apple-system, sans-serif";
            }
        }
        
        // دالة لإضافة ترجمة ديناميكية
        function addTranslation(key, enText, arText) {
            translations[key] = { en: enText, ar: arText };
        }
        
        // دالة للحصول على النص المترجم
        function getTranslation(key) {
            return translations[key] && translations[key][currentLanguage] 
                ? translations[key][currentLanguage] 
                : key;
        }
        
        // دالة لإعادة تطبيق اللغة (مفيدة للمحتوى الديناميكي)
        function reapplyLanguage() {
            applyLanguage(currentLanguage);
        }
        
        // تصدير الدوال للاستخدام العام
        window.toggleLanguage = toggleLanguage;
        window.addTranslation = addTranslation;
        window.getTranslation = getTranslation;
        window.reapplyLanguage = reapplyLanguage;
        window.currentLanguage = currentLanguage;
    </script>

    @stack('scripts')
</body>
</html>--gray-400);" data-translate="footer-description">Your trusted partner for educational products and services.</p>
                </div>
                <div>
                    <h4 style="margin-bottom: var(--space-md); color: white;" data-translate="quick-links">Quick Links</h4>
                    <ul style="list-style: none; color: var(--gray-400);">
                        <li style="margin-bottom: var(--space-sm);"><a href="{{ route('home') }}" style="color: inherit; text-decoration: none;" data-translate="home">Home</a></li>
                        <li style="margin-bottom: var(--space-sm);"><a href="{{ route('products.index') }}" style="color: inherit; text-decoration: none;" data-translate="products">Products</a></li>
                        <li style="margin-bottom: var(--space-sm);"><a href="{{ route('educational-cards.index') }}" style="color: inherit; text-decoration: none;" data-translate="educational-cards">Educational Cards</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="margin-bottom: var(--space-md); color: white;" data-translate="account">Account</h4>
                    <ul style="list-style: none; color: var(--gray-400);">
                        @auth
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('user.profile.show') }}" style="color: inherit; text-decoration: none;" data-translate="my-profile">My Profile</a></li>
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('orders.index') }}" style="color: inherit; text-decoration: none;" data-translate="my-orders">My Orders</a></li>
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('wishlist.index') }}" style="color: inherit; text-decoration: none;" data-translate="wishlist">Wishlist</a></li>
                        @else
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('login') }}" style="color: inherit; text-decoration: none;" data-translate="login">Login</a></li>
                            <li style="margin-bottom: var(--space-sm);"><a href="{{ route('register') }}" style="color: inherit; text-decoration: none;" data-translate="register">Register</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 style="margin-bottom: var(--space-md); color: white;" data-translate="contact">Contact</h4>
                    <p style="color: var(--gray-400); margin-bottom: var(--space-sm);">
                        <i class="fas fa-envelope"></i>
                        info@example.com
                    </p>
                    <p style="color: var(--gray-400); margin-bottom: var(--space-sm);">
                        <i class="fas fa-phone"></i>
                        +962 6 123 4567
                    </p>
                    <p style="color: var(