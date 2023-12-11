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
    Route::resource('academic_year','Category\AcademicYearController', ['only' => ['create','store','edit', 'update', 'destroy']]);  
    Route::match(['get', 'post'],'academic_year/list','Category\AcademicYearController@AcademicYearList');

    Route::resource('branch','Category\BranchController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'], 'branch/list', 'Category\BranchController@BranchList');
    
    Route::resource('room','Category\RoomController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'room/list','Category\RoomController@RoomList');

    Route::resource('grade','Category\GradeController', ['only' => ['create','store','edit', 'update', 'destroy']]);  
    Route::match(['get', 'post'],'grade/list','Category\GradeController@GradeList');

    Route::resource('section','Category\SectionController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'section/list','Category\SectionController@SectionList');

    Route::resource('grade_level_fee','Category\GradeLevelFeeController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'grade_level_fee/list','Category\GradeLevelFeeController@GradeLevelFeeList');

    Route::resource('additional_fee','Category\AdditionalFeeController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'additional_fee/list','Category\AdditionalFeeController@AdditionalFeeList');

    Route::resource('subject','Category\SubjectController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'subject/list','Category\SubjectController@SubjectList');

    //import township
    Route::get('/township/list','Category\TownshipController@townshipList');
    Route::post('/township/import','Category\TownshipController@importTownship');

    //for Create Information
    Route::resource('teacher_info','CreateInformation\TeacherInfoController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'teacher_info/list','CreateInformation\TeacherInfoController@teacherinfoList'); 

    Route::resource('driver_info','CreateInformation\DriverInfoController', ['only' => ['create','store','edit', 'update', 'destroy']]);  
    Route::match(['get', 'post'],'driver_info/list','CreateInformation\DriverInfoController@driverinfoList');

    Route::resource('class_setup','CreateInformation\ClassSetupController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'class_setup/list','CreateInformation\ClassSetupController@classSetupList');

    Route::get('/student_info/list','CreateInformation\StudentInfoController@studentInfoList', ['only' => ['create','store','edit', 'update', 'destroy']]);
    Route::post('/student_info/list','CreateInformation\StudentInfoController@studentInfoList');

    Route::get('/student_info/edit/{id}','CreateInformation\StudentInfoController@studentInfoEdit', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::post('/student_info/update/{id}','CreateInformation\StudentInfoController@studentInfoUpdate'); 

    Route::resource('schedule','CreateInformation\ScheduleController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'schedule/list','CreateInformation\ScheduleController@ScheduleList');

    Route::resource('activity','CreateInformation\ActivityController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'activity/list','CreateInformation\ActivityController@ActivityList');

    Route::resource('event','CreateInformation\EventController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'event/list','CreateInformation\EventController@EventList');

    Route::resource('homework','CreateInformation\HomeworkController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'homework/list','CreateInformation\HomeworkController@HomeworkList');


    //for Registration
    Route::resource('student_reg','Registration\StudentRegistrationController'); 
    Route::post('/student_registration/guardian_search','Registration\RegistrationSearchController@guardianSearch');
    Route::post('/student_registration/class_search','Registration\RegistrationSearchController@classSearch');
    Route::post('/student_registration/student_search','Registration\RegistrationSearchController@studentSearch');
    
    Route::resource('waitinglist_reg','Registration\WaitingListRegController',['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'waitinglist_reg/list','Registration\WaitingListRegController@waitingRegList');

    Route::resource('cancel_reg','Registration\CancelListRegController',['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'cancel_reg/list','Registration\CancelListRegController@cancelList');

    Route::resource('payment','Registration\PaymentRegController',['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'payment/list','Registration\PaymentRegController@paymentList');

    Route::post('/cancel_reg/registration_search','Registration\RegistrationSearchController@studentRegistrationSearch');
    Route::post('/payment/paymentreg_search','Registration\RegistrationSearchController@paymentRegistrationSearch');
    Route::post('/payment/get_class_data','Registration\PaymentRegController@getClasswithBranch');
    Route::post('/payment/paid','Registration\PaymentRegController@paidInvoice');

    Route::resource('school_bus_track','Registration\SchoolBusTrackRegController',['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'school_bus_track/list','Registration\SchoolBusTrackRegController@schoolBusTracktList');

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

    Route::get('/reporting/expense_report','Report\ExpenseReportController@expenseReport'); 
    Route::post('/reporting/expense_report','Report\ExpenseReportController@expenseReport');  
   
    //for exam
    Route::prefix('exam_terms')->group(function () {
        Route::get('list','Exam\ExamTermsController@examTermsList');
        Route::post('list','Exam\ExamTermsController@examTermsList');
        Route::get('create','Exam\ExamTermsController@examTermsCreate'); 
        Route::post('save','Exam\ExamTermsController@examTermsSave'); 
        Route::get('edit/{id}','Exam\ExamTermsController@examTermsEdit'); 
        Route::post('update/{id}','Exam\ExamTermsController@examTermsUpdate'); 
        Route::delete('delete/{id}','Exam\ExamTermsController@examTermsDelete');
    }); 

    Route::prefix('exam_marks')->group(function () {
        Route::get('list','Exam\ExamMarksController@examMarksList');
        Route::post('list','Exam\ExamMarksController@examMarksList');
        Route::get('create','Exam\ExamMarksController@examMarksCreate'); 
        Route::post('save','Exam\ExamMarksController@examMarksSave'); 
        Route::get('edit/{id}','Exam\ExamMarksController@examMarksEdit'); 
        Route::post('update/{id}','Exam\ExamMarksController@examMarksUpdate'); 
        Route::delete('delete/{id}','Exam\ExamMarksController@examMarksDelete');
    }); 

    Route::prefix('exam_rules')->group(function () {
        Route::get('list','Exam\ExamRulesController@examRulesList');
        Route::post('list','Exam\ExamRulesController@examRulesList');
        Route::get('create','Exam\ExamRulesController@examRulesCreate'); 
        Route::post('save','Exam\ExamRulesController@examRulesSave'); 
        Route::get('edit/{id}','Exam\ExamRulesController@examRulesEdit'); 
        Route::post('update/{id}','Exam\ExamRulesController@examRulesUpdate'); 
        Route::delete('delete/{id}','Exam\ExamRulesController@examRulesDelete');
    }); 

    //for wallet
    Route::prefix('cash_counter')->group(function () {
        Route::get('list','Wallet\CashCounterController@CashCounterList');
        Route::post('list','Wallet\CashCounterController@CashCounterList');
        Route::get('create','Wallet\CashCounterController@CashCounterCreate'); 
        Route::post('save','Wallet\CashCounterController@CashCounterSave'); 
        Route::get('edit/{id}','Wallet\CashCounterController@CashCounterEdit'); 
        Route::post('update/{id}','Wallet\CashCounterController@CashCounterUpdate'); 
        Route::post('card_data','Registration\RegistrationSearchController@cardDataSearch');
    });

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

    Route::prefix('book_category')->group(function () {
        Route::get('list','Library\BookCategoryController@bookCategoryList');
        Route::post('list','Library\BookCategoryController@bookCategoryList');
        Route::get('create','Library\BookCategoryController@bookCategoryCreate'); 
        Route::post('save','Library\BookCategoryController@bookCategorySave'); 
        Route::get('edit/{id}','Library\BookCategoryController@bookCategoryEdit'); 
        Route::post('update/{id}','Library\BookCategoryController@bookCategoryUpdate'); 
        Route::delete('delete/{id}','Library\BookCategoryController@bookCategoryDelete'); 
    });

    Route::prefix('book_register')->group(function () {
        Route::get('list','Library\BookRegisterController@bookRegisterList');
        Route::post('list','Library\BookRegisterController@bookRegisterList');
        Route::get('create','Library\BookRegisterController@bookRegisterCreate'); 
        Route::post('save','Library\BookRegisterController@bookRegisterSave'); 
        Route::get('edit/{id}','Library\BookRegisterController@bookRegisterEdit'); 
        Route::post('update/{id}','Library\BookRegisterController@bookRegisterUpdate'); 
        Route::delete('delete/{id}','Library\BookRegisterController@bookRegisterDelete'); 
    });

    Route::prefix('book_rent')->group(function () {
        Route::get('list','Library\BookRentController@bookRentList');
        Route::post('list','Library\BookRentController@bookRentList');
        Route::get('create','Library\BookRentController@bookRentCreate'); 
        Route::post('save','Library\BookRentController@bookRentSave'); 
        Route::get('edit/{id}','Library\BookRentController@bookRentEdit'); 
        Route::post('update/{id}','Library\BookRentController@bookRentUpdate'); 
        Route::delete('delete/{id}','Library\BookRentController@bookRentDelete'); 
    });

    //for expense
    Route::prefix('expense')->group(function () {
        Route::get('list','Expense\ExpenseController@expenseList');
        Route::post('list','Expense\ExpenseController@expenseList');
        Route::get('create','Expense\ExpenseController@expenseCreate'); 
        Route::post('save','Expense\ExpenseController@expenseSave'); 
        Route::get('edit/{id}','Expense\ExpenseController@expenseEdit'); 
        Route::post('update/{id}','Expense\ExpenseController@expenseUpdate'); 
        Route::delete('delete/{id}','Expense\ExpenseController@expenseDelete'); 
    });

    //for user 
    Route::resource('user','StaffInfoController', ['only' => ['create','store','edit', 'update', 'destroy']]); 
    Route::match(['get', 'post'],'user/list','StaffInfoController@staffInfoList');
   
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

