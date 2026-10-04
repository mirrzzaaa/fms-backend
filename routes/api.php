<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\DashboardController;

// Public Route: Login
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Butuh Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth Profile & Logout
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard Statistics (Admin & Viewer)
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Departments (Index & Show bisa untuk semua, Store/Update/Destroy idealnya dibatasi Admin)
    Route::apiResource('departments', DepartmentController::class);

    // Folders API
    Route::apiResource('folders', FolderController::class);

    // Files API & Custom Download
    Route::get('files/download/{file}', [FileController::class, 'download']);
    Route::apiResource('files', FileController::class);
});