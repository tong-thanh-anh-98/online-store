<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\ProductSubCategoryController;
use App\Http\Controllers\StoreController;

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

/**
 * Route for the home page.
 *
 * @param \App\Http\Controllers\FrontController@index The controller method responsible for handling the request.
 *
 * @return \Illuminate\Contracts\Routing\UrlGenerator The URL generator for the route.
 */
Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('/store/{categorySlug?}/{subCategorySlug?}', [StoreController::class, 'index'])->name('front.store');
Route::get('/product/{slug}', [StoreController::class, 'product'])->name('front.product');
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::delete('/delete-item', [CartController::class, 'deleteItem'])->name('front.deleteItem.cart');
Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::post('/process-checkout', [CartController::class, 'processCheckout'])->name('front.processCheckout');

Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login',[AuthController::class, 'login'])->name('account.login');
        Route::post('/login',[AuthController::class, 'authenticate'])->name('account.authenticate');
        Route::get('/register',[AuthController::class, 'register'])->name('account.register');
        Route::post('/process-register',[AuthController::class, 'processRegister'])->name('account.processRegister');        
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile',[AuthController::class, 'profile'])->name('account.profile');
        Route::get('/logout',[AuthController::class, 'logout'])->name('account.logout');        
    });
});

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

        // category routes
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/edit/{categoryId}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{categoryId}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categoryId}', [CategoryController::class, 'destroy'])->name('categories.delete');

        // subcategory routes
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/edit/{subCategoryId}', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategoryId}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategoryId}', [SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/edit/{brandId}', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brandId}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brandId}', [BrandController::class, 'destroy'])->name('brands.delete');

        // products routes
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/edit/{productID}', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{productID}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{productID}', [ProductController::class, 'destroy'])->name('products.delete');
        Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');

        // products subcategory routes
        Route::get('/product-subcategories', [ProductSubCategoryController::class, 'index'])->name('product-subcategories.index');

        // Route update product image
        Route::post('/product-images/update', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('/product-images', [ProductImageController::class, 'destroy'])->name('product-images.destroy');

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