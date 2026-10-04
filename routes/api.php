<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\DashboardController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // --- Rute yang bisa diakses VIEWER & ADMIN (Hanya Baca / Read) ---
    Route::get('departments', [DepartmentController::class, 'index']);
    Route::get('departments/{department}', [DepartmentController::class, 'show']);

    Route::get('folders', [FolderController::class, 'index']);
    Route::get('folders/{folder}', [FolderController::class, 'show']);

    Route::get('files/{file}/download', [FileController::class, 'download']);
    Route::get('files', [FileController::class, 'index']);
    Route::get('files/{file}', [FileController::class, 'show']);


    // --- Rute yang HANYA BISA DIAKSES ADMIN (Create, Update, Delete) ---
    Route::middleware('admin')->group(function () {
        // Departemen CRUD
        Route::post('departments', [DepartmentController::class, 'store']);
        Route::put('departments/{department}', [DepartmentController::class, 'update']);
        Route::delete('departments/{department}', [DepartmentController::class, 'destroy']);

        // Folder CRUD
        Route::post('folders', [FolderController::class, 'store']);
        Route::put('folders/{folder}', [FolderController::class, 'update']);
        Route::delete('folders/{folder}', [FolderController::class, 'destroy']);

        // File CRUD (Upload, Edit, Hapus)
        Route::post('files', [FileController::class, 'store']);
        Route::put('files/{file}', [FileController::class, 'update']);
        Route::delete('files/{file}', [FileController::class, 'destroy']);
    });
});
