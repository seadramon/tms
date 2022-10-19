<?php

use App\Http\Controllers\Api\Customer\PelangganController;
use App\Http\Controllers\Api\Driver\LoginController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\Internal\KalenderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('api.')->namespace('Api')->group(function() {
    Route::get('viewer/{path}', [FileController::class, 'viewer'])->name('file.viewer');

    Route::name('driver.')->prefix('driver')->namespace('Driver')->group(function() {
        Route::post('login', [LoginController::class, 'login'])->name('login');
    });

    Route::name('internal.')->prefix('internal')->namespace('Internal')->group(function() {
        Route::post('kalender-daily', [KalenderController::class, 'daily'])->name('daily');
    });
    
    Route::name('pelanggan.')->prefix('pelanggan')->namespace('Pelanggan')->group(function() {
        Route::get('search', [PelangganController::class, 'search'])->name('search');
    });
});
