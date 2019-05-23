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

Route::get('/', function () {
    return view('auth/login');
})->middleware('guest');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function(){
    Route::get('/users/add', 'UserController@create')->name('user.add');
    Route::post('/users/activate', 'UserController@is_active')->name('user.active');
    Route::get('/users', 'UserController@index')->name('user.index');
    Route::get('/clients', 'ClientController@index')->name('clients');
    Route::get('/client/{id}', 'ClientController@view')->name('client');
    
    Route::get('/attendance', 'AttendanceController@view')->name('view.attendance');
    Route::get('/guards', 'GuardController@index')->name('guards');
    Route::get('/guards/add', 'GuardController@create')->name('guard.add');
    Route::get('/guards/reports', 'GuardController@reports')->name('guard.update');
    Route::get('/guard/{id}', 'GuardController@view')->name('guard.view');
    Route::get('/roster/{id}', 'DutyRosterController@view')->name('roster.view');
    Route::get('/offences', 'DeductionController@create')->name('offences');
    Route::get('/offence-types', 'DeductionController@index')->name('offence-types');
    Route::get('/permissions', 'PermissionController@index')->name('permissions');
    Route::get('/send-report', 'ReportController@send')->name('report.send');
    Route::get('/view-deductions', 'DeductionController@guardDeductions')->name('offences.view');
    Route::get('/view-reports', 'ReportController@index')->name('reports.view');
    Route::get('/client-access', 'ClientController@clientAccess')->name('client-access.view');
    Route::get('/upload', 'GuardController@uploadExcel')->name('guard.upload');
    Route::get('/biometrics', 'GuardController@uploadBios')->name('guard.bios');
    Route::get('/add-guarantors', 'GuardController@addGuarantors')->name('guard.add-guarantors');
});
