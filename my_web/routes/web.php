<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\Admin\SubCategoryController;

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

        // category routes starts
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/edit/{categoryId}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{categoryId}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categoryId}', [CategoryController::class, 'destroy'])->name('categories.delete');

        // subcategory routes starts
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/edit/{subCategory}', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

        // Route upload image
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        /**
         * Generate a slug from the given title.
         *
         * @param \Illuminate\Http\Request $request The request object.
         * @return \Illuminate\Http\JsonResponse The JSON response containing the generated slug and status.
         */
        Route::get('/getSlug', function(Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug,
            ]);
        })->name('getSlug');
    });
});