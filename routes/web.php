<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BenefitsController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\frontend\BusinessController;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\RentalController;
use App\Http\Controllers\frontend\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/register/otp/send', [RegisterController::class, 'sendOtp'])->name('register.otp.send');
Route::post('/register/otp/verify', [RegisterController::class, 'verifyOtp'])->name('register.otp.verify');

Route::prefix('shops')->name('shops.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
});

Route::prefix('business')->name('business.')->group(function () {
    Route::get('/new', [BusinessController::class, 'create'])->name('create');
    Route::get('/{business}', [BusinessController::class, 'show'])->name('show');
    Route::get('/{business}/enquiry', [BusinessController::class, 'enquiry'])->name('enquiry');
    Route::post('/{business}/quote', [BusinessController::class, 'quote'])->name('quote');
});

Route::prefix('rentals')->name('rentals.')->group(function () {
    Route::get('/', [RentalController::class, 'index'])->name('index');
    Route::get('/{rental}', [RentalController::class, 'show'])->name('show');
});

Route::get('/category/{category}', [ShopController::class, 'category'])->name('category.show');

// Authenticated routes (Accessible to both Pending & Active users)
Route::middleware('auth')->group(function () {
    // User Dashboard & Profile (accessible immediately after registration)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::put('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::get('/dashboard/referrals', [DashboardController::class, 'referrals'])->name('dashboard.referrals');
    Route::get('/dashboard/idcard/download', [DashboardController::class, 'downloadIdCard'])->name('dashboard.idcard.download');

    // Subscription & Course Purchase
    Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'processSelection'])->name('subscription.checkout');
    Route::post('/subscription/payment/verify', [SubscriptionController::class, 'verifyPayment'])->name('subscription.payment.verify');
    Route::get('/subscription/receipt/{payment?}', [SubscriptionController::class, 'downloadReceipt'])->name('subscription.receipt.download');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    })->name('logout');

    // Protected Active User Routes (Requires purchasing at least 1 course)
    Route::middleware('active')->group(function () {
        // Course & Video Learning Routes
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
        Route::post('/courses/{course}/videos/{video}/progress', [CourseController::class, 'updateVideoProgress'])->name('courses.video.progress');

        // Certificate Routes
        Route::get('/certificate', [CertificateController::class, 'show'])->name('certificate.show');
        Route::get('/certificate/download', [CertificateController::class, 'download'])->name('certificate.download');

        // Benefits Route (Protected by certified middleware)
        Route::middleware('certified')->group(function () {
            Route::get('/benefits', [BenefitsController::class, 'index'])->name('benefits.index');
        });
    });
});

