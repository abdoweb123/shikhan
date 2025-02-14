<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
//
// // Route::middleware('auth:api')->get('/user', 'courses@complete_lesson');
//
// Route::group([
//     // 'namespace' => 'Api',
//     'middleware' => ['set.local.api'],
//     'prefix' => 'v1',
// ], function ($router) {
//
//     Route::post('/register', 'UserController@register');
//     Route::post('/login', 'UserController@login');
//     Route::post('/logout', 'UserController@logout');
//     Route::post('/forgot_password', 'UserController@forgotPassworod');
//     Route::post('/change_password', 'UserController@changePassworod');
//     Route::post('/resend_code', 'VerificationController@createVerificationCode')->middleware('throttle:4,1');
//     Route::post('check_verification_code', 'VerificationController@checkVerificationCode');
//     Route::post('/check_verification_code_password', 'VerificationController@checkVerificationCodePassword');
//     Route::post('/check_user', 'UserController@check_user');
//
//     Route::get('/shared_data', 'SharedController@index');
//
//     // Route::get('contactus_types', 'ContactUsController@getContactUsTypes'); // all
//     // Route::post('contactus', 'ContactUsController@store'); // all
//     // Route::get('settings/all', 'SettingController@index'); // secure property ???????
//     // Route::get('settings/{property}', 'SettingController@show'); // secure property ???????
//     // Route::get('how_to_use/{type}', 'SettingController@how_to_use'); // secure property ???????
//     // Route::get('faqs', 'FaqController@index');
//
//     Route::group([
//         'prefix' => 'category',
//         ], function ($router) {
//             Route::get('/all', 'CategoryController@index');
//             Route::get('/chlids/{id}', 'CategoryController@showWithChilds');
//             Route::get('/items/all/{id}', 'CategoryController@allItems');
//             Route::get('/items/child/{id}', 'CategoryController@childItems');
//
//     });
//
//     Route::middleware('auth:api')->group(function () {
//         Route::get('/users', 'UserController@show'); // where id []
//         Route::DELETE('items/{id}/delete_file/{file_id}', 'ItemController@destroyFile')->name('front.items.destroy_file')->where(['id', '[0-9]+', 'file_id', '[0-9]+']);
//
//         Route::group([
//             'prefix' => 'users',
//         ], function ($router) {
//             // Route::get('{id}', 'UserController@show')->name('user.show')->where('id', '[0-9]+');
//             Route::get('{id}/profile', 'UserController@edit')->where('id', '[0-9]+');
//             Route::put('{id}', 'UserController@update')->where('id', '[0-9]+');
//
//             Route::post('{id}/image', 'UserController@updateImage')->where('id', '[0-9]+');
//             Route::put('{id}/fcm', 'UserController@updateFcm')->where('id', '[0-9]+'); // all
//             Route::get('{id}/notifications', 'NotificationController@getNotificationByUserId')->where('id', '[0-9]+');
//             Route::post('notifications/send', 'NotificationController@send_notification')->where('id', '[0-9]+');
//             Route::post('notifications/send/orders', 'NotificationController@send_notification_orders')->where('id', '[0-9]+');
//         });
//     });
//
// });
