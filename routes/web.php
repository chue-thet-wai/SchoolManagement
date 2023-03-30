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

    //for user 
    Route::resource('user','StaffInfoController');   
    Route::get('/profile','UserController@show_profile'); 
    Route::get('/logout','UserController@logout');
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

