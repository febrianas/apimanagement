<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/page/{page}', [OrderController::class, 'pagination']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::get('orders/search/{keyword}', [OrderController::class, 'search']);
    Route::post('orders/create', [OrderController::class, 'store']);
    Route::put('orders/update/{order}',  [OrderController::class, 'update']);
    Route::delete('orders/delete/{order}',  [OrderController::class, 'destroy']);

    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/page/{page}', [CustomerController::class, 'pagination']);
    Route::get('customers/{id}', [CustomerController::class, 'show']);
    Route::get('customers/search/{keyword}', [CustomerController::class, 'search']);
    Route::post('customers/create', [CustomerController::class, 'store']);
    Route::put('customers/update/{customer}',  [CustomerController::class, 'update']);
    Route::delete('customers/delete/{customer}',  [CustomerController::class, 'destroy']);
});
