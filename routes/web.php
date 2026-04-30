<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\LogController;

// Login & Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root to dashboard or login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produk (Products)
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // Stok Warehouse (Stock)
    Route::get('/stock-warehouse', [StockController::class, 'warehouse'])->name('stock-warehouse');
    Route::get('/stock/by-location', [StockController::class, 'byLocation'])->name('stock.by-location');
    Route::get('/stock/export', [StockController::class, 'export'])->name('stock.export');

    // Pergerakan Barang (Movements)
    Route::get('/movement-items', [MovementController::class, 'index'])->name('movement-items');
    Route::get('/movement/statistics', [MovementController::class, 'statistics'])->name('movement.statistics');

    // Backup Data
    Route::get('/backup-data', [BackupController::class, 'index'])->name('backup-data');
    Route::post('/backup-data/backup', [BackupController::class, 'backup'])->name('backup.execute');
    Route::get('/backup-data/download/{id}', [BackupController::class, 'download'])->name('backup.download');

    // Log Backup
    Route::get('/backup-logs', [LogController::class, 'index'])->name('backup-logs');
    Route::get('/backup-logs/{id}', [LogController::class, 'show'])->name('backup-logs.show');
    Route::get('/backup-logs/api/list', [LogController::class, 'list'])->name('backup-logs.list');
});

// Legacy routes (keep for backward compatibility)
Route::post('/backup', [BackupController::class, 'backup']);
Route::get('/test-sql-server', function () {
    return 'SQL Server connection test';
});