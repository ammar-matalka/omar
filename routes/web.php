<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\EducationalCardsController;

// Auth Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\StepRegistrationController;

// User Controllers
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\ConversationController as UserConversationController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\ConversationController as AdminConversationController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\EducationalSubjectsController;
use App\Http\Controllers\Admin\GenerationsController;
use App\Http\Controllers\Admin\EducationalCardOrdersController;
// النظام الجديد
use App\Http\Controllers\Admin\TeachersController;
use App\Http\Controllers\Admin\PlatformsController;
use App\Http\Controllers\Admin\DossiersController;
use App\Http\Controllers\Admin\EducationalOrdersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================
// PUBLIC ROUTES
// ====================================

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Language Switching
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/language/current', [LanguageController::class, 'current'])->name('language.current');

// Products (Public)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/images', [ProductController::class, 'getImages'])->name('products.images');

// Educational Cards (Public) - النظام الجديد
Route::get('/educational-cards', [EducationalCardsController::class, 'index'])->name('educational-cards.index');
Route::get('/educational-cards/subjects/{generation}', [EducationalCardsController::class, 'getSubjects'])->name('educational-cards.subjects');
Route::get('/educational-cards/teachers/{generation}/{subject}', [EducationalCardsController::class, 'getTeachers'])->name('educational-cards.teachers');
Route::get('/educational-cards/platforms/{generation}/{subject}/{teacher}', [EducationalCardsController::class, 'getPlatforms'])->name('educational-cards.platforms');
Route::get('/educational-cards/dossiers/{generation}/{subject}/{teacher}/{platform}/{semester}', [EducationalCardsController::class, 'getDossiers'])->name('educational-cards.dossiers');
Route::post('/educational-cards/calculate-price', [EducationalCardsController::class, 'calculatePrice'])->name('educational-cards.calculate-price');

// ====================================
// AUTHENTICATION ROUTES
// ====================================

// Step Registration Routes
Route::prefix('register')->name('register.')->group(function () {
    Route::get('/step1', [StepRegistrationController::class, 'showStep1'])->name('step1');
    Route::post('/step1', [StepRegistrationController::class, 'processStep1'])->name('step1.process');
    Route::get('/step2', [StepRegistrationController::class, 'showStep2'])->name('step2');
    Route::post('/step2', [StepRegistrationController::class, 'processStep2'])->name('step2.process');
    Route::post('/resend-code', [StepRegistrationController::class, 'resendCode'])->name('resend-code');
    Route::get('/back-to-step1', [StepRegistrationController::class, 'backToStep1'])->name('back-to-step1');
});

// Standard Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// ====================================
// AUTHENTICATED USER ROUTES
// ====================================

Route::middleware(['auth'])->group(function () {
    
    // Home/Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Cart Management
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'addItem'])->name('add');
        Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
    });
    
    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
        Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('apply-coupon');
        Route::post('/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('remove-coupon');
    });
    
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    });
    
    // Educational Cards - النظام الجديد
    Route::prefix('educational-cards')->name('educational-cards.')->group(function () {
        Route::post('/submit-order', [EducationalCardsController::class, 'submitOrder'])->name('submit-order');
        Route::get('/my-orders', [EducationalCardsController::class, 'myOrders'])->name('my-orders');
        Route::get('/orders/{order}', [EducationalCardsController::class, 'showOrder'])->name('show-order');
        Route::get('/search-orders', [EducationalCardsController::class, 'searchOrders'])->name('search-orders');
    });
    
    // Coupons
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/{coupon}', [CouponController::class, 'show'])->name('show');
    });
    
    // Testimonials
    Route::prefix('testimonials')->name('testimonials.')->group(function () {
        Route::get('/create/{order}', [TestimonialController::class, 'create'])->name('create');
        Route::post('/', [TestimonialController::class, 'store'])->name('store');
    });
    
    // Wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/add', [WishlistController::class, 'store'])->name('add');
        Route::delete('/{product}', [WishlistController::class, 'destroy'])->name('remove');
        Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
    });
    
    // User Profile Management
    Route::prefix('user')->name('user.')->group(function () {
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [UserProfileController::class, 'show'])->name('show');
            Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [UserProfileController::class, 'update'])->name('update');
            Route::get('/change-password', [UserProfileController::class, 'changePassword'])->name('change-password');
            Route::patch('/change-password', [UserProfileController::class, 'updatePassword'])->name('update-password');
        });
        
        // User Conversations
        Route::prefix('conversations')->name('conversations.')->group(function () {
            Route::get('/', [UserConversationController::class, 'index'])->name('index');
            Route::get('/create', [UserConversationController::class, 'create'])->name('create');
            Route::post('/', [UserConversationController::class, 'store'])->name('store');
            Route::get('/{conversation}', [UserConversationController::class, 'show'])->name('show');
            Route::post('/{conversation}/reply', [UserConversationController::class, 'reply'])->name('reply');
            Route::get('/{conversation}/check-messages', [UserConversationController::class, 'checkNewMessages'])->name('check-messages');
            Route::post('/{conversation}/mark-read', [UserConversationController::class, 'markAsRead'])->name('mark-read');
            Route::get('/unread/count', [UserConversationController::class, 'getUnreadCount'])->name('unread-count');
        });
    });
});

// ====================================
// ADMIN ROUTES - النظام المحدث
// ====================================

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/notifications', [AdminDashboardController::class, 'getNotificationCounts'])->name('dashboard.notifications');
    Route::post('/dashboard/mark-conversations-read', [AdminDashboardController::class, 'markAllConversationsRead'])->name('dashboard.mark-conversations-read');
    Route::post('/dashboard/mark-testimonials-reviewed', [AdminDashboardController::class, 'markAllTestimonialsReviewed'])->name('dashboard.mark-testimonials-reviewed');
    Route::get('/dashboard/export', [AdminDashboardController::class, 'exportDashboardData'])->name('dashboard.export');
    
    // Categories Management
    Route::resource('categories', AdminCategoryController::class);
    
    // Products Management
    Route::resource('products', AdminProductController::class);
    Route::post('/products/{product}/update-image-order', [AdminProductController::class, 'updateImageOrder'])->name('products.update-image-order');
    
    // Orders Management
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
    
    // Educational Generations Management
    Route::resource('generations', GenerationsController::class);
    Route::post('/generations/{generation}/toggle-status', [GenerationsController::class, 'toggleStatus'])->name('generations.toggle-status');
    
    // Educational Subjects Management
    Route::resource('educational-subjects', EducationalSubjectsController::class);
    Route::post('/educational-subjects/{educationalSubject}/toggle-status', [EducationalSubjectsController::class, 'toggleStatus'])->name('educational-subjects.toggle-status');
    Route::get('/educational-subjects/generation/{generation}', [EducationalSubjectsController::class, 'getByGeneration'])->name('educational-subjects.by-generation');
    
    // Educational Cards Management - إضافة المسارات المفقودة
    Route::prefix('educational-cards')->name('educational-cards.')->group(function () {
        Route::get('/', function() { 
            return redirect()->route('admin.educational-card-orders.index'); 
        })->name('index');
        Route::get('/create', function() { 
            return redirect()->route('admin.generations.create'); 
        })->name('create');
        Route::post('/', function() { 
            return redirect()->route('admin.educational-card-orders.index'); 
        })->name('store');
    });

    // Educational Card Orders Management - طلبات البطاقات التعليمية
    Route::prefix('educational-card-orders')->name('educational-card-orders.')->group(function () {
        Route::get('/', [EducationalCardOrdersController::class, 'index'])->name('index');
        Route::get('/{educationalCardOrder}', [EducationalCardOrdersController::class, 'show'])->name('show');
        Route::patch('/{educationalCardOrder}/status', [EducationalCardOrdersController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{educationalCardOrder}', [EducationalCardOrdersController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-update', [EducationalCardOrdersController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/export/csv', [EducationalCardOrdersController::class, 'export'])->name('export');
        Route::get('/stats/quick', [EducationalCardOrdersController::class, 'quickStats'])->name('quick-stats');
    });
    
    // Teachers Management - جديد
    Route::resource('teachers', TeachersController::class);
    Route::post('/teachers/{teacher}/toggle-status', [TeachersController::class, 'toggleStatus'])->name('teachers.toggle-status');
    Route::get('/teachers/generation/{generation}/subject/{subject}', [TeachersController::class, 'getByGenerationAndSubject'])->name('teachers.by-generation-subject');
    Route::get('/teachers/all', [TeachersController::class, 'getAll'])->name('teachers.all');
    Route::post('/teachers/bulk-action', [TeachersController::class, 'bulkAction'])->name('teachers.bulk-action');
    Route::get('/teachers/export', [TeachersController::class, 'export'])->name('teachers.export');
    
    // Platforms Management - جديد
    Route::resource('platforms', PlatformsController::class);
    Route::post('/platforms/{platform}/toggle-status', [PlatformsController::class, 'toggleStatus'])->name('platforms.toggle-status');
    Route::get('/platforms/all', [PlatformsController::class, 'getAll'])->name('platforms.all');
    
    // Dossiers Management - جديد
    Route::resource('dossiers', DossiersController::class);
    Route::post('/dossiers/{dossier}/toggle-status', [DossiersController::class, 'toggleStatus'])->name('dossiers.toggle-status');
    Route::get('/dossiers/filtered', [DossiersController::class, 'getFiltered'])->name('dossiers.filtered');
    
    // Educational Orders Management - النظام الجديد يحل محل educational-card-orders
    Route::prefix('educational-orders')->name('educational-orders.')->group(function () {
        Route::get('/', [EducationalOrdersController::class, 'index'])->name('index');
        Route::get('/{order}', [EducationalOrdersController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [EducationalOrdersController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{order}', [EducationalOrdersController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-update', [EducationalOrdersController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/export/csv', [EducationalOrdersController::class, 'export'])->name('export');
        Route::get('/stats/quick', [EducationalOrdersController::class, 'quickStats'])->name('quick-stats');
    });
    
    // Users Management
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/verify-email', [AdminUserController::class, 'verifyEmail'])->name('users.verify-email');
    Route::post('/users/{user}/send-password-reset', [AdminUserController::class, 'sendPasswordReset'])->name('users.send-password-reset');
    Route::get('/users/statistics/get', [AdminUserController::class, 'getStatistics'])->name('users.statistics');
    Route::get('/users/export/csv', [AdminUserController::class, 'export'])->name('users.export');
    
    // Testimonials Management
    Route::prefix('testimonials')->name('testimonials.')->group(function () {
        Route::get('/', [AdminTestimonialController::class, 'index'])->name('index');
        Route::get('/{testimonial}', [AdminTestimonialController::class, 'show'])->name('show');
        Route::patch('/{testimonial}/approve', [AdminTestimonialController::class, 'approve'])->name('approve');
        Route::patch('/{testimonial}/reject', [AdminTestimonialController::class, 'reject'])->name('reject');
        Route::delete('/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('destroy');
    });
    
    // Coupons Management
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [AdminCouponController::class, 'index'])->name('index');
        Route::get('/create', [AdminCouponController::class, 'create'])->name('create');
        Route::post('/', [AdminCouponController::class, 'store'])->name('store');
        Route::get('/{coupon}', [AdminCouponController::class, 'show'])->name('show');
        Route::get('/{coupon}/edit', [AdminCouponController::class, 'edit'])->name('edit');
        Route::patch('/{coupon}', [AdminCouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}', [AdminCouponController::class, 'destroy'])->name('destroy');
        
        // Multiple Coupons Generation
        Route::get('/generate/multiple', [AdminCouponController::class, 'generateMultiple'])->name('generate-multiple');
        Route::post('/generate/multiple', [AdminCouponController::class, 'storeMultiple'])->name('store-multiple');
    });
    
    // Conversations Management
    Route::prefix('conversations')->name('conversations.')->group(function () {
        Route::get('/', [AdminConversationController::class, 'index'])->name('index');
        Route::get('/{conversation}', [AdminConversationController::class, 'show'])->name('show');
        Route::post('/{conversation}/reply', [AdminConversationController::class, 'reply'])->name('reply');
        Route::get('/{conversation}/check-messages', [AdminConversationController::class, 'checkNewMessages'])->name('check-messages');
        Route::post('/{conversation}/mark-read', [AdminConversationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [AdminConversationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/counts/get', [AdminConversationController::class, 'getCounts'])->name('get-counts');
    });
    
    // Admin Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'show'])->name('show');
        Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [AdminProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [AdminProfileController::class, 'changePassword'])->name('change-password');
        Route::patch('/change-password', [AdminProfileController::class, 'updatePassword'])->name('update-password');
    });
});