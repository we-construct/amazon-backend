<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
Auth::routes(['verify' => true]);
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/confirmLogin/{id}', [AuthController::class, 'confirmEmail']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])  ;
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
});

Route::prefix('/auth/user-profile')->middleware('api')->group(function () {
    Route::get('/', [AuthController::class, 'userProfile']);
    Route::get('/my-address',[UserController::class,'getAddresses']);
    Route::post('/my-address/add',[AddressController::class,'addAddress']);
    Route::delete('/my-address/delete/{id}',[AddressController::class,'deleteAddress']);
    Route::put('/my-address/update/{id}',[AddressController::class,'updateAddress']);
});
Route::prefix('/auth/shop')->middleware('api')->group(function () {
//    Route::get('/', [ProductController::class, 'userProfile']);
    Route::post('/add',[ProductController::class,'addProduct']);
});
