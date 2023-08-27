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

    //import township
    Route::get('/township/list','Category\TownshipController@townshipList');
    Route::post('/township/import','Category\TownshipController@importTownship');

    //for Create Information
    Route::resource('teacher_info','CreateInformation\TeacherInfoController'); 
    Route::post('teacher_info/list','CreateInformation\TeacherInfoController@teacherinfoList'); 
    Route::resource('driver_info','CreateInformation\DriverInfoController');  
    Route::post('driver_info/list','CreateInformation\DriverInfoController@driverinfoList');
    Route::resource('class_setup','CreateInformation\ClassSetupController'); 
    Route::post('class_setup/list','CreateInformation\ClassSetupController@classSetupList');
    Route::get('/student_info/list','CreateInformation\StudentInfoController@studentInfoList');
    Route::post('/student_info/list','CreateInformation\StudentInfoController@studentInfoList');
    Route::get('/student_info/edit/{id}','CreateInformation\StudentInfoController@studentInfoEdit'); 
    Route::post('/student_info/update/{id}','CreateInformation\StudentInfoController@studentInfoUpdate'); 
    Route::resource('schedule','CreateInformation\ScheduleController'); 
    Route::post('schedule/list','CreateInformation\ScheduleController@ScheduleList');
    Route::resource('activity','CreateInformation\ActivityController'); 
    Route::post('activity/list','CreateInformation\ActivityController@ActivityList');


    //for Registration
    Route::resource('student_reg','Registration\StudentRegistrationController'); 
    Route::post('/student_registration/guardian_search','Registration\RegistrationSearchController@guardianSearch');
    Route::post('/student_registration/class_search','Registration\RegistrationSearchController@classSearch');
    Route::post('/student_registration/student_search','Registration\RegistrationSearchController@studentSearch');
    
    Route::resource('waitinglist_reg','Registration\WaitingListRegController'); 
    Route::resource('cancel_reg','Registration\CancelListRegController'); 
    Route::resource('payment','Registration\PaymentRegController'); 

    Route::post('/cancel_reg/registration_search','Registration\RegistrationSearchController@studentRegistrationSearch');
    Route::post('/payment/paymentreg_search','Registration\RegistrationSearchController@paymentRegistrationSearch');
    Route::resource('school_bus_track','Registration\SchoolBusTrackRegController'); 
    Route::post('/school_bus_track/driver_search','Registration\RegistrationSearchController@driverSearch');

    Route::resource('teacher_attendance','Registration\TeacherAttendanceRegController'); 
    Route::resource('student_attendance','Registration\StudentAttendanceRegController'); 

    //for reporting
    Route::get('/reporting/cancel_report','Report\CancelReportController@cancelReport'); 
    Route::post('/reporting/cancel_report','Report\CancelReportController@cancelReport'); 

    Route::get('/reporting/studentregistration_report','Report\StudentRegReportController@studentRegReport'); 
    Route::post('/reporting/studentregistration_report','Report\StudentRegReportController@studentRegReport'); 

    Route::get('/reporting/ferry_report','Report\FerryReportController@ferryReport'); 
    Route::post('/reporting/ferry_report','Report\FerryReportController@ferryReport'); 

    Route::get('/reporting/payment_report','Report\PaymentReportController@paymentReport'); 
    Route::post('/reporting/payment_report','Report\PaymentReportController@paymentReport'); 

    Route::get('/reporting/teacher_attendance_report','Report\TeacherAttendanceReportController@teacherAttendanceReport'); 
    Route::post('/reporting/teacher_attendance_report','Report\TeacherAttendanceReportController@teacherAttendanceReport'); 

    Route::get('/reporting/student_attendance_report','Report\StudentAttendanceReportController@studentAttendanceReport'); 
    Route::post('/reporting/student_attendance_report','Report\StudentAttendanceReportController@studentAttendanceReport');  
   
    //for exam
    Route::get('/exam_terms/list','Exam\ExamTermsController@examTermsList');
    Route::post('/exam_terms/list','Exam\ExamTermsController@examTermsList');
    Route::get('/exam_terms/create','Exam\ExamTermsController@examTermsCreate'); 
    Route::post('/exam_terms/save','Exam\ExamTermsController@examTermsSave'); 
    Route::get('/exam_terms/edit/{id}','Exam\ExamTermsController@examTermsEdit'); 
    Route::post('/exam_terms/update/{id}','Exam\ExamTermsController@examTermsUpdate'); 
    Route::delete('/exam_terms/delete/{id}','Exam\ExamTermsController@examTermsDelete'); 

    Route::get('/exam_marks/list','Exam\ExamMarksController@examMarksList');
    Route::post('/exam_marks/list','Exam\ExamMarksController@examMarksList');
    Route::get('/exam_marks/create','Exam\ExamMarksController@examMarksCreate'); 
    Route::post('/exam_marks/save','Exam\ExamMarksController@examMarksSave'); 
    Route::get('/exam_marks/edit/{id}','Exam\ExamMarksController@examMarksEdit'); 
    Route::post('/exam_marks/update/{id}','Exam\ExamMarksController@examMarksUpdate'); 
    Route::delete('/exam_marks/delete/{id}','Exam\ExamMarksController@examMarksDelete'); 

    //for wallet
    Route::get('/cash_counter/list','Wallet\CashCounterController@CashCounterList');
    Route::post('/cash_counter/list','Wallet\CashCounterController@CashCounterList');
    Route::get('/cash_counter/create','Wallet\CashCounterController@CashCounterCreate'); 
    Route::post('/cash_counter/save','Wallet\CashCounterController@CashCounterSave'); 
    Route::get('/cash_counter/edit/{id}','Wallet\CashCounterController@CashCounterEdit'); 
    Route::post('/cash_counter/update/{id}','Wallet\CashCounterController@CashCounterUpdate'); 
    Route::post('/cash_counter/card_data','Registration\RegistrationSearchController@cardDataSearch');

    Route::get('/cash_in_history/list','Wallet\CashInHistoryController@CashInHistoryList');
    Route::post('/cash_in_history/list','Wallet\CashInHistoryController@CashInHistoryList');

    //for shop
    Route::prefix('menu')->group(function () {
        Route::get('list','Shop\ShopMenuController@shopMenuList');
        Route::post('list','Shop\ShopMenuController@shopMenuList');
        Route::get('create','Shop\ShopMenuController@shopMenuCreate'); 
        Route::post('save','Shop\ShopMenuController@shopMenuSave'); 
        Route::get('edit/{id}','Shop\ShopMenuController@shopMenuEdit'); 
        Route::post('update/{id}','Shop\ShopMenuController@shopMenuUpdate'); 
        Route::delete('delete/{id}','Shop\ShopMenuController@shopMenuDelete'); 
    });

    Route::prefix('food_order')->group(function () {
        Route::get('list','Shop\FoodOrderController@foodOrderList');
        Route::post('list','Shop\FoodOrderController@foodOrderList');
        Route::get('create','Shop\FoodOrderController@foodOrderCreate'); 
        Route::post('save','Shop\FoodOrderController@foodOrderSave'); 
        Route::get('edit/{id}','Shop\FoodOrderController@foodOrderEdit'); 
        Route::post('update/{id}','Shop\FoodOrderController@foodOrderUpdate'); 
        Route::delete('delete/{id}','Shop\FoodOrderController@foodOrderDelete'); 
    });


    //for user 
    Route::resource('user','StaffInfoController');   
    Route::get('/profile','UserController@show_profile'); 
    Route::get('/logout','UserController@logout');

    //for permission
    Route::resource('role_permission','RoleandPermissionController'); 
});

Route::get('/', function () {
    //return view('welcome');
    return redirect(route('login'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

