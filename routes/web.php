<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\AccountController;
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



// Đăng nhập và đăng ký
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.submit');
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Bảo vệ tất cả các route sau bằng middleware auth
Route::middleware(['auth:account'])->group(function () {

    Route::get('/', function () {
        return view('welcome');
    });
    // product routes
    Route::get('/products', [ProductController::class, 'getAll']);
    Route::get('/product/{id}', [ProductController::class, 'getDetail']);
    Route::post('/products/create', [ProductController::class, 'create']);
    Route::put('/products/update/{id}', [ProductController::class, 'update']);
    Route::delete('/products/delete/{id}', [ProductController::class, 'delete']);
    Route::get('/search/products', [ProductController::class, 'search']);

    // customer routes
    Route::get('/customers', [CustomerController::class, 'getAll']);
    Route::get('/customer/{id}', [CustomerController::class, 'getDetail']);
    Route::get('/search/customers', [CustomerController::class, 'search']);
    Route::delete('/customers/delete/{id}', [CustomerController::class, 'delete']);
    Route::put('/customers/update/{id}', [CustomerController::class, 'update']);
    Route::post('/customers/create', [CustomerController::class, 'create']);

    // import routes
    Route::get('/imports', [ImportController::class, 'getAll']);
    Route::post('/imports/create', [ImportController::class, 'create']);
    Route::get('/imports/delete', [ImportController::class, 'getdelete']);
    Route::get('/imports/p/p', [ImportController::class, 'postman']);
    Route::get('/imports/onlydelete', [ImportController::class, 'getDeleted']);
    Route::delete('/imports/delete/{id}', [ImportController::class, 'delete']);
    Route::get('/imports/{id}', [ImportController::class, 'getDetail']);

    // category routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/create', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/update/{category_id}', [CategoryController::class, 'update']);
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');

    // Routes for managing exports
    Route::get('/exports/search', [ExportController::class, 'search']);
    Route::get('/exports/remove', [ExportController::class, 'removeIndex']);
    Route::get('/exports', [ExportController::class, 'index']);
    Route::post('/exports/create', [ExportController::class, 'store']);
    Route::get('/exports/{id}', [ExportController::class, 'show']);
    Route::delete('/exports/delete/{id}', [ExportController::class, 'destroy']);
    Route::get('/exports/detail/{id}', [ExportController::class, 'detail']);


    // Supplier routes
    Route::get('/suppliers', [SupplierController::class, 'getAll']);
    Route::get('/supplier/{id}', [SupplierController::class, 'getDetail']);
    Route::get('/search/suppliers', [SupplierController::class, 'search']);
    Route::delete('/suppliers/delete/{id}', [SupplierController::class, 'delete']);
    Route::put('/suppliers/update/{id}', [SupplierController::class, 'update']);
    Route::post('/suppliers/create', [SupplierController::class, 'create']);
    Route::get('/suppliers/all', [SupplierController::class, 'getAllSuppliers']);



    // Statistics routes

    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/data', [StatisticsController::class, 'getStatistics'])->name('statistics.data');
    Route::get('/revenue-by-category', [StatisticsController::class, 'revenueByCategory']);

    // Account routes
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/accounts/search', [AccountController::class, 'search'])->name('accounts.search');
    Route::post('/accounts/create', [AccountController::class, 'store'])->name('accounts.store');
    Route::put('/accounts/update/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/delete/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');
});

// serach routes
// Route::post('/search/products',[ProductController::class,'search']);