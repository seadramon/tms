<?php

use App\Http\Controllers\Master\ArmadaController;
use App\Http\Controllers\Master\DriverController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sp3Controller;
use App\Http\Controllers\SpmController;
use App\Http\Controllers\SppController;
use App\Http\Controllers\SppApprovalController;
use App\Http\Controllers\SptbController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KalenderPengirimanController;
use App\Http\Controllers\MasterDriverController;
use App\Http\Controllers\MasterArmadaController;
use App\Http\Controllers\PdaController;
use App\Http\Controllers\PricelistAngkutanController;
use App\Http\Controllers\Report\PemenuhanArmadaController;
use App\Http\Controllers\Verifikasi\ArmadaController as VerifikasiArmadaController;
use App\Http\Controllers\LoginVendorController;
use App\Http\Middleware\EnsureSessionIsValid;

use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([EnsureSessionIsValid::class])->group(function () {
    Route::get('/', function () {
    	// dd(session()->all());
        return view('testing');
    });

	Route::group(['prefix' => '/sp3', 'as' => 'sp3.'], function(){
	    Route::post('/destroy', [Sp3Controller::class, 'destroy'])->name('destroy');
	    Route::post('/data', [Sp3Controller::class, 'data'])->name('data');
		Route::get('/search-npp', [Sp3Controller::class, 'searchNpp'])->name('search-npp');
		Route::get('/search-pic', [Sp3Controller::class, 'searchPic'])->name('search-pic');
		Route::post('/get-data-box2', [Sp3Controller::class, 'getDataBox2'])->name('get-data-box2');
		Route::get('/approve/{type}/{no_sp3}', [Sp3Controller::class, 'showApprove'])->name('get-approve')->where('no_sp3', '(.*)');
		Route::post('/approve', [Sp3Controller::class, 'storeApprove'])->name('store-approve');
		Route::get('/edit/{no_sp3}', [Sp3Controller::class, 'edit'])->name('edit');
		Route::get('/amandemen/{no_sp3}', [Sp3Controller::class, 'edit'])->name('amandemen');
		Route::put('/update/{no_sp3}', [Sp3Controller::class, 'update'])->name('update');
	    Route::resource('/',  Sp3Controller::class)->except([
	        'destroy'
	    ])->parameters(['' => 'sp3']);
	});

	Route::group(['prefix' => '/spp', 'as' => 'spp.'], function(){
	    Route::post('/destroy', [SppController::class, 'destroy'])->name('destroy');
	    Route::post('/draft', [SppController::class, 'createDraft'])->name('draft');
	    Route::get('/data', [SppController::class, 'data'])->name('data');
	    Route::get('/spp-edit/{spp}', [SppController::class, 'edit'])->name('edit');
	    Route::get('/spp-amandemen/{spp}', [SppController::class, 'amandemen'])->name('amandemen');
	    Route::resource('/',  SppController::class)->except([
	        'destroy', 'edit'
	    ])->parameters(['' => 'spp']);
	});

	Route::group(['prefix' => '/spp-approve', 'as' => 'spp-approve.'], function(){
		Route::get('{urutan}/{nosppb}',			[SppApprovalController::class, 'approval'])->name('approval');

		Route::post('/store',	[SppApprovalController::class, 'store'])->name('store');
	});

	Route::group(['prefix' => '/select2', 'as' => 'select2.'], function(){
		Route::get('/spprb',	[SppController::class, 'getSpprb'])->name('spprb');
	});

	Route::group(['prefix' => '/sptb', 'as' => 'sptb.'], function(){
		Route::get('/data', [SptbController::class, 'data'])->name('data');

	    Route::resource('/',  SptbController::class)->except([
	        'show', 'destroy'
	    ])->parameters(['' => 'sptb']);

		Route::post('/get-spm', [SptbController::class, 'getSpm'])->name('get-spm');
	});

	Route::group(['prefix' => '/pricelist-angkutan', 'as' => 'pricelist-angkutan.'], function(){
		Route::get('/data', [PricelistAngkutanController::class, 'data'])->name('data');

	    Route::resource('/',  PricelistAngkutanController::class)->except([
			'destroy'
		])->parameters(['' => 'pricelist-angkutan']);

		Route::post('/get-lokasi-pemuatan', [PricelistAngkutanController::class, 'getLokasiPemuatan'])->name('get-lokasi-pemuatan');
		Route::post('/upload-excel', [PricelistAngkutanController::class, 'uploadExcel'])->name('upload-excel');
	});

	Route::group(['prefix' => '/master-driver', 'as' => 'master-driver.'], function(){
	    Route::post('/destroy', [DriverController::class, 'destroy'])->name('destroy');
	    Route::get('/data', [DriverController::class, 'data'])->name('data');
	    Route::resource('/',  DriverController::class)->except([
	        'show', 'destroy'
	    ])->parameters(['' => 'master-driver']);
	});

	Route::group(['prefix' => '/master-armada', 'as' => 'master-armada.'], function(){
	    Route::post('/destroy', [ArmadaController::class, 'destroy'])->name('destroy');
	    Route::get('/data', [ArmadaController::class, 'data'])->name('data');
	    Route::resource('/',  ArmadaController::class)->except([
	        'show', 'destroy'
	    ])->parameters(['' => 'master-armada']);
	});

	Route::group(['prefix' => 'verivikasi-armada', 'as' => 'verifikasi-armada.'], function(){
		Route::get('verifikasi/{id}', [VerifikasiArmadaController::class, 'verify'])->name('verify');
		Route::post('verifikasi/{id}', [VerifikasiArmadaController::class, 'verified'])->name('verify');
		Route::get('data', [VerifikasiArmadaController::class, 'data'])->name('data');
		Route::resource('/',  VerifikasiArmadaController::class)->only([
			'index'
		])->parameters(['' => 'id']);
	});

	Route::group(['prefix' => 'potensi-detail-armada', 'as' => 'potensi.detail.armada.'], function(){
		Route::get('/create',	[PdaController::class, 'create'])->name('create');
	});

    Route::group(['prefix' => '/spm', 'as' => 'spm.'], function(){
	    Route::post('/destroy', [SpmController::class, 'destroy'])->name('destroy');
	    Route::post('/draft', [SpmController::class, 'createDraft'])->name('draft');
	    Route::post('/konfirmasi', [SpmController::class, 'konfirmasi'])->name('konfirmasi');
	    Route::get('/data', [SpmController::class, 'data'])->name('data');
	    Route::resource('/',  SpmController::class)->except([
	        'destroy', 'show'
	    ])->parameters(['' => 'spm']);

		Route::post('/search-pbbmuat', [SpmController::class, 'getPbbMuat'])->name('getPbbMuat');
		Route::post('/get-data-box2', [SpmController::class, 'getDataBox2'])->name('get-data-box2');
        Route::post('/get-jml-segmen', [SpmController::class, 'getJmlSegmen'])->name('get-jml-segmen');
		Route::get('/konfirmasi-vendor/{spm}', [SpmController::class, 'create_konfirmasi_vendor'])->name('create-konfirmasi-vendor');
		Route::post('/store-konfirmasi-vendor', [SpmController::class, 'store_konfirmasi_vendor'])->name('store-konfirmasi-vendor');
	});

	Route::group(['prefix' => 'kalender-pengirimian', 'as' => 'kalender-pengiriman.'], function() {
		Route::get('/', [KalenderPengirimanController::class, 'index'])->name('index');
		Route::get('spm', [KalenderPengirimanController::class, 'spmData'])->name('spm');
		Route::get('spp', [KalenderPengirimanController::class, 'sppData'])->name('spp');

		Route::get('detail-weekly', [KalenderPengirimanController::class, 'detailWeekly'])->name('detail-weekly');
		Route::post('detail-weekly-data', [KalenderPengirimanController::class, 'detailWeeklyData'])->name('detail-weekly-data');
	});

	Route::group(['prefix' => 'report-pemenuhan-armada', 'as' => 'report-pemenuhan-armada.'], function(){
		Route::post('/data', [PemenuhanArmadaController::class, 'data'])->name('data');
		Route::post('/chart', [PemenuhanArmadaController::class, 'chart'])->name('chart');

	    Route::resource('/',  PemenuhanArmadaController::class)->except([
			'destroy', 'show'
		])->parameters(['' => 'report-pemenuhan-armada']);
	});
});


// VENDOR
Route::group(['prefix' => '/vendor', 'as' => 'vendor.'], function(){

	Route::get('/login',	[LoginVendorController::class, 'index'])->name('login');
	Route::post('/login',	[LoginVendorController::class, 'postLogin'])->name('post-login');

	Route::middleware('auth')->group(function () {

		Route::get('/testing', function () {
			return view('pages.tms-vendor.home');
		});

		Route::get('/logout',	[LoginVendorController::class, 'signOut'])->name('logout');
	});

});
