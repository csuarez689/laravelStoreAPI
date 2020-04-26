<?php

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

// Users
Route::apiResource('users', 'API\UserController');

// Buyers
Route::apiResource('buyers', 'API\BuyerController')->only(['index', 'show']);

// Sellers
Route::apiResource('sellers', 'API\SellerController')->only(['index', 'show']);

// Categories
Route::apiResource('categories', 'API\CategoryController');

//Products
Route::apiResource('products', 'API\ProductController')->only(['index', 'show']);

//Transactions
Route::apiResource('transactions', 'API\TransactionController')->only(['index', 'show']);
