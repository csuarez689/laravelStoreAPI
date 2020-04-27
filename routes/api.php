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
Route::apiResource('users', 'User\UserController');

// Buyers
Route::apiResource('buyers', 'Buyer\BuyerController')->only(['index', 'show']);
Route::apiResource('buyers.transactions', 'Buyer\BuyerTransactionController')->only(['index']);
Route::apiResource('buyers.products', 'Buyer\BuyerProductController')->only(['index']);
Route::apiResource('buyers.sellers', 'Buyer\BuyerSellerController')->only(['index']);
Route::apiResource('buyers.categories', 'Buyer\BuyerCategoryController')->only(['index']);

// Sellers
Route::apiResource('sellers', 'Seller\SellerController')->only(['index', 'show']);
Route::apiResource('sellers.transactions', 'Seller\SellerTransactionController')->only(['index']);
Route::apiResource('sellers.categories', 'Seller\SellerCategoryController')->only(['index']);

// Categories
Route::apiResource('categories', 'Category\CategoryController');
Route::apiResource('categories.products', 'Category\CategoryProductController')->only(['index']);
Route::apiResource('categories.sellers', 'Category\CategorySellerController')->only(['index']);
Route::apiResource('categories.transactions', 'Category\CategoryTransactionController')->only(['index']);
Route::apiResource('categories.buyers', 'Category\CategoryBuyerController')->only(['index']);

//Products
Route::apiResource('products', 'Product\ProductController')->only(['index', 'show']);

//Transactions
Route::apiResource('transactions', 'Transaction\TransactionController')->only(['index', 'show']);
Route::apiResource('transactions.categories', 'Transaction\TransactionCategoryController')->only(['index']);
Route::apiResource('transactions.sellers', 'Transaction\TransactionSellerController')->only(['index']);
