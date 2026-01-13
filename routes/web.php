<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));
Route::get('/login', [AuthController::class, 'loginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| DASHBOARD (ADMIN & ADMINISTRATOR)
|--------------------------------------------------------------------------
*/
Route::middleware('adminOnly')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/api/router/{id}', [DashboardController::class, 'realtime']);

    // ROUTER
    Route::get('/router/add', [RouterController::class, 'add']);
    Route::post('/router/store', [RouterController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| ADMIN MANAGEMENT (ADMINISTRATOR ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('administratorOnly')->group(function () {

    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/create', [AdminController::class, 'create']);
    Route::post('/admin', [AdminController::class, 'store']);

    Route::get('/admin/{id}/edit', [AdminController::class, 'edit']);
    Route::put('/admin/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/{id}', [AdminController::class, 'destroy']);
});
