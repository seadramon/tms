<?php

use App\Http\Controllers\Api\Driver\DriverController;
use App\Http\Controllers\Api\Pelanggan\PelangganController;
use App\Http\Controllers\Api\Driver\LoginController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\General\ProyekController;
use App\Http\Controllers\Api\General\SptbController;
use App\Http\Controllers\Api\Internal\InternalController;
use App\Http\Controllers\Api\Internal\KalenderController;
use App\Http\Controllers\Api\Internal\NppController;
use App\Http\Controllers\Api\Internal\PelangganController as InternalPelangganController;
use App\Http\Controllers\Api\Pelanggan\HistoryController;
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

    Route::name('general.')->prefix('general')->namespace('General')->group(function() {
        Route::get('proyek-list/{kd_pat}', [ProyekController::class, 'proyekList'])->name('proyek-list');
        Route::get('proyek-progress/{no_npp}', [ProyekController::class, 'proyekProgress'])->name('proyek-progress');
        Route::get('proyek-progress-tipe/{no_npp}', [ProyekController::class, 'proyekProgressTipe'])->name('proyek-progress-tipe');
        Route::get('sptb-print/{sptb}', [SptbController::class, 'sptbPrint'])->name('general-sptb-print');
        Route::get('delivery-by-date', [SptbController::class, 'deliveryByDate'])->name('general-delivery-by-date');
    });

    Route::name('driver.')->prefix('driver')->namespace('Driver')->group(function() {
        Route::post('login', [LoginController::class, 'login'])->name('login');
        Route::post('penerimaan', [DriverController::class, 'penerimaan'])->name('penerimaan');
        Route::post('gps-log', [DriverController::class, 'gpsLog'])->name('gps-log');
        Route::get('sptb-detail', [DriverController::class, 'sptbDetail'])->name('sptb-detail');
        Route::get('sptb-list', [DriverController::class, 'sptbList'])->name('sptb-list');
        Route::post('kalender-daily', [DriverController::class, 'daily'])->name('daily');
        Route::post('sptb-daily', [DriverController::class, 'sptbDaily'])->name('sptb-daily');
        Route::get('spm-list', [DriverController::class, 'spmList'])->name('spm-list'); // filter by nopol
    });

    Route::name('internal.')->prefix('internal')->namespace('Internal')->group(function() {
        Route::post('kalender-daily', [KalenderController::class, 'daily'])->name('daily');
        Route::get('npp/{pat}', [InternalPelangganController::class, 'nppList'])->name('npp');
        Route::get('pelanggan-list', [InternalPelangganController::class, 'index'])->name('pelanggan-list');
        Route::post('pelanggan-approve', [InternalPelangganController::class, 'approve'])->name('pelanggan-approve');
        Route::post('gps-tracker', [InternalController::class, 'gpsTracker'])->name('gps-tracker');
        Route::get('spm', [InternalController::class, 'spmList'])->name('spm-list');
        Route::get('sptb', [InternalController::class, 'sptbList'])->name('sptb-list');
    });
    
    Route::name('pelanggan.')->prefix('pelanggan')->namespace('Pelanggan')->group(function() {
        Route::get('search', [PelangganController::class, 'search'])->name('search');
        Route::post('register', [PelangganController::class, 'register'])->name('register');
        Route::post('login', [PelangganController::class, 'login'])->name('login');
        Route::get('produk-detail', [PelangganController::class, 'produkDetail'])->name('produk-detail');
        Route::post('produk-konfirmasi', [PelangganController::class, 'produkKonfirmasi'])->name('produk-konfirmasi');
        Route::get('progress-proyek/{no_hp}', [PelangganController::class, 'progressProyek'])->name('progress-proyek');
        Route::get('total-pengiriman', [PelangganController::class, 'totalPengiriman'])->name('total-pengiriman');

        Route::post('npp-list/{no_hp}', [HistoryController::class, 'nppList'])->name('history-npp-list');
        Route::post('sptb-list-by-npp/{no_npp}', [HistoryController::class, 'sptbListByNpp'])->name('history-sptb-list-by-npp');

        Route::middleware('auth:sanctum')->group(function() {
            Route::post('login1', [PelangganController::class, 'login1'])->name('login1');
            Route::post('kalender-daily', [PelangganController::class, 'daily'])->name('daily');
            Route::post('sptb-daily', [PelangganController::class, 'sptbDaily'])->name('sptb-daily');
        });
    });
});
