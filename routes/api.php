<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace'=>'Seoul'],
    function() {
        Route::get("/seoul/list/{arsId}", 'StationController@getStationBusList');
        Route::get("/seoul/{station_name}", 'StationController@getStationInfo');
        Route::get("/seoul/{arsId}/{bus}", 'StationController@getArrivalInfo');
});

Route::post('/alarm','AlarmController@store');
Route::post('/update','AlarmController@update');
Route::delete('/destroy/{id}','AlarmController@destroy');
Route::get('/alarm/{device_id}/{id}','AlarmController@getAlarm');
Route::get('/list/{device_id}','AlarmController@getList');