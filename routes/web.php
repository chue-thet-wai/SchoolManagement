<?php

use Illuminate\Support\Facades\Auth;
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
Route::group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['adminpermission']], function () {
    //for Category
    Route::resource('academic_year', 'Category\AcademicYearController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'academic_year/list', 'Category\AcademicYearController@AcademicYearList');

    Route::resource('branch', 'Category\BranchController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'branch/list', 'Category\BranchController@BranchList');

    Route::resource('room', 'Category\RoomController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'room/list', 'Category\RoomController@RoomList');

    Route::resource('grade', 'Category\GradeController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'grade/list', 'Category\GradeController@GradeList');

    Route::resource('section', 'Category\SectionController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'section/list', 'Category\SectionController@SectionList');

    Route::resource('grade_level_fee', 'Category\GradeLevelFeeController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'grade_level_fee/list', 'Category\GradeLevelFeeController@GradeLevelFeeList');

    Route::resource('additional_fee', 'Category\AdditionalFeeController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'additional_fee/list', 'Category\AdditionalFeeController@AdditionalFeeList');

    Route::resource('subject', 'Category\SubjectController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'subject/list', 'Category\SubjectController@SubjectList');

    //import township
    Route::get('/township/list', 'Category\TownshipController@townshipList');
    Route::post('/township/import', 'Category\TownshipController@importTownship');

    //for Create Information
    Route::resource('teacher_info', 'CreateInformation\TeacherInfoController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'teacher_info/list', 'CreateInformation\TeacherInfoController@teacherinfoList');

    Route::resource('teacher_class', 'CreateInformation\TeacherClassController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'teacher_class/list', 'CreateInformation\TeacherClassController@teacherclassList');

    Route::resource('class_setup', 'CreateInformation\ClassSetupController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'class_setup/list', 'CreateInformation\ClassSetupController@classSetupList');
    Route::post('/class_setup/get_room', 'CreateInformation\ClassSetupController@getRoomwithBranch');

    Route::get('/student_info/list', 'CreateInformation\StudentInfoController@studentInfoList');
    Route::post('/student_info/list', 'CreateInformation\StudentInfoController@studentInfoList');

    Route::get('/student_info/edit/{id}', 'CreateInformation\StudentInfoController@studentInfoEdit');
    Route::post('/student_info/update/{id}', 'CreateInformation\StudentInfoController@studentInfoUpdate');

    Route::get('/guardian_info/list', 'CreateInformation\GuardianInfoController@guardianInfoList');
    Route::post('/guardian_info/list', 'CreateInformation\GuardianInfoController@guardianInfoList');

    Route::get('/guardian_info/edit/{id}', 'CreateInformation\GuardianInfoController@guardianInfoEdit');
    Route::post('/guardian_info/update/{id}', 'CreateInformation\GuardianInfoController@guardianInfoUpdate');

    Route::resource('schedule', 'CreateInformation\ScheduleController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'schedule/list', 'CreateInformation\ScheduleController@ScheduleList');

    Route::resource('activity', 'CreateInformation\ActivityController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'activity/list', 'CreateInformation\ActivityController@ActivityList');

    Route::resource('event', 'Operation\EventController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'event/list', 'Operation\EventController@EventList');

    Route::resource('homework', 'Operation\HomeworkController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'homework/list', 'Operation\HomeworkController@HomeworkList');
    Route::post('homework/class_change', 'Operation\HomeworkController@classChange');

    Route::resource('homework_status', 'Operation\HomeworkStatusController', ['only' => ['store', 'edit', 'update', 'destroy']]);
    Route::get('homework_status/list/{id}', 'Operation\HomeworkStatusController@HomeworkStatusList');
    Route::post('homework_status/list', 'Operation\HomeworkStatusController@HomeworkStatusList');
    Route::get('homework_status/create/{id}', 'Operation\HomeworkStatusController@create')->name('homework_status.create');
    Route::post('homework_status/homework_search', 'Operation\HomeworkStatusController@homeworkSearch');

    Route::resource('daily_activity', 'Operation\DailyActivityController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'daily_activity/list', 'Operation\DailyActivityController@DailyActivityList');
    Route::post('daily_activity/student_search', 'Operation\DailyActivityController@studentSearch');

    Route::resource('student_request', 'Operation\StudentRequestController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'student_request/list', 'Operation\StudentRequestController@StudentRequestList');
    Route::post('student_request/student_search', 'Operation\StudentRequestController@studentSearch');
    Route::post('student_request/save_last_read_time', 'Operation\StudentRequestController@saveLastReadTime');

    Route::prefix('student_request/comment')->group(function () {
        Route::get('list/{id}', 'Operation\StudentRequestCommentController@StudentRequestCommentList')->name('student_request_comment.list');;
        Route::post('store', 'Operation\StudentRequestCommentController@store');
        Route::delete('delete/{id}', 'Operation\StudentRequestCommentController@delete');
    }); 

    Route::resource('message', 'Operation\MessageController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'message/list', 'Operation\MessageController@MessageList');


    //for Registration
    Route::resource('student_reg', 'Registration\StudentRegistrationController');
    Route::post('/student_registration/guardian_search', 'Registration\RegistrationSearchController@guardianSearch');
    Route::post('/student_registration/class_search', 'Registration\RegistrationSearchController@classSearch');
    Route::post('/student_registration/student_search', 'Registration\RegistrationSearchController@studentSearch');

    Route::resource('waitinglist_reg', 'Registration\WaitingListRegController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'waitinglist_reg/list', 'Registration\WaitingListRegController@waitingRegList');

    Route::resource('cancel_reg', 'Registration\CancelListRegController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'cancel_reg/list', 'Registration\CancelListRegController@cancelList');

    Route::resource('invoice', 'Payment\PaymentRegController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'invoice/list', 'Payment\PaymentRegController@paymentList');

    Route::post('/cancel_reg/registration_search', 'Registration\RegistrationSearchController@studentRegistrationSearch');
    Route::post('/invoice/paymentreg_search', 'Registration\RegistrationSearchController@paymentRegistrationSearch');
    Route::post('/invoice/get_class_data', 'Payment\PaymentRegController@getClasswithBranch');
    Route::post('/invoice/paid', 'Payment\PaymentRegController@paidInvoice');

    Route::resource('teacher_attendance', 'Operation\TeacherAttendanceRegController');
    Route::resource('student_attendance', 'Operation\StudentAttendanceRegController');

    //for reporting
    Route::get('/reporting/cancel_report', 'Report\CancelReportController@cancelReport');
    Route::post('/reporting/cancel_report', 'Report\CancelReportController@cancelReport');

    Route::get('/reporting/studentregistration_report', 'Report\StudentRegReportController@studentRegReport');
    Route::post('/reporting/studentregistration_report', 'Report\StudentRegReportController@studentRegReport');

    Route::get('/reporting/ferry_report', 'Report\FerryReportController@ferryReport');
    Route::post('/reporting/ferry_report', 'Report\FerryReportController@ferryReport');

    Route::get('/reporting/payment_report', 'Report\PaymentReportController@paymentReport');
    Route::post('/reporting/payment_report', 'Report\PaymentReportController@paymentReport');

    Route::get('/reporting/teacher_attendance_report', 'Report\TeacherAttendanceReportController@teacherAttendanceReport');
    Route::post('/reporting/teacher_attendance_report', 'Report\TeacherAttendanceReportController@teacherAttendanceReport');

    Route::get('/reporting/student_attendance_report', 'Report\StudentAttendanceReportController@studentAttendanceReport');
    Route::post('/reporting/student_attendance_report', 'Report\StudentAttendanceReportController@studentAttendanceReport');
    Route::post('/reporting/student_attendance_report/approve', 'Report\StudentAttendanceReportController@studentAttendanceApprove');

    Route::get('/reporting/expense_report', 'Report\ExpenseReportController@expenseReport');
    Route::post('/reporting/expense_report', 'Report\ExpenseReportController@expenseReport');

    Route::get('/reporting/pocket_money_report', 'Report\PocketMoneyReportController@pocketMoneyReport');
    Route::post('/reporting/pocket_money_report', 'Report\PocketMoneyReportController@pocketMoneyReport');
    //Route::post('/reporting/pocket_money_report/approve', 'Report\PocketMoneyReportController@pocketMoneyApprove');

    Route::get('/pocket_money_request', 'Payment\PocketMoneyRequestController@pocketMoneyRequest');
    Route::post('/pocket_money_request', 'Payment\PocketMoneyRequestController@pocketMoneyRequest');
    Route::post('/pocket_money_request/approve', 'Payment\PocketMoneyRequestController@pocketMoneyApprove');

    Route::get('/reporting/driver_attendance_report', 'Report\DriverAttendanceReportController@driverAttendanceReport');
    Route::post('/reporting/driver_attendance_report', 'Report\DriverAttendanceReportController@driverAttendanceReport');

    Route::get('/reporting/card_data_report', 'Report\CardDataReportController@cardDataReport');
    Route::post('/reporting/card_data_report', 'Report\CardDataReportController@cardDataReport');

    Route::resource('school_bus_track', 'Registration\SchoolBusTrackRegController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'school_bus_track/list', 'Registration\SchoolBusTrackRegController@schoolBusTracktList');

    Route::resource('driver_routes', 'Driver\DriverRoutesController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'driver_routes/list', 'Driver\DriverRoutesController@driverRoutesList');
    Route::get('driver_routes_detail/{id}', 'Driver\DriverRoutesDetailController@DriverRoutesDetailList')->name('driver_routes_detail');

    Route::get('ferry_payment', 'Driver\FerryPaymentController@ferryPayment');
    Route::post('ferry_payment', 'Driver\FerryPaymentController@ferryPayment');

    Route::resource('news', 'Operation\NewsController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'news/list', 'Operation\NewsController@NewsList');
    Route::post('news/student_search', 'Operation\NewsController@studentSearch');

    Route::prefix('news/comment')->group(function () {
        Route::get('list/{id}', 'Operation\NewsCommentController@NewsCommentList')->name('news_comment.list');;
        Route::post('store', 'Operation\NewsCommentController@store');
        Route::delete('delete/{id}', 'Operation\NewsCommentController@delete');
    }); 

    Route::resource('certificate', 'Exam\CertificateController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'certificate/list', 'Exam\CertificateController@CertificateList');
    Route::post('certificate/student_search', 'Exam\CertificateController@studentSearch');

    //for exam
    Route::prefix('exam_terms')->group(function () {
        Route::get('list', 'Exam\ExamTermsController@examTermsList');
        Route::post('list', 'Exam\ExamTermsController@examTermsList');
        Route::get('create', 'Exam\ExamTermsController@examTermsCreate');
        Route::post('save', 'Exam\ExamTermsController@examTermsSave');
        Route::get('edit/{id}', 'Exam\ExamTermsController@examTermsEdit');
        Route::post('update/{id}', 'Exam\ExamTermsController@examTermsUpdate');
        Route::delete('delete/{id}', 'Exam\ExamTermsController@examTermsDelete');
    }); 
    
    //for exam
    Route::prefix('exam_terms_detail')->group(function () {
        Route::get('list/{id}', 'Exam\ExamTermsDetailController@ExamTermsDetailListwithGet');
        Route::post('list', 'Exam\ExamTermsDetailController@ExamTermsDetailList');
        Route::get('create/{examtermid}', 'Exam\ExamTermsDetailController@create');
        Route::post('store', 'Exam\ExamTermsDetailController@store');
        Route::get('edit/{id}', 'Exam\ExamTermsDetailController@edit');
        Route::post('update/{id}', 'Exam\ExamTermsDetailController@update');
        Route::delete('delete/{id}', 'Exam\ExamTermsDetailController@delete');
    }); 

    Route::prefix('exam_marks')->group(function () {
        Route::get('list', 'Exam\ExamMarksController@examMarksList');
        Route::post('list', 'Exam\ExamMarksController@examMarksList');
        Route::get('create', 'Exam\ExamMarksController@examMarksCreate');
        Route::post('save', 'Exam\ExamMarksController@examMarksSave');
        Route::get('edit/{id}', 'Exam\ExamMarksController@examMarksEdit');
        Route::post('update/{id}', 'Exam\ExamMarksController@examMarksUpdate');
        Route::delete('delete/{id}', 'Exam\ExamMarksController@examMarksDelete');
        Route::post('change_class', 'Exam\ExamMarksController@changeClass');
        Route::post('change_examterms', 'Exam\ExamMarksController@changeExamTerms');
    });

    Route::prefix('exam_rules')->group(function () {
        Route::get('list', 'Exam\ExamRulesController@examRulesList');
        Route::post('list', 'Exam\ExamRulesController@examRulesList');
        Route::get('create', 'Exam\ExamRulesController@examRulesCreate');
        Route::post('save', 'Exam\ExamRulesController@examRulesSave');
        Route::get('edit/{id}', 'Exam\ExamRulesController@examRulesEdit');
        Route::post('update/{id}', 'Exam\ExamRulesController@examRulesUpdate');
        Route::delete('delete/{id}', 'Exam\ExamRulesController@examRulesDelete');
    });

    //for wallet
    Route::prefix('cash_counter')->group(function () {
        Route::get('list', 'Wallet\CashCounterController@CashCounterList');
        Route::post('list', 'Wallet\CashCounterController@CashCounterList');
        Route::get('create', 'Wallet\CashCounterController@CashCounterCreate');
        Route::post('save', 'Wallet\CashCounterController@CashCounterSave');
        Route::get('edit/{id}', 'Wallet\CashCounterController@CashCounterEdit');
        Route::post('update/{id}', 'Wallet\CashCounterController@CashCounterUpdate');
        Route::post('card_data', 'Registration\RegistrationSearchController@cardDataSearch');
    });

    Route::get('/cash_in_history/list', 'Wallet\CashInHistoryController@CashInHistoryList');
    Route::post('/cash_in_history/list', 'Wallet\CashInHistoryController@CashInHistoryList');

    //for shop
    Route::prefix('menu')->group(function () {
        Route::get('list', 'Shop\ShopMenuController@shopMenuList');
        Route::post('list', 'Shop\ShopMenuController@shopMenuList');
        Route::get('create', 'Shop\ShopMenuController@shopMenuCreate');
        Route::post('save', 'Shop\ShopMenuController@shopMenuSave');
        Route::get('edit/{id}', 'Shop\ShopMenuController@shopMenuEdit');
        Route::post('update/{id}', 'Shop\ShopMenuController@shopMenuUpdate');
        Route::delete('delete/{id}', 'Shop\ShopMenuController@shopMenuDelete');
    });

    Route::prefix('food_order')->group(function () {
        Route::get('list', 'Shop\FoodOrderController@foodOrderList');
        Route::post('list', 'Shop\FoodOrderController@foodOrderList');
        Route::get('create', 'Shop\FoodOrderController@foodOrderCreate');
        Route::post('save', 'Shop\FoodOrderController@foodOrderSave');
        Route::get('edit/{id}', 'Shop\FoodOrderController@foodOrderEdit');
        Route::post('update/{id}', 'Shop\FoodOrderController@foodOrderUpdate');
        Route::delete('delete/{id}', 'Shop\FoodOrderController@foodOrderDelete');
    });

    Route::prefix('book_category')->group(function () {
        Route::get('list', 'Library\BookCategoryController@bookCategoryList');
        Route::post('list', 'Library\BookCategoryController@bookCategoryList');
        Route::get('create', 'Library\BookCategoryController@bookCategoryCreate');
        Route::post('save', 'Library\BookCategoryController@bookCategorySave');
        Route::get('edit/{id}', 'Library\BookCategoryController@bookCategoryEdit');
        Route::post('update/{id}', 'Library\BookCategoryController@bookCategoryUpdate');
        Route::delete('delete/{id}', 'Library\BookCategoryController@bookCategoryDelete');
    });

    Route::prefix('book_name_register')->group(function () {
        Route::get('list', 'Library\BookRegisterController@bookRegisterList');
        Route::post('list', 'Library\BookRegisterController@bookRegisterList');
        Route::get('create', 'Library\BookRegisterController@bookRegisterCreate');
        Route::post('save', 'Library\BookRegisterController@bookRegisterSave');
        Route::get('edit/{id}', 'Library\BookRegisterController@bookRegisterEdit');
        Route::post('update/{id}', 'Library\BookRegisterController@bookRegisterUpdate');
        Route::delete('delete/{id}', 'Library\BookRegisterController@bookRegisterDelete');
    });

    Route::prefix('book_rent')->group(function () {
        Route::get('list', 'Library\BookRentController@bookRentList');
        Route::post('list', 'Library\BookRentController@bookRentList');
        Route::get('create', 'Library\BookRentController@bookRentCreate');
        Route::post('save', 'Library\BookRentController@bookRentSave');
        Route::get('edit/{id}', 'Library\BookRentController@bookRentEdit');
        Route::post('update/{id}', 'Library\BookRentController@bookRentUpdate');
        Route::delete('delete/{id}', 'Library\BookRentController@bookRentDelete');
    });

    //for expense
    Route::prefix('expense')->group(function () {
        Route::get('list', 'Expense\ExpenseController@expenseList');
        Route::post('list', 'Expense\ExpenseController@expenseList');
        Route::get('create', 'Expense\ExpenseController@expenseCreate');
        Route::post('save', 'Expense\ExpenseController@expenseSave');
        Route::get('edit/{id}', 'Expense\ExpenseController@expenseEdit');
        Route::post('update/{id}', 'Expense\ExpenseController@expenseUpdate');
        Route::delete('delete/{id}', 'Expense\ExpenseController@expenseDelete');
    });

    //for chat
    Route::prefix('chat')->group(function () {
        Route::get('list', 'Chat\ChatController@chatList');
        Route::post('list', 'Chat\ChatController@chatList');
        Route::get('/chat_with/{guardianId}', 'Chat\ChatController@show')->name('chat.show');
        Route::post('send-message', 'Chat\ChatController@sendMessage')->name('send.message');
        Route::post('/save-last-read-at', 'Chat\ChatController@saveLastReadAt')->name('save.last_read_at');
    });

    //for school Registration
    Route::prefix('school_registration')->group(function () {
        Route::get('list', 'School\SchoolController@schoolList');
        Route::post('list', 'School\SchoolController@schoolList');
        Route::get('create', 'School\SchoolController@schoolCreate');
        Route::post('save', 'School\SchoolController@schoolSave');
        Route::get('edit/{id}', 'School\SchoolController@schoolEdit');
        Route::post('update/{id}', 'School\SchoolController@schoolUpdate');
        Route::delete('delete/{id}', 'School\SchoolController@schoolDelete');
    });

    //for driver
    Route::resource('driver_info', 'Driver\DriverInfoController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'driver_info/list', 'Driver\DriverInfoController@driverinfoList');

    Route::resource('school_bus_track', 'Driver\SchoolBusTrackRegController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'school_bus_track/list', 'Driver\SchoolBusTrackRegController@schoolBusTracktList');
    
    Route::post('/school_bus_track/driver_search', 'Registration\RegistrationSearchController@driverSearch');

    Route::resource('ferry_student', 'Driver\FerryStudentController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'ferry_student/list', 'Driver\FerryStudentController@FerryStudentList');
    Route::post('ferry_student/student_search', 'Driver\FerryStudentController@studentRegistrationSearch');

     Route::prefix('school_bus_track_detail')->group(function () {
        Route::get('list/{id}', 'Driver\SchoolBusTrackDetailController@SchoolBusTrackDetailListwithGet');
        Route::post('list', 'Driver\SchoolBusTrackDetailController@SchoolBusTrackDetailList');
        Route::post('add', 'Driver\SchoolBusTrackDetailController@addFerryStudentDetail');
        Route::post('paid', 'Driver\SchoolBusTrackDetailController@paidFerry');
    });
     

    //for user 
    Route::resource('user', 'StaffInfoController', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    Route::match(['get', 'post'], 'user/list', 'StaffInfoController@staffInfoList');

    Route::get('/profile', 'UserController@show_profile');
    Route::get('/logout', 'UserController@logout');

    //for permission
    Route::resource('role_permission', 'RoleandPermissionController');

});

Route::get('/', function () {
    //return view('welcome');
    return redirect(route('login'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/dashboard_payment', [App\Http\Controllers\HomeController::class, 'getDashboardPayment']);
Route::get('/full-calender', [App\Http\Controllers\HomeController::class, 'getEvent']);

Route::get('parent/login', 'App\Http\Controllers\Parent\LoginController@parentLogin');
Route::post('parent/login/submit', 'App\Http\Controllers\Parent\LoginController@parentLoginSubmit');

Route::group(['prefix' => 'parent', 'namespace' => 'App\Http\Controllers\Parent', 'middleware' => ['parentpermission']], function () {   

    Route::get('/home', 'HomeController@parentProfile');
    Route::get('/edit_profile', 'HomeController@editProfile'); 
    Route::post('/edit_profile/submit', 'HomeController@editProfileSubmit'); 
    Route::get('/change_password', 'HomeController@changePassword'); 
    Route::post('/change_password/submit', 'HomeController@changePasswordSubmit');  
    Route::get('/contacts', 'HomeController@parentContacts'); 

    Route::get('/annoucement', 'CalenderController@parentAnnoucement');
    Route::get('/messages', 'MessageController@parentMessages');

    Route::get('/student_profile/{id}', 'StudentProfileController@parentStudentProfile');

    //for each student
    Route::get('/student_profile/{id}/exam-date', 'StudentProfileController@profileExamDate');
    Route::get('/student_profile/{id}/attendance', 'StudentProfileController@profileAttendance');
    Route::get('/student_profile/{id}/exam-result', 'StudentProfileController@profileExamResult');
    Route::get('/student_profile/{id}/exam-result/{exam_term}', 'StudentProfileController@profileExamResultDetail');

    Route::get('/student_profile/{id}/curriculum', 'StudentProfileController@profileCurriculum');
    Route::get('/student_profile/{id}/curriculum/{day}', 'StudentProfileController@profileCurriculumDay');
   
    Route::get('/student_profile/{id}/homework', 'StudentProfileController@profileHomework');
    Route::get('/student_profile/{id}/messages', 'StudentProfileController@profileMessages');
    Route::get('/student_profile/{id}/event', 'StudentProfileController@profileEvent'); 
    Route::get('/student_profile/{id}/billing', 'StudentProfileController@profileBilling');

    Route::get('/student_profile/{id}/attendance/leave_request', 'StudentProfileController@leaveRequest'); 
    Route::post('/student_profile/{id}/attendance/leave_request_submit', 'StudentProfileController@leaveRequestSubmit');     

});

//for driver
Route::get('driver/login', 'App\Http\Controllers\Driver\LoginController@driverLogin');
Route::post('driver/login/submit', 'App\Http\Controllers\Driver\LoginController@driverLoginSubmit');

Route::group(['prefix' => 'driver', 'namespace' => 'App\Http\Controllers\Driver', 'middleware' => ['driverpermission']], function () {   

    Route::get('/home', 'HomeController@driverHome');
    Route::get('/profile', 'HomeController@driverProfile');
    Route::get('/schedule', 'HomeController@driverSchedule');
    Route::get('/setting', 'HomeController@driverSetting');
    Route::post('/setting/submit', 'HomeController@driverSettingSubmit');
    Route::get('/attendance', 'HomeController@driverAttendance');
    Route::get('/route/{routeId}', 'HomeController@driverRoute');
    Route::post('/update_route_status', 'HomeController@updateRouteStatus');

    Route::get('/logout', 'LoginController@logout');

    Route::post('/checkin', 'HomeController@checkIn')->name('driver.checkin');
    Route::post('/checkout', 'HomeController@checkOut')->name('driver.checkout');

});
