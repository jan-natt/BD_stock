<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('aboutus');
});

// Authenticated users
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    // Redirect to role-specific dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();

        switch ($user->user_type) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'buyer':
                return redirect()->route('buyer.dashboard');
            case 'seller':
                return redirect()->route('seller.dashboard');
            default:
                abort(403, 'Unauthorized');
        }
    })->name('dashboard');

});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Add more admin routes here
});

// Buyer routes
Route::middleware(['auth', 'role:buyer'])->group(function () {
    Route::get('/buyer/dashboard', [BuyerController::class, 'index'])->name('buyer.dashboard');
    // Add more buyer routes here
});

// Seller routes
Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
    // Add more seller routes here
});
