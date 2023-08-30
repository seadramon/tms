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
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Report\EvaluasiVendorController;
use App\Http\Controllers\Report\MonitoringDistribusiController;
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
        return redirect()->route('dashboard.index');
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
		Route::get('/print/{no_sp3}', [Sp3Controller::class, 'print'])->name('print');
		Route::put('/update/{no_sp3}', [Sp3Controller::class, 'update'])->name('update');
	    Route::resource('/',  Sp3Controller::class)->except([
	        'destroy'
	    ])->parameters(['' => 'sp3']);
	});

	Route::group(['prefix' => '/spp', 'as' => 'spp.'], function(){
	    Route::post('/destroy', [SppController::class, 'destroy'])->name('destroy');
	    Route::post('/draft', [SppController::class, 'createDraft'])->name('draft');

	    Route::get('/data', [SppController::class, 'data'])->name('data');
	    Route::get('/data-spprb', [SppController::class, 'dataSpprb'])->name('data-spprb');
	    Route::get('/data-angkutan', [SppController::class, 'dataAngkutan'])->name('data-angkutan');
		
		Route::get('monitor-approval', [SppController::class, 'monitorApproval'])->name('monitor-approval');
		Route::get('monitor-approval-data', [SppController::class, 'monitorApprovalData'])->name('monitor-approval-data');
		
	    Route::get('/spp-edit/{spp}', [SppController::class, 'edit'])->name('edit');
	    Route::get('/spp-amandemen/{spp}', [SppController::class, 'amandemen'])->name('amandemen');
	    Route::get('/spp-print/{spp}', [SppController::class, 'print'])->name('print');
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
	        'destroy'
	    ])->parameters(['' => 'sptb']);

	    Route::get('/print/{no_sptb}', [SptbController::class, 'print'])->name('print');
		Route::post('/get-spm', [SptbController::class, 'getSpm'])->name('get-spm');
		Route::post('/set-konfirmasi', [SptbController::class, 'setKonfirmasi'])->name('set-konfirmasi');
	    Route::get('/penilaian-mutu/{no_sptb}', [SptbController::class, 'penilaianMutu'])->name('penilaian-mutu');
		Route::post('/penilaian-mutu-simpan', [SptbController::class, 'penilaianMutuSimpan'])->name('penilaian-mutu-simpan');
		Route::post('/penilaian-pelayanan-simpan', [SptbController::class, 'penilaianPelayananSimpan'])->name('penilaian-pelayanan-simpan');
	});

	Route::group(['prefix' => '/pricelist-angkutan', 'as' => 'pricelist-angkutan.'], function(){
		Route::get('/data', [PricelistAngkutanController::class, 'data'])->name('data');

	    Route::resource('/',  PricelistAngkutanController::class)->except([
			'destroy'
		])->parameters(['' => 'pricelist-angkutan']);

		Route::post('/get-lokasi-pemuatan', [PricelistAngkutanController::class, 'getLokasiPemuatan'])->name('get-lokasi-pemuatan');
		Route::post('/upload-excel', [PricelistAngkutanController::class, 'uploadExcel'])->name('upload-excel');
		Route::post('/delete', [PricelistAngkutanController::class, 'delete'])->name('delete');
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
		Route::get('/',	[PdaController::class, 'index'])->name('index');
		Route::get('data',	[PdaController::class, 'data'])->name('data');
		Route::get('/create',	[PdaController::class, 'create'])->name('create');

        Route::get('/{no_npp}/edit/{pat?}',	[PdaController::class, 'edit'])->name('edit');
        Route::post('/store',	[PdaController::class, 'store'])->name('store');
        Route::post('/store-jumlah',	[PdaController::class, 'storeJumlah'])->name('storejumlah');
	});

    Route::group(['prefix' => '/spm', 'as' => 'spm.'], function(){
	    Route::post('/destroy', [SpmController::class, 'destroy'])->name('destroy');
	    Route::post('/draft', [SpmController::class, 'createDraft'])->name('draft');
	    Route::get('/konfirmasi/{spm}', [SpmController::class, 'konfirmasiLink'])->name('konfirmasi-link');
	    Route::post('/konfirmasi', [SpmController::class, 'konfirmasi'])->name('konfirmasi');
		Route::get('/select-pat', [SpmController::class, 'selectPat'])->name('select-pat');
		Route::get('/armada-tiba-validation', [SpmController::class, 'armadaTibaValidation'])->name('armada-tiba-validation');
		Route::post('/armada-tiba', [SpmController::class, 'armadaTiba'])->name('armada-tiba');
	    Route::get('/data', [SpmController::class, 'data'])->name('data');
	    Route::resource('/',  SpmController::class)->except([
	        'destroy'
	    ])->parameters(['' => 'spm']);

		Route::post('/search-pbbmuat', [SpmController::class, 'getPbbMuat'])->name('getPbbMuat');
		Route::post('/get-data-box2', [SpmController::class, 'getDataBox2'])->name('get-data-box2');
        Route::post('/get-jml-segmen', [SpmController::class, 'getJmlSegmen'])->name('get-jml-segmen');
		Route::get('/konfirmasi-vendor/{spm}', [SpmController::class, 'create_konfirmasi_vendor'])->name('create-konfirmasi-vendor');
		Route::post('/store-konfirmasi-vendor', [SpmController::class, 'store_konfirmasi_vendor'])->name('store-konfirmasi-vendor');
		Route::get('/print/{spm}', [SpmController::class, 'print'])->name('print');
        Route::get('/{spm}/edit', [SpmController::class, 'edit'])->name('edit');
        Route::post('/get-data-edit-box2', [SpmController::class, 'getDataEditBox2'])->name('get-data-edit-box2');
        Route::post('/store-edit', [SpmController::class, 'store_edit'])->name('store-edit');
	});

	Route::group(['prefix' => 'kalender-pengirimian', 'as' => 'kalender-pengiriman.'], function() {
		Route::get('/', [KalenderPengirimanController::class, 'index'])->name('index');
		Route::get('spm', [KalenderPengirimanController::class, 'spmData'])->name('spm');
		Route::get('spp', [KalenderPengirimanController::class, 'sppData'])->name('spp');

		Route::get('detail-weekly', [KalenderPengirimanController::class, 'detailWeekly'])->name('detail-weekly');
		Route::post('detail-weekly-data', [KalenderPengirimanController::class, 'detailWeeklyData'])->name('detail-weekly-data');
		Route::get('periode-minggu', [KalenderPengirimanController::class, 'periodeMinggu'])->name('periode-minggu');
	});

	Route::group(['prefix' => 'report-pemenuhan-armada', 'as' => 'report-pemenuhan-armada.'], function(){
		Route::post('/data', [PemenuhanArmadaController::class, 'data'])->name('data');
		Route::post('/chart', [PemenuhanArmadaController::class, 'chart'])->name('chart');
		Route::post('/box-data', [PemenuhanArmadaController::class, 'boxData'])->name('box-data');

	    Route::resource('/',  PemenuhanArmadaController::class)->except([
			'destroy', 'show'
		])->parameters(['' => 'report-pemenuhan-armada']);
	});
	Route::group(['prefix' => 'report-evaluasi-vendor', 'as' => 'report-evaluasi-vendor.'], function(){
		Route::post('/data-sp3', [EvaluasiVendorController::class, 'dataSp3'])->name('data-sp3');
		Route::post('/data-vendor-semester', [EvaluasiVendorController::class, 'dataVendorSemester'])->name('data-vendor-semester');

	    Route::resource('/',  EvaluasiVendorController::class)->except([
			'destroy', 'show'
		])->parameters(['' => 'report-evaluasi-vendor']);
	});
	
	Route::group(['prefix' => 'report-monitoring-distribusi', 'as' => 'report-monitoring-distribusi.'], function(){
		Route::get('/', [MonitoringDistribusiController::class, 'index'])->name('index');
		Route::get('/export-excel/{minggu1}/{minggu2}/{kd_pat}', [MonitoringDistribusiController::class, 'exportExcel'])->name('export-excel');
	});
	
	Route::controller(RoleController::class)->prefix('setting-akses-menu')->name('setting.akses.menu.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/data', 'data')->name('data');
        Route::get('/setting/{id}', 'setting')->name('setting');
        Route::post('/update', 'update_setting')->name('update.setting');
        Route::post('/tree-data', 'tree_data')->name('tree.data');
        Route::get('/delete-setting/{id}', 'delete_setting')->name('delete.setting');
    });

	Route::group(['prefix' => '/dashboard', 'as' => 'dashboard.'], function(){
		Route::get('/', [DashboardController::class, 'index'])->name('index');
	});
});

Route::get('logout',	[LoginVendorController::class, 'signOut'])->name('logout');

// VENDOR
Route::group(['prefix' => '/vendor', 'as' => 'vendor.'], function(){

	Route::get('/login',	[LoginVendorController::class, 'index'])->name('login');
	Route::post('/login',	[LoginVendorController::class, 'postLogin'])->name('post-login');


	Route::middleware('auth')->group(function () {

		Route::get('/testing', function () {
			return view('pages.tms-vendor.home');
		});

		
	});

});
