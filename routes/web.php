<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ImportController;

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
Route::get('/imports/{id}',[ImportController::class,'getDetail']);
Route::post('/imports/create', [ImportController::class, 'create']);


// serach routes
// Route::post('/search/products',[ProductController::class,'search']);