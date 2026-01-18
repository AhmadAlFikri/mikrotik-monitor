<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'loginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| DASHBOARD & MONITORING
| Role: admin + administrator
|--------------------------------------------------------------------------
*/
Route::middleware('adminOnly')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/bandwidth-report', [DashboardController::class, 'index']);
    Route::get('/dashboard/bandwidth-report/internet', [DashboardController::class, 'index']);
    Route::get('/dashboard/bandwidth-report/ethernet', [DashboardController::class, 'index']);

    // REALTIME API (AJAX / FETCH)
    Route::get('/api/router/{id}', [DashboardController::class, 'realtime']);

    // ROUTER MANAGEMENT
    Route::get('/router/add', [RouterController::class, 'add']);
    Route::post('/router/store', [RouterController::class, 'store']);

    // REPORT
    Route::get('/report/monthly', [ReportController::class, 'monthly']);
    Route::get('/report/sessions', [ReportController::class, 'sessionLogs']);
});

/*
|--------------------------------------------------------------------------
| ADMIN MANAGEMENT
| Role: administrator ONLY
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
