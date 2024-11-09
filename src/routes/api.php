<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('users/register', [AuthController::class, 'register']);
Route::post('/admin/register', [AuthController::class, 'adminRegister']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
