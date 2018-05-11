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

Route::post('/sensor/identificate', 'SensorsController@identificate');
Route::post('/temperature/post', 'TemperatureController@create');
Route::post('/temperature/dev_post', 'TemperatureController@dev_create');
Route::get('/cleanup', 'CleanupController@cleanup');

Route::group(['middleware' => ['auth']], function() {

	Route::get('/', 'HomeController@index')->name('home');
	Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/user', 'UserController@edit')->name('user');
	Route::patch('/user', 'UserController@update');

	Route::get('/sensors', 'SensorsController@index')->name('sensors');
	Route::get('/sensors/active', 'SensorsController@indexActive')->name('sensorsActive');
	Route::get('/sensors/inactive', 'SensorsController@indexInactive')->name('sensorsInactive');
	Route::get('/sensors/notconfirmed', 'SensorsController@indexNotConfirmed')->name('sensorsNotConfirmed');
	Route::get('/sensors/exceeded', 'SensorsController@indexExceeded')->name('sensorsExceeded');


	Route::get('/sensors/register', 'SensorsController@create')->name('sensor.register');
	Route::post('/sensors/register', 'SensorsController@store');

	Route::get('/sensors/{sensor}', 'SensorsController@show');
	Route::patch('/sensors/{sensor}', 'SensorsController@update');
	Route::delete('/sensors/{sensor}', 'SensorsController@delete');

	Route::get('/sensors/{sensor}/edit', 'SensorsController@edit');
	Route::get('/sensors/{sensor}/confirm', 'SensorsController@confirm');
	Route::get('/sensors/{sensor}/qrcode', 'SensorsController@qrcode');

	Route::post('/sensors/{sensor}/json', 'SensorsController@json');

});

Auth::routes();
