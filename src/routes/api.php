<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InternalBookController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('users/register', [AuthController::class, 'register']);
Route::post('/admin/register', [AuthController::class, 'adminRegister']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/internal-books', [InternalBookController::class, 'internalBookList']);
    Route::post('/internal-books', [InternalBookController::class, 'createInternalBook']);
    Route::get('/internal-books/{book_id}', [InternalBookController::class, 'internalBookItem']);
    Route::delete('/internal-books/{book_id}', [InternalBookController::class, 'deleteIntenalBook']);
});