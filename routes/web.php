<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.submit');
Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');


//product routes
Route::get('/products',[ProductController::class,'getAll']);
Route::get('/product/{id}',[ProductController::class,'getDetail']);
Route::post('/products/create', [ProductController::class, 'create']);
Route::put('/products/update/{id}', [ProductController::class, 'update']);
Route::delete('/products/delete/{id}', [ProductController::class, 'delete']);
Route::get('/search/products',[ProductController::class,'search']);


//customer routes
Route::get('/customers',[CustomerController::class,'getAll']);
Route::get('/customer/{id}',[CustomerController::class,'getDetail']);
Route::get('/search/customers',[CustomerController::class,'search']);
Route::delete('/customers/delete/{id}', [CustomerController::class, 'delete']);
Route::put('/customers/update/{id}', [CustomerController::class, 'update']);
Route::post('/customers/create', [CustomerController::class, 'create']);



//import routes
Route::get('/imports',[ImportController::class,'getAll']);
Route::get('/imports/p/p',[ImportController::class,'postman']);
Route::delete('/imports/delete/{id}', [ImportController::class, 'delete']);

Route::get('/imports/{id}',[ImportController::class,'getDetail']);
Route::post('/imports/create', [ImportController::class, 'create']);

//category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories/create', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categories/update/{category_id}', [CategoryController::class, 'update']);
Route::delete('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');

// Routes for managing exports
Route::get('/exports', [ExportController::class, 'index']); // Lấy danh sách xuất hàng
Route::post('/exports/create', [ExportController::class, 'store']);
Route::get('/exports/{id}', [ExportController::class, 'show']); // AJAX detail
Route::put('/exports/update/{id}', [ExportController::class, 'update']);
Route::delete('/exports/delete/{id}', [ExportController::class, 'destroy']);



// serach routes
// Route::post('/search/products',[ProductController::class,'search']);