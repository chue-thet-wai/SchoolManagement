<?php

use Illuminate\Support\Facades\Route;

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

//Admin
Route::group(['prefix'=>'admin','namespace'=>'App\Http\Controllers\Admin','middleware'=>['adminpermission']],function(){
    //for Category
    Route::resource('academic_year','Category\AcademicYearController');  
    Route::resource('branch','Category\BranchController'); 
    Route::resource('room','Category\RoomController'); 
    Route::resource('grade','Category\GradeController');  
    Route::resource('section','Category\SectionController'); 
    Route::resource('grade_level_fee','Category\GradeLevelFeeController'); 
    Route::resource('additional_fee','Category\AdditionalFeeController'); 
    Route::resource('subject','Category\SubjectController'); 

    //for Create Information
    Route::resource('teacher_info','CreateInformation\TeacherInfoController'); 
    Route::resource('driver_info','CreateInformation\DriverInfoController');  
    Route::resource('class_setup','CreateInformation\ClassSetupController'); 
    Route::get('/student_info/list','CreateInformation\StudentInfoController@studentInfoList'); 

    //for Registration
    Route::resource('student_reg','Registration\StudentRegistrationController'); 
    Route::post('/student_registration/guardian_search','Registration\RegistrationSearchController@guardianSearch');
    Route::post('/student_registration/class_search','Registration\RegistrationSearchController@classSearch');
    Route::post('/student_registration/student_search','Registration\RegistrationSearchController@studentSearch');
    
    Route::resource('waitinglist_reg','Registration\WaitingListRegController'); 
    Route::resource('cancel_reg','Registration\CancelListRegController'); 
    Route::post('/cancel_reg/registration_search','Registration\RegistrationSearchController@studentRegistrationSearch');
    Route::resource('school_bus_track','Registration\SchoolBusTrackRegController'); 
    Route::post('/school_bus_track/driver_search','Registration\RegistrationSearchController@driverSearch');

    //for user 
    Route::resource('user','StaffInfoController');   
    Route::get('/profile','UserController@show_profile'); 
    Route::get('/logout','UserController@logout');
});

Route::get('/', function () {
    //return view('welcome');
    return redirect(route('login'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

