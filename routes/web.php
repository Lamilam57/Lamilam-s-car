<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppFeedbackController;
use App\Http\Controllers\Auth\FacebookAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarImagesController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*-------------------------------
| Public Home Page
--------------------------------*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/car/notavailable', [AdminController::class, 'notavailable'])->middleware(['auth', 'role:admin'])->name('car.notavailable');
    // Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

/*-------------------------------
| Guest-only Routes
| Only users who are NOT logged in
--------------------------------*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'postLogin'])->name('postLogin');

    // Signup
    Route::get('/signup', [SignupController::class, 'create'])->name('signup');
    Route::post('/signup', [SignupController::class, 'postSignUp'])->name('postSignUp');

    // Reset Password
    Route::get('/reset-password', [ResetPasswordController::class, 'create'])->name('reset-password');

    // Google auth
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);


    // Facebook auth
    Route::get('/auth/facebook', [FacebookAuthController::class, 'redirect'])
    ->name('facebook.login');
    Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback']);
});

/*-------------------------------
| Auth-user-only Routes
| Only logged-in users can access
--------------------------------*/

Route::middleware('auth', 'role:user', 'verified')->group(function () {
    // Car store registration with subscription verification
    Route::post('/cars/store', [CarController::class,'store'])
        ->middleware(['auth','subscription'])
        ->name('car.store');

    Route::get('/car/watchlist', [CarController::class, 'watchlist'])->name('car.watchlist');

    // Route::get('/car/car_images', [CarImagesController::class, 'index'])->name('car.car_images');

    // Car Images Pages
    Route::get('/car/{car}/images', [CarImagesController::class, 'index'])->name('car.images.index');
    Route::put('/cars/{car}/images', [CarImagesController::class, 'update'])->name('car.images.update');
    Route::post('/cars/{car}/images', [CarImagesController::class, 'store'])->name('car.images.store');
    Route::delete('/car/{car}', [CarController::class, 'destroy'])->name('cars.destroy');

    // Route::resource('car', CarController::class);

    
    // Favourite Car
    Route::post('/car/{car}/favourite-toggle', [FavouriteController::class, 'toggle'])
    ->middleware('auth')
    ->name('cars.favourite.toggle');

    // Dynamic cities for a state (AJAX)
    // Route::get('/api/cities/{state}', [CityController::class, 'getByState']);

    
    Route::post('/reviews/{car}', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');

    
    Route::get('/app-feedback', [AppFeedbackController::class,'index'])
    ->middleware('auth')
    ->name('feedback.index');

    Route::post('/app-feedback', [AppFeedbackController::class,'store'])
        ->middleware('auth')
        ->name('feedback.store');
});

/*-------------------------------
| Auth-admin-only Routes
| Only logged-in users can access
--------------------------------*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/viewedCar', [AdminController::class, 'viewedCar'])
        ->name('admin.viewedCar');

    Route::get('admin/viewedCar/{car}', [AdminController::class, 'carViewers'])
        ->name('admin.carViewers');

    Route::get('admin/users/{user}', [AdminController::class, 'show'])
        ->name('admin.users.show');

    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users');
    
    //    Feedback
    Route::get('/admin/feedback', [AdminController::class,'feedback'])
        ->name('admin.feedback');

    Route::patch('/admin/feedback/{feedback}/status', [AdminController::class,'updateFeedbackStatus'])
        ->name('admin.feedback.status');

    // Subscription
    Route::get('/admin/subscriptions', [AdminController::class, 'subscriptions'])
        ->name('admin.subscriptions');

    Route::post('/admin/subscriptions/{subscription}/update', [AdminController::class, 'updateSubscription'])
        ->name('admin.subscriptions.update');

    Route::get('/admin/pending-cars', [AdminController::class, 'pendingCars'])
        ->name('admin.pending.cars');
        
    // ADMIN RESOURCE
    Route::resource('admin', AdminController::class);


});



Route::middleware(['auth','verified'])->group(function () {
    // Car Routes
    Route::get('/car/search', [CarController::class, 'search'])->name('car.search');
    Route::get('/api/models/{maker}', [ModelController::class, 'getByMaker']);
    Route::resource('car', CarController::class);

    // Personal Information
    Route::get('/profile', [HomeController::class, 'show'])->name('profile');
    Route::put('/profile', [HomeController::class, 'update'])->name('profile.update');
    // Dynamic cities API
    Route::get('/api/cities/{state}', [CityController::class, 'getByState']);

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});


/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/car');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Request Reset Link
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');


/*
|--------------------------------------------------------------------------
| Reset Password
|--------------------------------------------------------------------------
*/

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.forgot-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');


Route::middleware('auth')->group(function () {

    // Route::get('/subscription', [SubscriptionController::class,'plans'])
    //     ->name('subscription.plans');

    // Route::post('/subscription/pay', [SubscriptionController::class,'redirectToGateway'])
    //     ->name('subscription.pay');

        
    Route::get('/subscription/callback', [SubscriptionController::class,'callback'])
        ->name('subscription.callback');
        
    Route::post('/paystack/webhook', [WebhookController::class, 'webhook']);
    
    Route::get('/subscribe', function () {
        return view('subscription.plan');
    })->name('subscription.page');

    Route::post('/subscribe', [SubscriptionController::class,'subscribe'])
        ->name('subscription.start');
});