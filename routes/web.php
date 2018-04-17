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
    return '뿌잉';
});

Route::group(['namespace'=>'Seoul'],
function(){
	Route::get("/seoul/list/{arsId}", 'StationController@getStationBusList');		
	Route::get("/seoul/{station_name}", 'StationController@getStationInfo');		
	Route::get("/seoul/{arsId}/{bus}", 'StationController@getStationArrivalInfo');		

});