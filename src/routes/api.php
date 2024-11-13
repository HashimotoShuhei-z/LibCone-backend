<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InternalBookController;
use Illuminate\Support\Facades\Route;

// 認証関連のエンドポイント
Route::post('/login', [AuthController::class, 'login']);
Route::post('users/register', [AuthController::class, 'register']);
Route::post('/admin/register', [AuthController::class, 'adminRegister']);

// 管理者、一般ユーザー共通のエンドポイント
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/internal-books', [InternalBookController::class, 'internalBookList']);
    Route::get('/internal-books/{company_book}', [InternalBookController::class, 'internalBookItem']);
});

// 管理者のみ叩けるエンドポイント
Route::middleware('auth:sanctum', 'abilities:admin')->group(function () {
    Route::post('/internal-books', [InternalBookController::class, 'createInternalBook']);
    Route::delete('/internal-books/{company_book}', [InternalBookController::class, 'deleteIntenalBook']);
});

// 一般ユーザー(社員)のみが叩けるエンドポイント
Route::middleware('auth:sanctum', 'abilities:user')->group(function () {
    // 
});
