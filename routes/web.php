<?php

use App\Http\Controllers\Master\ArmadaController;
use App\Http\Controllers\Master\DriverController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sp3Controller;
use App\Http\Controllers\SpmController;
use App\Http\Controllers\SppController;
use App\Http\Controllers\SppApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterDriverController;
use App\Http\Controllers\MasterArmadaController;
use App\Http\Controllers\PdaController;
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
	    Route::get('/data', [Sp3Controller::class, 'data'])->name('data');
	    Route::resource('/',  Sp3Controller::class)->except([
	        'show', 'destroy'
	    ])->parameters(['' => 'sp3']);
		Route::get('/search-npp', [Sp3Controller::class, 'searchNpp'])->name('search-npp');
		Route::get('/search-pic', [Sp3Controller::class, 'searchPic'])->name('search-pic');
		Route::post('/get-data-box2', [Sp3Controller::class, 'getDataBox2'])->name('get-data-box2');
		Route::get('/approve/{type}/{no_sp3}', [Sp3Controller::class, 'showApprove'])->name('get-approve')->where('no_sp3', '(.*)');
		Route::post('/approve', [Sp3Controller::class, 'storeApprove'])->name('store-approve');
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
	    Route::get('/data', [SpmController::class, 'data'])->name('data');
	    Route::resource('/',  SpmController::class)->except([
	        'destroy'
	    ])->parameters(['' => 'spm']);

		Route::post('/search-pbbmuat', [SpmController::class, 'getPbbMuat'])->name('getPbbMuat');
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
