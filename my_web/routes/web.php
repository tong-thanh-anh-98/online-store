<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AdminLoginController;

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

/* 
Route::get('/', function () {
    return view('welcome');
});
*/

/**
 * Define the admin routes.
 *
 * This method is responsible for defining the admin routes. It groups the routes under the 'admin' prefix and applies
 */
Route::group(['prefix' => 'admin'], function () {
    /**
     * the 'admin.guest' middleware to the enclosed routes. The '/login' route is mapped to the 'index' method of the
     * 'AdminLoginController' class and named 'admin.login'. The '/authenticate' route is mapped to the 'authenticate'
     * method of the 'AdminLoginController' class and named 'admin.authenticate'.
     */                                 
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });

    /**
     * Define the admin authenticated routes.
     *
     * This method is responsible for defining the admin authenticated routes. It groups the routes under the 'admin' prefix
     * and applies the 'admin.auth' middleware to the enclosed routes.
     */
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');
    });
});