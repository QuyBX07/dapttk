<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ImportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//product routes
Route::post('/products/create', [ProductController::class, 'create']);
Route::post('/products/update/{id}', [ProductController::class, 'update']);
Route::delete('/products/delete/{id}', [ProductController::class, 'delete']);


//customer routes
Route::post('/customers/create', [CustomerController::class, 'create']);
Route::put('/customers/update/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/delete/{id}', [CustomerController::class, 'delete']);



//import routes
Route::post('/imports/create', [ImportController::class, 'create']);
Route::delete('/imports/delete/{id}', [ImportController::class, 'delete']);
Route::get('/imports', [ImportController::class, 'getAll']);


// search routes
Route::post('/search/products',[ProductController::class,'search']);
Route::post('/search/customers',[CustomerController::class,'search']);
