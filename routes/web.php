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



//customer routes
Route::get('/customers',[CustomerController::class,'getAll']);
Route::get('/customer/{id}',[CustomerController::class,'getDetail']);



//import routes
Route::get('/imports',[ImportController::class,'getAll']);
Route::get('/import/{id}',[ImportController::class,'getDetail']);


// serach routes
// Route::post('/search/products',[ProductController::class,'search']);