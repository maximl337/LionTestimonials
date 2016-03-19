<?php


Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => 'web'], function () {
    
    Route::auth();

    Route::get('/home', 'HomeController@index');

    Route::get('/profile', 'UserController@getProfile');

    Route::get('/profile/edit', 'UserController@editProfile');

    Route::post('/user', 'UserController@update');

    Route::get('/user/verify/{token}', 'UserController@verify');

});
