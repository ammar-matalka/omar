<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EducationalCardsController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\ConfirmPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ====================================
// AUTHENTICATION ROUTES (SIMPLE)
// ====================================

// Simple Registration Routes (بدون كود تحقق)
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('guest');

// Login Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request')->middleware('guest');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('guest');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update')->middleware('guest');

// Email Verification Routes (اختياري - يمكنك حذفها إذا لم تكن تريد التحقق من الإيميل)
Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice')->middleware('auth');
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify')->middleware(['auth', 'signed']);
Route::post('/email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend')->middleware(['auth', 'throttle:6,1']);

// Password Confirmation Routes
Route::get('/password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm')->middleware('auth');
Route::post('/password/confirm', [App\Http\Controllers\Auth\ConfirmPasswordController::class, 'confirm'])->middleware('auth');


// ====================================
// PUBLIC ROUTES
// ====================================

// Home and Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Product Routes (Public)
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/images', [App\Http\Controllers\ProductController::class, 'getImages'])->name('products.images');
Route::get('/shop', [App\Http\Controllers\ProductController::class, 'index'])->name('shop.index');

// Categories
Route::get('/categories', [HomeController::class, 'allCategories'])->name('categories.all');
Route::get('/products/all', [HomeController::class, 'allProducts'])->name('products.all');

// Educational Cards Routes (Public)
Route::prefix('educational-cards')->name('educational-cards.')->group(function () {
    Route::get('/', [EducationalCardsController::class, 'index'])->name('index');
    Route::get('/search', [EducationalCardsController::class, 'search'])->name('search');
    Route::get('/{platform}', [EducationalCardsController::class, 'showGrades'])->name('grades');
    Route::get('/{platform}/{grade}', [EducationalCardsController::class, 'showSubjects'])->name('subjects');
    Route::get('/{platform}/{grade}/{subject}', [EducationalCardsController::class, 'showCards'])->name('cards');
    Route::get('/card/{card}', [EducationalCardsController::class, 'showCard'])->name('show');
});

// Public Testimonials
Route::get('/testimonials', [App\Http\Controllers\TestimonialController::class, 'index'])->name('testimonials.index');

// ====================================
// AUTHENTICATED USER ROUTES
// ====================================
Route::middleware(['auth'])->group(function () {
    
    // Cart Routes
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addItem'])->name('cart.addItem');
    Route::put('/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [App\Http\Controllers\CartController::class, 'getCartCount'])->name('cart.count');
    
    // Educational Cards Cart
    Route::post('/educational-cards/add-to-cart', [EducationalCardsController::class, 'addToCart'])->name('educational-cards.add-to-cart');
    
    // Checkout Routes
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/apply-coupon', [App\Http\Controllers\CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');
    Route::delete('/checkout/remove-coupon', [App\Http\Controllers\CheckoutController::class, 'removeCoupon'])->name('checkout.removeCoupon');
    
    // Order Routes
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    
    // Testimonial Routes (User)
    Route::get('/testimonials/create/{order}', [App\Http\Controllers\TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');
    
    // Wishlist Routes
    Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist', [App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{productId}', [App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');
    
    // Coupon Routes (User)
    Route::get('/coupons', [App\Http\Controllers\CouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/{coupon}', [App\Http\Controllers\CouponController::class, 'show'])->name('coupons.show');
});

// ====================================
// USER AREA ROUTES
// ====================================
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    
    // User Conversations
    Route::get('/conversations', [App\Http\Controllers\User\ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/create', [App\Http\Controllers\User\ConversationController::class, 'create'])->name('conversations.create');
    Route::post('/conversations', [App\Http\Controllers\User\ConversationController::class, 'store'])->name('conversations.store');
    Route::get('/conversations/{conversation}', [App\Http\Controllers\User\ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/reply', [App\Http\Controllers\User\ConversationController::class, 'reply'])->name('conversations.reply');
    
    // User Profile Routes
    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\User\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [App\Http\Controllers\User\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.update-password');
});

// ====================================
// ADMIN ROUTES
// ====================================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {    
    // Dashboard Routes
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/notifications', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getNotificationCounts'])->name('dashboard.notifications');
    Route::get('/dashboard/export', [App\Http\Controllers\Admin\AdminDashboardController::class, 'exportDashboardData'])->name('dashboard.export');
    
    // Admin Profile Routes
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Categories Management
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    
    // Products Management
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::post('/products/{product}/update-image-order', [App\Http\Controllers\Admin\ProductController::class, 'updateImageOrder'])->name('products.update-image-order');
    
    // Educational Cards Management
    Route::resource('platforms', App\Http\Controllers\Admin\PlatformController::class);
    Route::resource('grades', App\Http\Controllers\Admin\GradeController::class);
    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);
    Route::resource('educational-cards', App\Http\Controllers\Admin\EducationalCardController::class);
    
    // AJAX Routes for Educational Cards
    Route::get('/platforms/{platform}/grades', [App\Http\Controllers\Admin\SubjectController::class, 'getGradesByPlatform'])->name('platforms.grades');
    Route::get('/grades/{grade}/subjects', [App\Http\Controllers\Admin\EducationalCardController::class, 'getSubjectsByGrade'])->name('grades.subjects');
    Route::post('/educational-cards/{educationalCard}/update-image-order', [App\Http\Controllers\Admin\EducationalCardController::class, 'updateImageOrder'])->name('educational-cards.update-image-order');
    
    // Orders Management
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    
    // Users Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Conversations Management
    Route::get('/conversations', [App\Http\Controllers\Admin\ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [App\Http\Controllers\Admin\ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/reply', [App\Http\Controllers\Admin\ConversationController::class, 'reply'])->name('conversations.reply');
    Route::post('/conversations/mark-all-read', [AdminDashboardController::class, 'markAllConversationsRead'])->name('conversations.mark-all-read');
    
    // Testimonials Management
    Route::get('/testimonials', [App\Http\Controllers\Admin\TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/{testimonial}', [App\Http\Controllers\Admin\TestimonialController::class, 'show'])->name('testimonials.show');
    Route::patch('/testimonials/{testimonial}/approve', [App\Http\Controllers\Admin\TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::patch('/testimonials/{testimonial}/reject', [App\Http\Controllers\Admin\TestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::delete('/testimonials/{testimonial}', [App\Http\Controllers\Admin\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    Route::post('/testimonials/mark-all-reviewed', [App\Http\Controllers\Admin\AdminDashboardController::class, 'markAllTestimonialsReviewed'])->name('testimonials.mark-all-reviewed');
    
    // Coupons Management
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
    Route::get('/coupons/generate/multiple', [App\Http\Controllers\Admin\CouponController::class, 'generateMultiple'])->name('coupons.generate');
    Route::post('/coupons/generate/multiple', [App\Http\Controllers\Admin\CouponController::class, 'storeMultiple'])->name('coupons.storeMultiple');
});

// ====================================
// DEVELOPMENT/DEBUG ROUTES
// ====================================
if (app()->environment('local')) {
    Route::get('/project-structure', function () {
        function listFolderFiles($dir, $prefix = '') {
            if (!is_dir($dir)) return '';
            
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
    })->name('debug.structure');
}