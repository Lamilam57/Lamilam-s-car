<?php

use App\Http\Controllers\Auth\FacebookAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarImagesController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

/*-------------------------------
| Public Home Page
--------------------------------*/
Route::get('/', [HomeController::class, 'index'])->name('home');

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
| Auth-only Routes
| Only logged-in users can access
--------------------------------*/
Route::middleware('auth')->group(function () {
    // Car Routes
    Route::get('/car/search', [CarController::class, 'search'])->name('car.search');

    Route::get('/car/watchlist', [CarController::class, 'watchlist'])->name('car.watchlist');

    // Route::get('/car/car_images', [CarImagesController::class, 'index'])->name('car.car_images');

    // Car Images Pages
    Route::get('/car/{car}/images', [CarImagesController::class, 'index'])->name('car.images.index');
    Route::put('/cars/{car}/images', [CarImagesController::class, 'update'])->name('car.images.update');
    Route::post('/cars/{car}/images', [CarImagesController::class, 'store'])->name('car.images.store');
    Route::delete('/car/{car}', [CarController::class, 'destroy'])
    ->name('cars.destroy');

    Route::resource('car', CarController::class);

    Route::get('/api/models/{maker}', [ModelController::class, 'getByMaker']);

    // Favourite Car
    Route::post('/car/{car}/favourite-toggle', [FavouriteController::class, 'toggle'])
    ->middleware('auth')
    ->name('cars.favourite.toggle');

    // Dynamic cities for a state (AJAX)
    Route::get('/api/cities/{state}', [CityController::class, 'getByState']);

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});
