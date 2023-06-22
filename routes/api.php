<?php

use App\Http\Controllers\Product\ProductCategory;
use App\Http\Controllers\Product\Product;
use App\Http\Controllers\User\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// User Auth
Route::group(['prefix' => 'user'], function(){
  Route::post('register', [Auth::class, 'register']);
  Route::post('login', [Auth::class, 'login']);
  Route::get('logout', [Auth::class, 'logout'])->middleware('checkUser');
});

// Product Category
Route::group(['prefix' => 'product-category', 'middleware' => ['checkUser']], function(){
  Route::get('', [ProductCategory::class, 'index']);
  Route::get('{id}', [ProductCategory::class, 'detail']);
  Route::post('', [ProductCategory::class, 'insert']);
  Route::put('{id}', [ProductCategory::class, 'update']);
  Route::delete('{id}', [ProductCategory::class, 'delete']);
});

// Product
Route::group(['prefix' => 'product', 'middleware' => ['checkUser']], function(){
  Route::get('', [Product::class, 'index']);
  Route::get('{id}', [Product::class, 'detail']);
  Route::get('{id}/pay', [Product::class, 'paymentGateway']);
  Route::post('', [Product::class, 'insert']);
  Route::put('{id}', [Product::class, 'update']);
  Route::delete('{id}', [Product::class, 'delete']);
});