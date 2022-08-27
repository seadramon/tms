<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sp3Controller;
use App\Http\Controllers\SppController;
use App\Http\Middleware\EnsureSessionIsValid;

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
	});

	Route::group(['prefix' => '/spp', 'as' => 'spp.'], function(){
	    Route::post('/destroy', [SppController::class, 'destroy'])->name('destroy');
	    Route::get('/data', [SppController::class, 'data'])->name('data');
	    Route::resource('/',  SppController::class)->except([
	        'destroy'
	    ])->parameters(['' => 'spp']);
	});
});