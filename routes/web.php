<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\DocumentController;

// Health check (no auth required)
Route::get('/health', function () {
    return response('ok', 200)->header('Content-Type', 'text/plain');
});

// Authentication routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    
    // Redirect root to appropriate dashboard
    Route::get('/', function() {
        if (auth()->user()->hasRole('Driver')) {
            return redirect('/driver');
        }
        return redirect('/loads');
    });

    // Load management (Admin & Dispatcher)
    Route::middleware('role:Admin|Dispatcher')->group(function () {
        Route::get('/loads', [LoadController::class, 'index'])->name('loads.index');
        Route::get('/loads/create', [LoadController::class, 'create'])->name('loads.create');
        Route::post('/loads', [LoadController::class, 'store'])->name('loads.store');
        Route::put('/loads/{load}/assign', [LoadController::class, 'assign'])->name('loads.assign');
        Route::put('/loads/{load}/status', [LoadController::class, 'updateStatus'])->name('loads.updateStatus');
    });

    // Load detail (all authenticated users)
    Route::get('/loads/{load}', [LoadController::class, 'show'])->name('loads.show');

    // Driver routes
    Route::middleware('role:Driver')->group(function () {
        Route::get('/driver', [DriverController::class, 'dashboard'])->name('driver.dashboard');
        Route::post('/driver/status', [DriverController::class, 'updateStatus'])->name('driver.updateStatus');
        
        // Rate-limited location endpoint (60 requests per minute)
        Route::post('/driver/location', [DriverController::class, 'storeLocation'])
            ->middleware('throttle:60,1')
            ->name('driver.storeLocation');
    });

    // Dispatch map (Admin & Dispatcher)
    Route::middleware('role:Admin|Dispatcher')->group(function () {
        Route::get('/dispatch/map', [DispatchController::class, 'map'])->name('dispatch.map');
        Route::get('/api/driver-locations', [DispatchController::class, 'getDriverLocations'])->name('api.driver-locations');
    });

    // Documents
    Route::post('/loads/{load}/documents', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
});
