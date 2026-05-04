<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth routes
// Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
// Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [App\Http\Controllers\Api\AuthController::class, 'profile']);
});

// Role routes
Route::post('/roles/insert', [App\Http\Controllers\Enum\RoleController::class, 'insert']);

// User routes
Route::get('/users', [App\Http\Controllers\admin\UserController::class, 'index']);

Route::post('midtrans/callback', [App\Http\Controllers\User\PaymentController::class, 'callback'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);