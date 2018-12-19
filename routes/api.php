<?php

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

Route::get('login', ['as' => 'login', 'uses' => 'UserController@login']);
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/my-bookings', 'UserController@getBookings');
    Route::resource('/booking', 'BookingController');
    Route::get('/booking/by-date/{date}', 'BookingController@getBookingsOfDay');
    Route::resource('/room', 'MeetingRoomController');
});
