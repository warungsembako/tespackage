<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\WebhookController;

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


// Route Authentication
Route::prefix("auth")->group(function () {
    Route::post("/register", [App\Http\Controllers\Api\AuthController::class, "register"]);
    Route::post("/login", [App\Http\Controllers\Api\AuthController::class, "login"]);
});


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('/category', App\Http\Controllers\Api\CategoryController::class);
    Route::resource('/product', App\Http\Controllers\Api\ProductController::class);
    Route::delete('/transactiondelete/{transaction_number}', [App\Http\Controllers\Api\TransactionController::class, 'destroy']);
    Route::resource('/transaction', App\Http\Controllers\Api\TransactionController::class);
    Route::resource('/unit', App\Http\Controllers\Api\UnitController::class);
    Route::post("/auth/logout", [App\Http\Controllers\Api\AuthController::class, "logout"]);
});

Route::post("/webhook", [WebhookController::class,"store"]);
