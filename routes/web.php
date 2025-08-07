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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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

// Educational Controllers
use App\Http\Controllers\EducationalController;
use App\Http\Controllers\EducationalApiController;
use App\Http\Controllers\EducationalCartController;

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

// Educational Admin Controllers
use App\Http\Controllers\Admin\GenerationController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\EducationalPackageController;
use App\Http\Controllers\Admin\EducationalPricingController;
use App\Http\Controllers\Admin\EducationalInventoryController;
use App\Http\Controllers\Admin\ShippingRegionController;

// NEW: Separated Educational Controllers
use App\Http\Controllers\Admin\DigitalCardsController;
use App\Http\Controllers\Admin\PhysicalBookletsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================
// ROUTE MODEL BINDING للفصل بين الأنواع
// ====================================

// Custom route model binding للبطاقات الرقمية
Route::bind('digitalCard', function ($value) {
    $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
    
    if (!$digitalProductType) {
        abort(500, 'نوع المنتج الرقمي غير متوفر في النظام');
    }
    
    $package = \App\Models\EducationalPackage::with('productType')->find($value);
    
    if (!$package) {
        abort(404, 'البطاقة الرقمية غير موجودة');
    }
    
    // التأكد من أن هذا منتج رقمي
    if ($package->product_type_id !== $digitalProductType->id) {
        abort(404, 'المعرف المطلوب ليس بطاقة رقمية');
    }
    
    if (!$package->productType || !$package->productType->is_digital) {
        abort(404, 'المعرف المطلوب ليس بطاقة رقمية');
    }
    
    // Additional validation for data integrity
    if (!is_null($package->pages_count) || !is_null($package->weight_grams)) {
        Log::error('Digital card has physical properties', [
            'package_id' => $package->id,
            'pages_count' => $package->pages_count,
            'weight_grams' => $package->weight_grams
        ]);
        abort(500, 'البطاقة الرقمية تحتوي على بيانات غير صحيحة');
    }
    
    return $package;
});

// Custom route model binding للدوسيات الورقية
Route::bind('physicalBooklet', function ($value) {
    $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
    
    if (!$physicalProductType) {
        abort(500, 'نوع المنتج الورقي غير متوفر في النظام');
    }
    
    $package = \App\Models\EducationalPackage::with('productType')->find($value);
    
    if (!$package) {
        abort(404, 'الدوسية الورقية غير موجودة');
    }
    
    // التأكد من أن هذا منتج ورقي
    if ($package->product_type_id !== $physicalProductType->id) {
        abort(404, 'المعرف المطلوب ليس دوسية ورقية');
    }
    
    if (!$package->productType || $package->productType->is_digital) {
        abort(404, 'المعرف المطلوب ليس دوسية ورقية');
    }
    
    // Additional validation for data integrity
    if (!is_null($package->duration_days) || !is_null($package->lessons_count)) {
        Log::error('Physical booklet has digital properties', [
            'package_id' => $package->id,
            'duration_days' => $package->duration_days,
            'lessons_count' => $package->lessons_count
        ]);
        abort(500, 'الدوسية الورقية تحتوي على بيانات غير صحيحة');
    }
    
    // Physical booklets must have pages_count
    if (is_null($package->pages_count) || $package->pages_count <= 0) {
        Log::error('Physical booklet missing pages_count', [
            'package_id' => $package->id,
            'pages_count' => $package->pages_count
        ]);
        abort(500, 'الدوسية الورقية لا تحتوي على عدد صفحات صحيح');
    }
    
    return $package;
});

// ====================================
// PUBLIC ROUTES
// ====================================

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Products (Public)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/images', [ProductController::class, 'getImages'])->name('products.images');

// Educational System (Public)
// إضافة هذه الـ routes المفقودة في قسم Educational System (Public) في ملف web.php

// Educational System (Public) - أضف هذه الـ routes
Route::prefix('educational')->name('educational.')->group(function () {
    Route::get('/', [EducationalController::class, 'index'])->name('index');
    Route::get('/form', [EducationalController::class, 'form'])->name('form');
    
    // إضافة الـ routes المفقودة
    Route::get('/digital-cards', [EducationalController::class, 'digitalCards'])->name('digital-cards');
    Route::get('/physical-booklets', [EducationalController::class, 'physicalBooklets'])->name('physical-booklets');
    
    // Routes لتفاصيل المنتجات
    Route::get('/digital-cards/{digitalCard}', [EducationalController::class, 'showDigitalCard'])->name('digital-cards.show');
    Route::get('/physical-booklets/{physicalBooklet}', [EducationalController::class, 'showPhysicalBooklet'])->name('physical-booklets.show');
    
    Route::get('/verify-card', [EducationalController::class, 'verifyCard'])->name('verify-card');
    Route::post('/verify-card', [EducationalController::class, 'processCardVerification'])->name('process-card-verification');
    
    // Educational API Routes (Public)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/product-types', [EducationalApiController::class, 'productTypes'])->name('product-types');
        Route::get('/generations', [EducationalApiController::class, 'generations'])->name('generations');
        Route::get('/subjects', [EducationalApiController::class, 'subjects'])->name('subjects');
        Route::get('/teachers', [EducationalApiController::class, 'teachers'])->name('teachers');
        Route::get('/platforms', [EducationalApiController::class, 'platforms'])->name('platforms');
        Route::get('/packages', [EducationalApiController::class, 'packages'])->name('packages');
        Route::get('/regions', [EducationalApiController::class, 'regions'])->name('regions');
        Route::post('/calculate-price', [EducationalApiController::class, 'calculatePrice'])->name('calculate-price');
        Route::post('/check-inventory', [EducationalApiController::class, 'checkInventory'])->name('check-inventory');
    });
});

// Educational Cards (Backward compatibility)
Route::get('/educational-cards', function() {
    return redirect()->route('educational.digital-cards');
})->name('educational-cards.index');

Route::get('/educational-cards/{id}', function($id) {
    return redirect()->route('educational.digital-cards');
})->name('educational-cards.show');

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

// Email Verification Routes
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Password Confirmation Routes
Route::get('/password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('/password/confirm', [ConfirmPasswordController::class, 'confirm']);

// ====================================
// AUTHENTICATED USER ROUTES
// ====================================

Route::middleware(['auth'])->group(function () {
    
    // Home/Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Cart Management
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add-item', [CartController::class, 'addItem'])->name('addItem');
        Route::patch('/update/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
    });
    
    // Educational Cart Management
    Route::prefix('educational/cart')->name('educational.cart.')->group(function () {
        Route::post('/add', [EducationalCartController::class, 'store'])->name('add');
        Route::patch('/update/{cartItem}', [EducationalCartController::class, 'update'])->name('update');
        Route::delete('/remove/{cartItem}', [EducationalCartController::class, 'destroy'])->name('remove');
    });
    
    // Educational User Features
    Route::prefix('educational')->name('educational.')->group(function () {
        Route::get('/my-cards', [EducationalController::class, 'myCards'])->name('my-cards');
        Route::get('/cards/{card}', [EducationalController::class, 'showCard'])->name('cards.show');
        Route::post('/update-expired-cards', [EducationalController::class, 'updateExpiredCards'])->name('update-expired-cards');
    });
    
    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
        Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('applyCoupon');
        Route::post('/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('removeCoupon');
    });
    
    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
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
        
        Route::prefix('conversations')->name('conversations.')->group(function () {
            Route::get('/', [UserConversationController::class, 'index'])->name('index');
            Route::get('/create', [UserConversationController::class, 'create'])->name('create');
            Route::post('/', [UserConversationController::class, 'store'])->name('store');
            Route::get('/{conversation}', [UserConversationController::class, 'show'])->name('show');
            Route::post('/{conversation}/reply', [UserConversationController::class, 'reply'])->name('reply');
            
            // Real-time messaging for users
            Route::get('/{conversation}/check-new-messages', [UserConversationController::class, 'checkNewMessages'])->name('check-new-messages');
            Route::get('/check-updates', [UserConversationController::class, 'checkUpdates'])->name('check-updates');
            
            Route::post('/{conversation}/mark-read', [UserConversationController::class, 'markAsRead'])->name('mark-read');
            Route::get('/unread/count', [UserConversationController::class, 'getUnreadCount'])->name('unread-count');
        });
    });
});

// ====================================
// ADMIN ROUTES
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
        Route::get('/generate/multiple', [AdminCouponController::class, 'generateMultiple'])->name('generate');
        Route::post('/generate/multiple', [AdminCouponController::class, 'storeMultiple'])->name('storeMultiple');
    });
    
    // Conversations Management
    Route::prefix('conversations')->name('conversations.')->group(function () {
        Route::get('/', [AdminConversationController::class, 'index'])->name('index');
        Route::get('/{conversation}', [AdminConversationController::class, 'show'])->name('show');
        Route::post('/{conversation}/reply', [AdminConversationController::class, 'reply'])->name('reply');
        
        // Real-time messaging API
        Route::get('/{conversation}/check-new-messages', [AdminConversationController::class, 'checkNewMessages'])->name('check-new-messages');
        Route::get('/check-updates', [AdminConversationController::class, 'checkUpdates'])->name('check-updates');
        
        Route::patch('/{conversation}/mark-read', [AdminConversationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [AdminConversationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/counts/get', [AdminConversationController::class, 'getCounts'])->name('get-counts');
    });
    
    // ====================================
    // EDUCATIONAL SYSTEM ADMIN ROUTES (المُصححة)
    // ====================================
    
    Route::prefix('educational')->name('educational.')->group(function () {
        
        // Generations Management
        Route::resource('generations', GenerationController::class);
        Route::post('/generations/{generation}/toggle-status', [GenerationController::class, 'toggleStatus'])->name('generations.toggle-status');
        Route::get('/generations/{generation}/subjects', [GenerationController::class, 'getSubjects'])->name('generations.subjects');
        Route::get('/generations/{generation}/statistics', [GenerationController::class, 'statistics'])->name('generations.statistics');
        
        // Subjects Management
        Route::resource('subjects', SubjectController::class);
        Route::post('/subjects/{subject}/toggle-status', [SubjectController::class, 'toggleStatus'])->name('subjects.toggle-status');
        Route::get('/subjects/{subject}/teachers', [SubjectController::class, 'getTeachers'])->name('subjects.teachers');
        Route::post('/subjects/{subject}/clone', [SubjectController::class, 'clone'])->name('subjects.clone');
        Route::get('/subjects/{subject}/statistics', [SubjectController::class, 'statistics'])->name('subjects.statistics');
        Route::get('/subjects/export/csv', [SubjectController::class, 'export'])->name('subjects.export');
        
        // Teachers Management
        Route::resource('teachers', TeacherController::class);
        Route::post('/teachers/{teacher}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('teachers.toggle-status');
        Route::get('/teachers/{teacher}/platforms', [TeacherController::class, 'getPlatforms'])->name('teachers.platforms');
        Route::post('/teachers/{teacher}/clone', [TeacherController::class, 'clone'])->name('teachers.clone');
        Route::get('/teachers/{teacher}/statistics', [TeacherController::class, 'statistics'])->name('teachers.statistics');
        Route::get('/teachers/export/csv', [TeacherController::class, 'export'])->name('teachers.export');
        
        // Platforms Management
        Route::resource('platforms', PlatformController::class);
        Route::post('/platforms/{platform}/toggle-status', [PlatformController::class, 'toggleStatus'])->name('platforms.toggle-status');
        Route::get('/platforms/{platform}/packages', [PlatformController::class, 'getPackages'])->name('platforms.packages');
        Route::post('/platforms/{platform}/clone', [PlatformController::class, 'clone'])->name('platforms.clone');
        Route::get('/platforms/{platform}/statistics', [PlatformController::class, 'statistics'])->name('platforms.statistics');
        Route::get('/platforms/export/csv', [PlatformController::class, 'export'])->name('platforms.export');
        
        // ====================================
        // البطاقات الرقمية - DIGITAL CARDS
        // ====================================
        
        Route::prefix('digital-cards')->name('digital-cards.')->group(function () {
            // Basic CRUD operations
            Route::get('/', [DigitalCardsController::class, 'index'])->name('index');
            Route::get('/create', [DigitalCardsController::class, 'create'])->name('create');
            Route::post('/', [DigitalCardsController::class, 'store'])->name('store');
            
            // Routes with custom model binding
            Route::get('/{digitalCard}', [DigitalCardsController::class, 'show'])->name('show');
            Route::get('/{digitalCard}/edit', [DigitalCardsController::class, 'edit'])->name('edit');
            Route::patch('/{digitalCard}', [DigitalCardsController::class, 'update'])->name('update');
            Route::delete('/{digitalCard}', [DigitalCardsController::class, 'destroy'])->name('destroy');
            
            // Additional operations
            Route::post('/{digitalCard}/toggle-status', [DigitalCardsController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{digitalCard}/clone', [DigitalCardsController::class, 'clone'])->name('clone');
            
            // Reports and exports
            Route::get('/statistics/overview', [DigitalCardsController::class, 'statistics'])->name('statistics');
            Route::get('/export/csv', [DigitalCardsController::class, 'export'])->name('export');
        });
        
        // ====================================
        // الدوسيات الورقية - PHYSICAL BOOKLETS
        // ====================================
        
        Route::prefix('physical-booklets')->name('physical-booklets.')->group(function () {
            // Basic CRUD operations
            Route::get('/', [PhysicalBookletsController::class, 'index'])->name('index');
            Route::get('/create', [PhysicalBookletsController::class, 'create'])->name('create');
            Route::post('/', [PhysicalBookletsController::class, 'store'])->name('store');
            
            // Routes with custom model binding
            Route::get('/{physicalBooklet}', [PhysicalBookletsController::class, 'show'])->name('show');
            Route::get('/{physicalBooklet}/edit', [PhysicalBookletsController::class, 'edit'])->name('edit');
            Route::patch('/{physicalBooklet}', [PhysicalBookletsController::class, 'update'])->name('update');
            Route::delete('/{physicalBooklet}', [PhysicalBookletsController::class, 'destroy'])->name('destroy');
            
            // Additional operations
            Route::post('/{physicalBooklet}/toggle-status', [PhysicalBookletsController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{physicalBooklet}/clone', [PhysicalBookletsController::class, 'clone'])->name('clone');
            Route::post('/{physicalBooklet}/add-inventory', [PhysicalBookletsController::class, 'addInventory'])->name('add-inventory');
            
            // Reports and exports
            Route::get('/statistics/overview', [PhysicalBookletsController::class, 'statistics'])->name('statistics');
            Route::get('/reports/low-stock', [PhysicalBookletsController::class, 'lowStockReport'])->name('low-stock-report');
            Route::get('/reports/no-inventory', [PhysicalBookletsController::class, 'noInventoryReport'])->name('no-inventory-report');
            Route::get('/export/csv', [PhysicalBookletsController::class, 'export'])->name('export');
        });
        
        // ====================================
        // GENERAL PACKAGES ROUTE (للتوافق مع النظام الحالي)
        // ====================================
        
        Route::resource('packages', EducationalPackageController::class);
        Route::post('/packages/{package}/toggle-status', [EducationalPackageController::class, 'toggleStatus'])->name('packages.toggle-status');
        Route::post('/packages/{package}/clone', [EducationalPackageController::class, 'clone'])->name('packages.clone');
        Route::get('/packages/{package}/statistics', [EducationalPackageController::class, 'statistics'])->name('packages.statistics');
        Route::get('/packages/export/csv', [EducationalPackageController::class, 'export'])->name('packages.export');
        
        // Educational Pricing Management
        Route::resource('pricing', EducationalPricingController::class);
        Route::post('/pricing/{pricing}/toggle-status', [EducationalPricingController::class, 'toggleStatus'])->name('pricing.toggle-status');
        Route::post('/pricing/bulk-update', [EducationalPricingController::class, 'bulkUpdate'])->name('pricing.bulk-update');
        Route::get('/pricing/export/csv', [EducationalPricingController::class, 'export'])->name('pricing.export');
        
        // Educational Inventory Management
        Route::resource('inventory', EducationalInventoryController::class);
        Route::post('/inventory/{inventory}/add-stock', [EducationalInventoryController::class, 'addStock'])->name('inventory.add-stock');
        Route::post('/inventory/{inventory}/adjust-reserved', [EducationalInventoryController::class, 'adjustReserved'])->name('inventory.adjust-reserved');
        Route::post('/inventory/bulk-update', [EducationalInventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
        Route::get('/inventory/low-stock-report', [EducationalInventoryController::class, 'lowStockReport'])->name('inventory.low-stock-report');
        Route::get('/inventory/export/csv', [EducationalInventoryController::class, 'export'])->name('inventory.export');
        
        // Shipping Regions Management
        Route::resource('regions', ShippingRegionController::class);
        Route::post('/regions/{region}/toggle-status', [ShippingRegionController::class, 'toggleStatus'])->name('regions.toggle-status');
        Route::post('/regions/bulk-update-shipping', [ShippingRegionController::class, 'bulkUpdateShipping'])->name('regions.bulk-update-shipping');
        Route::post('/regions/bulk-toggle-status', [ShippingRegionController::class, 'bulkToggleStatus'])->name('regions.bulk-toggle-status');
        Route::get('/regions/export/csv', [ShippingRegionController::class, 'export'])->name('regions.export');
        
        // ====================================
        // EDUCATIONAL API ROUTES FOR ADMIN
        // ====================================
        
        Route::prefix('api')->name('api.')->group(function () {
            
            // API للبطاقات الرقمية
            Route::prefix('digital-cards')->name('digital-cards.')->group(function () {
                Route::get('/platforms/{platform}/packages', function($platformId) {
                    $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
                    
                    if (!$digitalProductType) {
                        return response()->json(['error' => 'نوع المنتج الرقمي غير متوفر'], 500);
                    }
                    
                    $packages = \App\Models\EducationalPackage::where('platform_id', $platformId)
                                                            ->where('product_type_id', $digitalProductType->id)
                                                            ->with('productType')
                                                            ->orderBy('name')
                                                            ->get();
                    return response()->json($packages);
                })->name('by-platform');
                
                Route::get('/check-name-availability', function(\Illuminate\Http\Request $request) {
                    $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
                    
                    if (!$digitalProductType) {
                        return response()->json(['error' => 'نوع المنتج الرقمي غير متوفر'], 500);
                    }
                    
                    $exists = \App\Models\EducationalPackage::where('name', $request->name)
                                                          ->where('platform_id', $request->platform_id)
                                                          ->where('product_type_id', $digitalProductType->id)
                                                          ->exists();
                    return response()->json(['available' => !$exists]);
                })->name('check-name');
                
                Route::post('/validate-digital-properties', function(\Illuminate\Http\Request $request) {
                    $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
                    
                    if (!$digitalProductType) {
                        return response()->json(['error' => 'نوع المنتج الرقمي غير متوفر'], 500);
                    }
                    
                    $validation = $digitalProductType->validatePackageProperties($request->all());
                    
                    return response()->json([
                        'valid' => $validation['valid'],
                        'errors' => $validation['errors']
                    ]);
                })->name('validate-properties');
            });
            
            // API للدوسيات الورقية
            Route::prefix('physical-booklets')->name('physical-booklets.')->group(function () {
                Route::get('/platforms/{platform}/packages', function($platformId) {
                    $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
                    
                    if (!$physicalProductType) {
                        return response()->json(['error' => 'نوع المنتج الورقي غير متوفر'], 500);
                    }
                    
                    $packages = \App\Models\EducationalPackage::where('platform_id', $platformId)
                                                            ->where('product_type_id', $physicalProductType->id)
                                                            ->with(['productType', 'educationalInventory'])
                                                            ->orderBy('name')
                                                            ->get();
                    return response()->json($packages);
                })->name('by-platform');
                
                Route::get('/check-name-availability', function(\Illuminate\Http\Request $request) {
                    $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
                    
                    if (!$physicalProductType) {
                        return response()->json(['error' => 'نوع المنتج الورقي غير متوفر'], 500);
                    }
                    
                    $exists = \App\Models\EducationalPackage::where('name', $request->name)
                                                          ->where('platform_id', $request->platform_id)
                                                          ->where('product_type_id', $physicalProductType->id)
                                                          ->exists();
                    return response()->json(['available' => !$exists]);
                })->name('check-name');
                
                Route::post('/calculate-shipping-weight', function(\Illuminate\Http\Request $request) {
                    $pages = $request->input('pages', 0);
                    $paperType = $request->input('paper_type', 5); // جرام per page
                    $estimatedWeight = $pages * $paperType;
                    
                    return response()->json([
                        'pages' => $pages,
                        'paper_weight_per_page' => $paperType,
                        'estimated_weight_grams' => $estimatedWeight,
                        'estimated_weight_kg' => round($estimatedWeight / 1000, 2)
                    ]);
                })->name('calculate-weight');
                
                Route::post('/validate-physical-properties', function(\Illuminate\Http\Request $request) {
                    $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
                    
                    if (!$physicalProductType) {
                        return response()->json(['error' => 'نوع المنتج الورقي غير متوفر'], 500);
                    }
                    
                    $validation = $physicalProductType->validatePackageProperties($request->all());
                    
                    return response()->json([
                        'valid' => $validation['valid'],
                        'errors' => $validation['errors']
                    ]);
                })->name('validate-properties');
                
                Route::post('/check-inventory-exists', function(\Illuminate\Http\Request $request) {
                    $inventory = \App\Models\EducationalInventory::where('generation_id', $request->generation_id)
                                                                ->where('subject_id', $request->subject_id)
                                                                ->where('teacher_id', $request->teacher_id)
                                                                ->where('platform_id', $request->platform_id)
                                                                ->where('package_id', $request->package_id)
                                                                ->first();
                    
                    return response()->json([
                        'exists' => $inventory !== null,
                        'inventory' => $inventory ? [
                            'id' => $inventory->id,
                            'quantity_available' => $inventory->quantity_available,
                            'quantity_reserved' => $inventory->quantity_reserved,
                            'actual_available' => $inventory->actual_available,
                            'stock_status' => $inventory->stock_status
                        ] : null
                    ]);
                })->name('check-inventory');
            });
            
            // API عامة للسلسلة التعليمية (مشتركة)
            Route::get('/generations', function() {
                return response()->json(\App\Models\Generation::active()->orderBy('birth_year', 'desc')->get());
            })->name('generations');
            
            Route::get('/subjects', function(\Illuminate\Http\Request $request) {
                $query = \App\Models\Subject::active()->with('generation')->orderBy('name');
                
                if ($request->filled('generation_id')) {
                    $query->where('generation_id', $request->generation_id);
                }
                
                return response()->json($query->get());
            })->name('subjects');
            
            Route::get('/teachers', function(\Illuminate\Http\Request $request) {
                $query = \App\Models\Teacher::active()->with('subject')->orderBy('name');
                
                if ($request->filled('subject_id')) {
                    $query->where('subject_id', $request->subject_id);
                }
                
                return response()->json($query->get());
            })->name('teachers');
            
            Route::get('/platforms', function(\Illuminate\Http\Request $request) {
                $query = \App\Models\Platform::active()->with('teacher')->orderBy('name');
                
                if ($request->filled('teacher_id')) {
                    $query->where('teacher_id', $request->teacher_id);
                }
                
                return response()->json($query->get());
            })->name('platforms');
            
            Route::get('/packages', function(\Illuminate\Http\Request $request) {
                $query = \App\Models\EducationalPackage::with('productType');
                
                if ($request->filled('platform_id')) {
                    $query->where('platform_id', $request->platform_id);
                }
                
                if ($request->filled('type')) {
                    if ($request->type === 'digital') {
                        $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
                        if ($digitalProductType) {
                            $query->where('product_type_id', $digitalProductType->id);
                        }
                    } elseif ($request->type === 'physical') {
                        $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
                        if ($physicalProductType) {
                            $query->where('product_type_id', $physicalProductType->id);
                        }
                    }
                }
                
                return response()->json($query->orderBy('name')->get());
            })->name('packages');
            
            Route::get('/package-details/{package}', function(\App\Models\EducationalPackage $package) {
                $package->load(['productType', 'platform.teacher.subject.generation']);
                
                return response()->json([
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'is_digital' => $package->is_digital,
                    'duration_days' => $package->duration_days,
                    'lessons_count' => $package->lessons_count,
                    'pages_count' => $package->pages_count,
                    'weight_grams' => $package->weight_grams,
                    'product_type' => $package->productType->name,
                    'chain' => [
                        'generation' => $package->platform->teacher->subject->generation->display_name,
                        'subject' => $package->platform->teacher->subject->name,
                        'teacher' => $package->platform->teacher->name,
                        'platform' => $package->platform->name
                    ]
                ]);
            })->name('package-details');
        });
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

// ====================================
// DASHBOARD WIDGETS ROUTES
// ====================================

Route::prefix('admin/dashboard')->name('admin.dashboard.')->middleware(['auth'])->group(function () {
    
    Route::get('/educational-overview', function() {
        $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
        $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
        
        $stats = [
            // البطاقات الرقمية
            'digital_cards' => [
                'total' => $digitalProductType ? \App\Models\EducationalPackage::where('product_type_id', $digitalProductType->id)->count() : 0,
                'active' => $digitalProductType ? \App\Models\EducationalPackage::where('product_type_id', $digitalProductType->id)->where('is_active', true)->count() : 0,
                'cards_generated' => \App\Models\EducationalCard::count(),
                'cards_used' => \App\Models\EducationalCard::where('status', 'used')->count()
            ],
            
            // الدوسيات الورقية
            'physical_booklets' => [
                'total' => $physicalProductType ? \App\Models\EducationalPackage::where('product_type_id', $physicalProductType->id)->count() : 0,
                'active' => $physicalProductType ? \App\Models\EducationalPackage::where('product_type_id', $physicalProductType->id)->where('is_active', true)->count() : 0,
                'in_stock' => \App\Models\EducationalInventory::where('quantity_available', '>', 0)->count(),
                'low_stock' => \App\Models\EducationalInventory::where('quantity_available', '>', 0)
                                                             ->where('quantity_available', '<=', 10)->count(),
                'out_of_stock' => \App\Models\EducationalInventory::where('quantity_available', '<=', 0)->count()
            ],
            
            // إحصائيات عامة
            'general' => [
                'generations' => \App\Models\Generation::active()->count(),
                'subjects' => \App\Models\Subject::active()->count(),
                'teachers' => \App\Models\Teacher::active()->count(),
                'platforms' => \App\Models\Platform::active()->count(),
                'total_revenue' => DB::table('order_items')
                                     ->join('educational_packages', 'order_items.package_id', '=', 'educational_packages.id')
                                     ->sum('order_items.price')
            ]
        ];
        
        return response()->json($stats);
    })->name('educational-overview');
});

// ====================================
// LEGACY SUPPORT ROUTES
// ====================================

// إعادة توجيه الـ routes القديمة للنظام الجديد
Route::get('/admin/educational/packages/create', function(\Illuminate\Http\Request $request) {
    // إذا كان المستخدم يريد إنشاء منتج معين
    if ($request->has('type')) {
        if ($request->type === 'digital') {
            return redirect()->route('admin.educational.digital-cards.create');
        } elseif ($request->type === 'physical') {
            return redirect()->route('admin.educational.physical-booklets.create');
        }
    }
    
    // إظهار صفحة اختيار نوع المنتج
    return view('admin.educational.packages.select-type', [
        'digital_route' => route('admin.educational.digital-cards.create'),
        'physical_route' => route('admin.educational.physical-booklets.create')
    ]);
})->middleware(['auth'])->name('admin.educational.packages.create-redirect');

Route::get('/admin/educational/packages/{id}/edit', function($id) {
    $package = \App\Models\EducationalPackage::findOrFail($id);
    
    $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
    $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
    
    if ($digitalProductType && $package->product_type_id === $digitalProductType->id) {
        return redirect()->route('admin.educational.digital-cards.edit', $package);
    } elseif ($physicalProductType && $package->product_type_id === $physicalProductType->id) {
        return redirect()->route('admin.educational.physical-booklets.edit', $package);
    }
    
    // Fallback based on properties
    if ($package->productType && $package->productType->is_digital) {
        return redirect()->route('admin.educational.digital-cards.edit', $package);
    } else {
        return redirect()->route('admin.educational.physical-booklets.edit', $package);
    }
})->middleware(['auth'])->name('admin.educational.packages.edit-redirect');

Route::get('/admin/educational/packages/{id}', function($id) {
    $package = \App\Models\EducationalPackage::findOrFail($id);
    
    $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
    $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
    
    if ($digitalProductType && $package->product_type_id === $digitalProductType->id) {
        return redirect()->route('admin.educational.digital-cards.show', $package);
    } elseif ($physicalProductType && $package->product_type_id === $physicalProductType->id) {
        return redirect()->route('admin.educational.physical-booklets.show', $package);
    }
    
    // Fallback based on properties
    if ($package->productType && $package->productType->is_digital) {
        return redirect()->route('admin.educational.digital-cards.show', $package);
    } else {
        return redirect()->route('admin.educational.physical-booklets.show', $package);
    }
})->middleware(['auth'])->name('admin.educational.packages.show-redirect');

// ====================================
// DATA INTEGRITY ROUTES
// ====================================

Route::prefix('admin/educational/maintenance')->name('admin.educational.maintenance.')->middleware(['auth'])->group(function () {
    
    // Fix data integrity issues
    Route::post('/fix-digital-cards', function() {
        $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
        
        if (!$digitalProductType) {
            return response()->json(['error' => 'نوع المنتج الرقمي غير متوفر'], 500);
        }
        
        $fixed = 0;
        $digitalCards = \App\Models\EducationalPackage::where('product_type_id', $digitalProductType->id)->get();
        
        foreach ($digitalCards as $card) {
            $updated = false;
            
            if (!is_null($card->pages_count)) {
                $card->pages_count = null;
                $updated = true;
            }
            
            if (!is_null($card->weight_grams)) {
                $card->weight_grams = null;
                $updated = true;
            }
            
            if ($updated) {
                $card->save();
                $fixed++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "تم إصلاح {$fixed} بطاقة رقمية",
            'fixed_count' => $fixed
        ]);
    })->name('fix-digital-cards');
    
    Route::post('/fix-physical-booklets', function() {
        $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
        
        if (!$physicalProductType) {
            return response()->json(['error' => 'نوع المنتج الورقي غير متوفر'], 500);
        }
        
        $fixed = 0;
        $physicalBooklets = \App\Models\EducationalPackage::where('product_type_id', $physicalProductType->id)->get();
        
        foreach ($physicalBooklets as $booklet) {
            $updated = false;
            
            if (!is_null($booklet->duration_days)) {
                $booklet->duration_days = null;
                $updated = true;
            }
            
            if (!is_null($booklet->lessons_count)) {
                $booklet->lessons_count = null;
                $updated = true;
            }
            
            if ($updated) {
                $booklet->save();
                $fixed++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "تم إصلاح {$fixed} دوسية ورقية",
            'fixed_count' => $fixed
        ]);
    })->name('fix-physical-booklets');
    
    // Check data integrity
    Route::get('/check-integrity', function() {
        $digitalProductType = \App\Models\EducationalProductType::getDigitalCardType();
        $physicalProductType = \App\Models\EducationalProductType::getPhysicalBookletType();
        
        $issues = [];
        
        if ($digitalProductType) {
            $corruptedDigitalCards = \App\Models\EducationalPackage::where('product_type_id', $digitalProductType->id)
                                                                  ->where(function($q) {
                                                                      $q->whereNotNull('pages_count')
                                                                        ->orWhereNotNull('weight_grams');
                                                                  })
                                                                  ->count();
            
            if ($corruptedDigitalCards > 0) {
                $issues[] = "يوجد {$corruptedDigitalCards} بطاقة رقمية تحتوي على خصائص ورقية";
            }
        }
        
        if ($physicalProductType) {
            $corruptedPhysicalBooklets = \App\Models\EducationalPackage::where('product_type_id', $physicalProductType->id)
                                                                      ->where(function($q) {
                                                                          $q->whereNotNull('duration_days')
                                                                            ->orWhereNotNull('lessons_count');
                                                                      })
                                                                      ->count();
            
            if ($corruptedPhysicalBooklets > 0) {
                $issues[] = "يوجد {$corruptedPhysicalBooklets} دوسية ورقية تحتوي على خصائص رقمية";
            }
            
            $missingPagesCount = \App\Models\EducationalPackage::where('product_type_id', $physicalProductType->id)
                                                              ->where(function($q) {
                                                                  $q->whereNull('pages_count')
                                                                    ->orWhere('pages_count', '<=', 0);
                                                              })
                                                              ->count();
            
            if ($missingPagesCount > 0) {
                $issues[] = "يوجد {$missingPagesCount} دوسية ورقية بدون عدد صفحات صحيح";
            }
        }
        
        return response()->json([
            'issues_count' => count($issues),
            'issues' => $issues,
            'healthy' => count($issues) === 0
        ]);
    })->name('check-integrity');
    
    // Fix all integrity issues at once
    Route::post('/fix-all-issues', function() {
        $result = \App\Models\EducationalPackage::fixAllIntegrityIssues();
        
        return response()->json([
            'success' => $result['success'],
            'message' => "تم فحص {$result['total_issues']} عنصر وإصلاح {$result['fixed_count']} مشكلة",
            'total_issues' => $result['total_issues'],
            'fixed_count' => $result['fixed_count']
        ]);
    })->name('fix-all-issues');
});

// ====================================
// QUICK ACCESS ROUTES
// ====================================

Route::prefix('quick')->name('quick.')->middleware(['auth'])->group(function () {
    
    // اختصار للبطاقات الرقمية
    Route::get('/digital-cards', function() {
        return redirect()->route('admin.educational.digital-cards.index');
    })->name('digital-cards');
    
    // اختصار للدوسيات الورقية
    Route::get('/physical-booklets', function() {
        return redirect()->route('admin.educational.physical-booklets.index');
    })->name('physical-booklets');
    
    // اختصار لإنشاء بطاقة رقمية
    Route::get('/create-digital-card', function() {
        return redirect()->route('admin.educational.digital-cards.create');
    })->name('create-digital-card');
    
    // اختصار لإنشاء دوسية ورقية
    Route::get('/create-physical-booklet', function() {
        return redirect()->route('admin.educational.physical-booklets.create');
    })->name('create-physical-booklet');
    
    // اختصار للمخزون المنخفض
    Route::get('/low-stock', function() {
        return redirect()->route('admin.educational.physical-booklets.low-stock-report');
    })->name('low-stock');
    
    // اختصار للتسعير
    Route::get('/pricing', function() {
        return redirect()->route('admin.educational.pricing.index');
    })->name('pricing');
    
    // اختصار للمخزون
    Route::get('/inventory', function() {
        return redirect()->route('admin.educational.inventory.index');
    })->name('inventory');
    
    // اختصار لفحص سلامة البيانات
    Route::get('/check-integrity', function() {
        return redirect()->route('admin.educational.maintenance.check-integrity');
    })->name('check-integrity');
});

// ====================================
// UTILITY ROUTES
// ====================================

// Fallback route for admin redirect
Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
})->middleware(['auth']);

// Project Structure Debug Route (Remove in production)
Route::get('/project-structure', function () {
    function listFolderFiles($dir, $prefix = '') {
        if (!is_dir($dir)) {
            return '';
        }
        
        $ffs = scandir($dir);
        $output = '';
        
        foreach ($ffs as $ff) {
            if ($ff != '.' && $ff != '..') {
                $path = $dir . DIRECTORY_SEPARATOR . $ff;
                $output .= $prefix . '├── ' . $ff;
                
                if (is_dir($path)) {
                    $output .= "/\n" . listFolderFiles($path, $prefix . '│   ');
                } else {
                    $output .= "\n";
                }
            }
        }
        
        return $output;
    }

    $basePaths = [
        'app/Http/Controllers' => 'Controllers',
        'app/Models' => 'Models',
        'config' => 'Config',
        'database/migrations' => 'Migrations',
        'resources/views' => 'Views',
        'routes' => 'Routes',
    ];

    $structure = '';

    foreach ($basePaths as $path => $label) {
        $fullPath = base_path($path);
        if (is_dir($fullPath)) {
            $structure .= "$label:\n";
            $structure .= listFolderFiles($fullPath);
            $structure .= "\n\n";
        }
    }

    return "<pre>$structure</pre>";
})->name('debug.project-structure');

// Health Check Route
Route::get('/health-check', function() {
    $checks = [
        'database' => false,
        'educational_product_types' => false,
        'digital_product_type' => false,
        'physical_product_type' => false,
        'data_integrity' => false
    ];
    
    try {
        // Check database connection
        DB::connection()->getPdo();
        $checks['database'] = true;
        
        // Check if educational_product_types table exists
        if (Schema::hasTable('educational_product_types')) {
            $checks['educational_product_types'] = true;
            
            // Check if product types exist
            $digitalType = \App\Models\EducationalProductType::getDigitalCardType();
            $physicalType = \App\Models\EducationalProductType::getPhysicalBookletType();
            
            if ($digitalType) {
                $checks['digital_product_type'] = true;
            }
            
            if ($physicalType) {
                $checks['physical_product_type'] = true;
            }
            
            // Check data integrity
            $integrityIssues = \App\Models\EducationalPackage::getPackagesWithIntegrityIssues();
            $checks['data_integrity'] = $integrityIssues->count() === 0;
        }
    } catch (\Exception $e) {
        // Handle errors
    }
    
    $allHealthy = array_reduce($checks, function($carry, $item) {
        return $carry && $item;
    }, true);
    
    return response()->json([
        'status' => $allHealthy ? 'healthy' : 'issues_detected',
        'checks' => $checks,
        'timestamp' => now()->toISOString()
    ], $allHealthy ? 200 : 500);
})->name('health-check');

// API Status Route
Route::get('/api/status', function() {
    return response()->json([
        'status' => 'active',
        'version' => '1.0.0',
        'educational_system' => 'separated',
        'timestamp' => now()->toISOString()
    ]);
})->name('api.status');