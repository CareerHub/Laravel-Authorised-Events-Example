<?php
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', "AuthController@login");
    Route::get('login', "AuthController@login");
    Route::post('login', "AuthController@dologin");
    Route::get('register', "AuthController@register");
    Route::post('register', "AuthController@doRegister");
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('events', "EventApiController@index");
	Route::get('events/{id}', "EventApiController@detail");
    Route::get('callback', "EventApiController@callback")->name("callback");
	Route::post('logout', 'AuthController@logout');
});
