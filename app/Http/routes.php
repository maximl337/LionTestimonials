<?php
if (App::environment('staging') || App::environment('production')) {

    Log::useFiles('php://stderr');
}

Route::get('/', 'HomeController@welcome');

Route::get('/video-test', function() {
    return view('video-test');
});


Route::group(['middleware' => 'web'], function () {
    
    Route::auth();

    Route::get('home', 'HomeController@index');

    Route::get('profile', 'UserController@getProfile');

    Route::get('profile/edit', 'UserController@editProfile');

    Route::post('user', 'UserController@update');

    Route::get('user/verify/{token}', 'UserController@verify');

    /** Contacts */
    Route::get('contacts', 'ContactController@index');

    Route::get('contacts/create', 'ContactController@create');

    Route::get('contacts/import', 'ContactController@import');

    Route::post('contacts/create', 'ContactController@store');

    Route::post('contacts/import', 'ContactController@importCsv');

    Route::get('contacts/{id}/email', 'ContactController@emailPreview');

    Route::get('contacts/{id}/sms', 'ContactController@smsPreview');

    Route::post('contacts/email/send', 'ContactController@sendEmail');

    Route::get('contacts/email/self', 'ContactController@sendEmailSelf');

    Route::post('contacts/sms/send', 'ContactController@sendSMS');

    /** Testimonials */
    Route::get('testimonials/create', 'TestimonialController@create');

    Route::post('testimonials', 'TestimonialController@store');

    Route::get('testimonials', 'TestimonialController@getTestimonials');

    Route::post('testimonials/approve', 'TestimonialController@approve');

    Route::get('testimonials/{id}', 'TestimonialController@getTestimonial');

    Route::post('testimonials/desktop', 'TestimonialController@storeFromDesktop');

    Route::post('testimonials/phone', 'TestimonialController@storeFromPhone');

    Route::get('video/{id}', 'TestimonialController@showTestimonialVideo');

    Route::get('users/{id}/testimonials/public', 'TestimonialController@publicTestimonials');
});
