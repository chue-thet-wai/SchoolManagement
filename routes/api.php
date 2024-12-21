<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->group(function () {
    Route::group(['namespace' => 'App\Http\Controllers\API'], function () {
        Route::get('/home', 'HomeController@home');
        Route::post('/edit_profile', 'HomeController@editProfile'); 
        Route::post('/change_password', 'HomeController@changePassword'); 
        Route::get('/contacts', 'HomeController@contactList'); 
        Route::get('/annoucement', 'AnnoucementController@annoucementList');
        Route::get('/event', 'HomeController@eventList');

        Route::post('/student_examdate', 'StudentProfileController@examDate');
        Route::post('/student_homework', 'StudentProfileController@homework');
        Route::post('/save_homework_comment', 'StudentProfileController@saveHomeworkComment');

        Route::post('/student_message', 'StudentProfileController@message');
        Route::post('/student_event', 'StudentProfileController@event'); 
        Route::post('/student_billing', 'StudentProfileController@billing');

        Route::post('student_curriculum', 'StudentProfileController@curriculum');
        Route::post('student_examterms', 'StudentProfileController@examTerms');
        Route::post('student_examterms_result', 'StudentProfileController@examTermsResult');
        Route::post('student_attendance', 'StudentProfileController@attendances');
        Route::post('student_leaverequest', 'StudentProfileController@leaveRequest'); 
        
        Route::post('student_progress', 'StudentProfileController@progress'); 
        Route::post('student_specialrequest', 'StudentProfileController@specialRequest'); 
        Route::post('save_specialrequest', 'StudentProfileController@saveSpecialRequest'); 
        Route::post('save_specialrequest_comment', 'StudentProfileController@saveSpecialRequestComment'); 
        Route::post('student_health', 'StudentProfileController@health');
        Route::post('student_news', 'StudentProfileController@news'); 
        Route::post('save_news_comment', 'StudentProfileController@saveNewsComment'); 
        Route::post('student_certificate', 'StudentProfileController@certificates'); 
        Route::post('student_ferry_tracking', 'StudentProfileController@studentFerryTracking'); 

        Route::post('check_new_specialrequest', 'StudentProfileController@checkNewSpecialRequest'); 
        Route::post('check_new_specialrequest_byrole', 'StudentProfileController@checkNewSpecialRequestRole'); 
        Route::post('save_specialrequest_last_readat', 'StudentProfileController@saveSpecialRequestLastReadAt');

        Route::post('pocket_money_request','StudentProfileController@pocketMoneyRequest');
        Route::post('pocket_money_save','StudentProfileController@pocketMoneySave');
        Route::post('bill_payment','StudentProfileController@billPayment');

        Route::get('/chat_list', 'ChatController@getChatList');
        Route::post('/send_chat_message', 'ChatController@sendMessage');
        Route::get('/get_chat_messages', 'ChatController@getMessages');
        Route::post('/save_last_readat', 'ChatController@saveLastReadAt');

        Route::post('/save_device', 'NotificationController@saveDevice');
        Route::post('/send_noti', 'NotificationController@sendNoti');

        //for notification
        Route::post('/notifications', 'NotificationController@listNotification');
        Route::post('/notifications/read', 'NotificationController@readNotification');
        Route::post('/notifications/unread', 'NotificationController@unreadNotification');
        Route::post('/notifications/destroy', 'NotificationController@destroyNotification');
    });
});

Route::group(['namespace' => 'App\Http\Controllers\API'], function () {
    Route::post('/school_detail', 'LoginController@getSchoolDetail');
    Route::post('/login', 'LoginController@login');
    Route::post('/config_api', 'LoginController@getConfig');
});
