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

// Products (Public)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/images', [ProductController::class, 'getImages'])->name('products.images');

// Educational System (Public)
Route::prefix('educational')->name('educational.')->group(function () {
    Route::get('/', [EducationalController::class, 'index'])->name('index');
    Route::get('/form', [EducationalController::class, 'form'])->name('form');
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
    return redirect()->route('educational.index');
})->name('educational-cards.index');

Route::get('/educational-cards/{id}', function($id) {
    return redirect()->route('educational.index');
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
    // EDUCATIONAL SYSTEM ADMIN ROUTES
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
        
        // Educational Packages Management
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
// UTILITY ROUTES
// ====================================

// Project Structure Debug Route (Remove in production)
Route::get('/project-structure', function () {
    function listFolderFiles($dir, $prefix = '') {
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
        $structure .= "$label:\n";
        $structure .= listFolderFiles(base_path($path));
        $structure .= "\n\n";
    }

    return "<pre>$structure</pre>";
});

// Fallback route for admin redirect
Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
})->middleware(['auth']);