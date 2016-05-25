<?php
if (App::environment('staging') || App::environment('production')) {

    Log::useFiles('php://stderr');
}

Route::get('/', 'HomeController@welcome');

Route::get('/video-test-2', function() {
    return view('video-test-2');
});


Route::group(['middleware' => 'web'], function () {
    
    Route::auth();

    Route::get('home', 'HomeController@index');

    Route::get('profile', 'UserController@getProfile');

    Route::get('profile/edit', 'UserController@editProfile');

    Route::get('users/{id}/public', 'UserController@showPublic');

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

    Route::get('contacts/{id}/external/email', 'ContactController@externalLinksEmailPreview');

    Route::post('contacts/external/email/send', 'ContactController@sendExternalLinksEmail');

    Route::get('contacts/{id}', 'ContactController@edit');

    Route::post('contacts/{id}', 'ContactController@update');

    Route::delete('contacts/{id}', 'ContactController@destroy');

    /** Testimonials */
    Route::get('testimonials/create', 'TestimonialController@create');

    Route::get('testimonials/thankyou', 'TestimonialController@thankyou');

    Route::post('testimonials', 'TestimonialController@store');

    Route::get('testimonials', 'TestimonialController@getTestimonials');

    Route::post('testimonials/approve', 'TestimonialController@approve');

    Route::get('testimonials/{id}', 'TestimonialController@getTestimonial');

    Route::get('video/{id}', 'TestimonialController@showTestimonialVideo');

    Route::get('users/{id}/testimonials/public', 'TestimonialController@publicTestimonials');

    

    /** External Link */

    Route::post('externalLinks/{id}', 'ExternalLinksController@update');

    Route::resource('externalLinks', 'ExternalLinksController');
    
    /** Support articles */
    Route::post('support/{id}', 'SupportArticleController@update');
    
    Route::resource('support', 'SupportArticleController');

    /** Videos */
    Route::get('videos/{id}/email', 'VideoController@videoByEmailTemplate');

    Route::post('videos/send/email', 'VideoController@sendByEmail');

    Route::resource('videos', 'VideoController');

    /** Subscription */
    Route::get('billing', 'SubscriptionController@index');

    Route::post('billing', 'SubscriptionController@subscribe');

    Route::post('subscription/resume', 'SubscriptionController@resume');

    Route::post('subscription/cancel', 'SubscriptionController@cancel');

    Route::post(
        'stripe/webhook',
        '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
    );

    
});
