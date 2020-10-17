<?php

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

Auth::routes();

Route::get('/', 'HomeController@index')->name('index');

/*
GNSS
*/
Route::get('/gnss','GNSSController@index');
Route::get('/get_rnx','GNSSController@get_rnx')->name('get_rnx');
Route::post('/get_rnx','GNSSController@upload')->name('get_rnx');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
|
| Pengaturan untuk kontrol pengguna dan layanan lainnya
|
*/

Route::group(['middleware' => ['auth']], function () {
	// DASHBOARD
    Route::get('/dashboard', 'Dashboard\dashboardController@index')->name('dashboard');

    // USER SETTING
    Route::get('/dashboard/user-setting', 'Dashboard\dashboardController@userSetting')->name('user-setting');
    	// Penambahan pengguna
    	Route::post('/dashboard/user-setting/add-user', 'Dashboard\dashboardController@addUser')->name('add-user');
    	// Hapus pengghuna
    	Route::post('/dashboard/user-setting/delete-user', 'Dashboard\dashboardController@deleteUser')->name('delete-user');

    // ELLIPSOID LIST
    Route::get('/dashboard/ellipsoid-list', 'Dashboard\dashboardController@ellipsoidList')->name('ellipsoid-list');
    	// Penambahan datum
    	Route::post('/dashboard/user-setting/add-ellipsoid', 'Dashboard\dashboardController@addEllipsoid')->name('add-ellipsoid');
    	// Edit datum
    	Route::post('/dashboard/user-setting/edit-ellipsoid', 'Dashboard\dashboardController@editEllipsoid')->name('edit-ellipsoid');
    	// Hapus datum
    	Route::post('/dashboard/user-setting/delete-ellipsoid', 'Dashboard\dashboardController@deleteEllipsoid')->name('delete-ellipsoid');

    // DATUM LIST
    Route::get('/dashboard/datum-list', 'Dashboard\dashboardController@datumList')->name('datum-list');
        // Penambahan datum
        Route::post('/dashboard/user-setting/add-datum', 'Dashboard\dashboardController@addDatum')->name('add-datum');
        // Edit datum
        Route::post('/dashboard/user-setting/edit-datum', 'Dashboard\dashboardController@editDatum')->name('edit-datum');
        // Hapus datum
        Route::post('/dashboard/user-setting/delete-datum', 'Dashboard\dashboardController@deleteDatum')->name('delete-datum');
});
// Email add user activation
Route::get('/account-activation/{code}', 'Dashboard\dashboardController@activation')->name('activation');
// Input pengguna baru
Route::post('/dashboard/user-setting/new-user', 'Dashboard\dashboardController@newUser')->name('new-user');

/*
|--------------------------------------------------------------------------
| Transformasi Koordinat [FREE USER]
|--------------------------------------------------------------------------
*/
Route::get('/apps/transformasi-koordinat-per-titik', 'transformasiController@index')->name('trans-index');
	// detail titik
	Route::get('/apps/transformasi-koordinat-per-titik/detail-titik', 'transformasiController@detailTitik')->name('detail-titik');
	// transformasi geodetik ke geosentrik
	Route::get('/apps/transformasi-koordinat-per-titik/detail-titik/convert', 'transformasiController@convert')->name('convert');

Route::get('/apps/transformasi-koordinat-banyak-titik', 'transformasiController@banyakTitik')->name('trans-index-banyak-titik');
	// detail file
	Route::get('/apps/transformasi-koordinat-banyak-titik/detail-file', 'transformasiController@detailFile')->name('detail-file');
	// eksekusi transformasi banyak titik
	Route::post('/apps/transformasi-koordinat-banyak-titik/exe-trans', 'transformasiController@exeTrans')->name('exe_trans');
	// perhitungan transformasi
	Route::get('/apps/transformasi-koordinat-banyak-titik/calc-trans', 'transformasiController@calcTrans')->name('calc-trans');
	// progresbar untuk transformasi
	Route::get('/apps/transformasi-koordinat-banyak-titik/progress-trans', function () {
	    return response()->json(['value' => session('bar')]);
	})->name('progress-trans');

/*
|--------------------------------------------------------------------------
| GNSS Processing [LIMITED USER]
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth']], function () {
	Route::get('/apps/gnss-processing', 'transformasiController@index')->name('gnss-index');
});

/*
|--------------------------------------------------------------------------
| Geoid Calculator [FREE USER]
|--------------------------------------------------------------------------
*/
Route::get('/apps/geoid-calculator', 'geoidController@index')->name('geoid-index');
	// Calculate geoid per point
	Route::get('/apps/geoid-calculator/geoid-per-point', 'geoidController@geoidPerPoint')->name('geoid-per-point');
	// Calculate geoid in file
	Route::post('/apps/geoid-calculator/geoid-in-file', 'geoidController@geoidInFile')->name('geoid-file');
	// Calculate geoid
	Route::get('/apps/geoid-calculator/calc-geoid', 'geoidController@calcGeoid')->name('calc-geoid');

/*
|--------------------------------------------------------------------------
| Perataan Pengukuran [LIMITED USER]
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth']], function () {

});
Route::get('/apps/perataan', 'adjustmentController@index')->name('adjustment-index');

/*
|--------------------------------------------------------------------------
| Transformasi Datum [LIMITED USER]
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth']], function () {
	Route::get('/apps/transformasi-datum', 'transformasiDatumController@index')->name('datum-trans-index');
	Route::get('/apps/transformasi-datum/{idSurvey}', 'transformasiDatumController@datumCalculation')->name('datum-calculation');
	Route::post('/apps/transformasi-datum/add-survey', 'transformasiDatumController@addSurvey')->name('add-survey');
	Route::post('/apps/transformasi-datum/add-survey/upload-points', 'transformasiDatumController@uploadPoints')->name('upload-datum-points');
	Route::post('/apps/transformasi-datum/survey/calculate-now', 'transformasiDatumController@calculateDatumNow')->name('calculate-datum-now');
	Route::post('/apps/transformasi-datum/submit-file', 'transformasiDatumController@submitFile')->name('trans-datum-submit-file');
});
Route::get('/apps/transformasi-datum/survey/point-use-edit', 'transformasiDatumController@pointUseEdit')->name('point-use-edit');
Route::get('/apps/transformasi-datum/select-method', 'transformasiDatumController@methodSelection')->name('datum-trans-method-selection');
Route::get('/apps/transformasi-datum/calculate', 'transformasiDatumController@calculate')->name('calc-trans-datum');

/*
|--------------------------------------------------------------------------
| Free Tranformation
|--------------------------------------------------------------------------
*/
Route::get('/apps/free-transformation', 'freeTransformationController@index')->name('free-trans-index');

Route::get('/migration', 'HomeController@migration')->name('migration');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
