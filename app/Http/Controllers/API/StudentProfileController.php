<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\BillPaymentRequest;
use App\Http\Requests\API\CurriculumRequest;
use App\Http\Requests\API\ExamTermsResultRequest;
use App\Http\Requests\API\LeaveRequest;
use App\Http\Requests\API\PocketMoneyRequest;
use App\Http\Requests\API\SaveHomeworkCommentRequest;
use App\Http\Requests\API\SaveNewsCommentRequest;
use App\Http\Requests\API\SaveSpecialRequest;
use App\Http\Requests\API\SaveSpecialRequestComment;
use App\Http\Requests\API\SaveSpecialRequestLastReadAt;
use App\Http\Requests\API\StudentProfileRequest;
use App\Http\Requests\API\StudentSpecialRequest;
use App\Http\Resources\API\AttendanceResource;
use App\Http\Resources\API\BillingResource;
use App\Http\Resources\API\CertificateResource;
use App\Http\Resources\API\CheckNewSpecialRequestResource;
use App\Http\Resources\API\CurriculumResource;
use App\Http\Resources\API\EventResource;
use App\Http\Resources\API\ExamDateResource;
use App\Http\Resources\API\ExamTermsResource;
use App\Http\Resources\API\ExamTermsResultResource;
use App\Http\Resources\API\FerryTrackingResource;
use App\Http\Resources\API\HomeworkResource;
use App\Http\Resources\API\MessageResource;
use App\Http\Resources\API\NewsResource;
use App\Http\Resources\API\PocketMoneyRequestResource;
use App\Http\Resources\API\ProgressResource;
use App\Http\Resources\API\StudentRequestResource;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Models\AcademicYear;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\ExamMarks;
use App\Models\ExamTerms;
use App\Models\ExamTermsDetail;
use App\Models\FerryStudent;
use App\Models\Homework;
use App\Models\HomeworkStatus;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\News;
use App\Models\Payment;
use App\Models\Schedules;
use App\Models\StudentDailyActivity;
use App\Models\StudentRegistration;
use App\Models\StudentRequest;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Services\NotificationService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging;
use Illuminate\Container\Container;

class StudentProfileController extends Controller
{
    private $academic_year_id;
    private RegistrationRepositoryInterface $regRepository;
    private $notificationService;

    public function __construct(RegistrationRepositoryInterface $regRepository,NotificationService $notificationService)
    {
        $this->regRepository      = $regRepository;
        $this->notificationService = $notificationService;

        $currentDate = date("Y-m-d");
        //current academic year
        $currentAcademic = AcademicYear::where('start_date', '<=', $currentDate)
                        ->where('end_date', '>=', $currentDate)
                        ->first();
        $this->academic_year_id = $currentAcademic ? $currentAcademic->id : null;
    }
    
    public function examDate(StudentProfileRequest $request)
    {
        try {
            $currentDate = new DateTime();
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                            ->join('class_setup','class_setup.id','student_registration.new_class_id')
                            ->select('class_setup.grade_id')
                            ->latest('student_registration.created_at')
                            ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $grade_id = $studentData->grade_id;

            $examTerms = ExamTerms::where('grade_id',$grade_id)
                            ->where('academic_year_id',$this->academic_year_id)
                            ->get()->toArray();
            $examTermsIds = array_column($examTerms,'id');
            $examTermsDetail = ExamTermsDetail::leftjoin('subject','subject.id','exam_terms_detail.subject_id')
                                ->whereIn('exam_terms_id',$examTermsIds)
                                ->where('exam_date','>',$currentDate)
                                ->select('exam_terms_detail.*','subject.name as subject_name')
                                ->orderBy('exam_date')
                                ->get();

                
            if (count($examTermsDetail) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new ExamDateResource($examTermsDetail),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function homework(StudentProfileRequest $request)
    {
        try {
            $homework_status = array(
                "1" => "Not Yet",
                "2" => "Complete",
                "3" => "Incompleted"
            );  
            $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$request->student_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => [],
                    'homewok_status' => $homework_status
                ]);
            }
            $class_id = $studentData->new_class_id;

            $homework = Homework::leftjoin('subject','subject.id','homework.subject_id')
                        ->where('class_id',$class_id)
                        ->where('academic_year_id',$this->academic_year_id)
                        ->select('homework.*','subject.name as subject_name')
                        ->get();
  
            if (count($homework) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new HomeworkResource($homework),
                    'homewok_status' => $homework_status
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => [],
                    'homewok_status' => $homework_status
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function saveHomeworkComment(SaveHomeworkCommentRequest $request)
    {
        DB::beginTransaction();
        try{
            $nowDate  = date('Y-m-d H:i:s', time());
            $guardian_id = Auth::id();
                      
            $updateData = array(
                'remark'            =>$request->comment,
                'updated_by'        =>$guardian_id,
                'updated_at'        =>$nowDate
            );
            
            $result=HomeworkStatus::where('homework_id',$request->homework_id)
                    ->where('student_id',$request->student_id)
                    ->update($updateData);
                        
            if($result){     
                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'Homework Comment saved Successfully.'
                ]); 
            }else{
                return response()->json([
                    'status'  => 422,
                    'message' => 'Homework Comment saved Fail.'
                ],422); 
            }      

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        } 
    }

    public function message(StudentProfileRequest $request) 
    {
        try {
            
            $messages = Message::where('student_id',$request->student_id)->get();   
            if (count($messages) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new MessageResource($messages),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function event(StudentProfileRequest $request)
    {
        try {
            $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$request->student_id)
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name',
                        'class_setup.grade_id as grade_id')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $grade_id = $studentData->grade_id;

            $event = Event::where('grade_id',$grade_id)
                        ->where('academic_year_id',$this->academic_year_id)
                        ->get();

                
            if (count($event) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new EventResource($event),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function billing(StudentProfileRequest $request)
    {
        try {
            $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$request->student_id)
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name')
                        ->latest('student_registration.created_at')
                        ->first();

            $unpaidinvoices = Invoice::join('student_info','student_info.student_id','invoice.student_id')
                            ->where('invoice.student_id',$request->student_id)
                            ->where('paid_status',0)->get();
            $paidinvoices = Invoice::where('student_id',$request->student_id)
                            ->where('paid_status',1)->get();

                
            if (count($unpaidinvoices) > 0 || count($paidinvoices)>0) {
                $response = [];
                
                if (count($unpaidinvoices) > 0) {
                    $response['unpaid_invoices'] = new BillingResource($unpaidinvoices);
                } else {
                    $response['unpaid_invoices'] = [];
                }
                if (count($paidinvoices) > 0) {
                    $response['paid_invoices'] = new BillingResource($paidinvoices);
                } else {
                    $response['paid_invoices'] = [];
                }
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => $response,
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => null
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function curriculum(CurriculumRequest $request) 
    {
        try {
            $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$request->student_id)
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $class_id = $studentData->new_class_id;

            $schedule = Schedules::leftjoin('subject','subject.id','schedules.subject_id')
                            ->where('class_id',$class_id)
                            ->where('weekdays',$request->day_number)
                            ->select('schedules.*','subject.name as subject_name')
                            ->orderBy('start_time')
                            ->get();
            if (count($schedule) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new CurriculumResource($schedule),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function examTerms(StudentProfileRequest $request)
    {
        try {
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('class_setup.grade_id')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $grade_id = $studentData->grade_id;

            $examTerms = ExamTerms::where('academic_year_id',$this->academic_year_id)
                            ->where('grade_id',$grade_id)
                            ->get();    

                
            if (count($examTerms) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new ExamTermsResource($examTerms),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function examTermsResult(ExamTermsResultRequest $request)
    {
        try {
            $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$request->student_id)
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $class_id = $studentData->new_class_id;

            $passMinMarks = 50;

            $examMarks = ExamMarks::join('subject','subject.id','exam_marks.subject_id')
                            ->where('student_id',$request->student_id)
                            ->where('class_id',$class_id)
                            ->where('exam_terms_id',$request->exam_terms_id)
                            ->select('exam_marks.*','subject.name as subject_name', 
                                DB::raw('CASE WHEN exam_marks.mark >= ' . $passMinMarks . ' THEN "Pass" ELSE "Fail" END as result'))
                            ->get();  

                
            if (count($examMarks) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new ExamTermsResultResource($examMarks),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function attendances(StudentProfileRequest $request)
    {
        try {
           
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                            ->join('class_setup','class_setup.id','student_registration.new_class_id')
                            ->where('class_setup.academic_year_id',$this->academic_year_id)
                            ->latest('student_registration.created_at')
                            ->select('student_registration.registration_no')
                            ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $registration_no = $studentData->registration_no;

            $attendanceData = DB::table('student_attendance')
                            ->where('student_id',$request->student_id)
                            ->where('registration_no',$registration_no)
                            ->get();         

            
            if (count($attendanceData) > 0) {
                $attendanceList = AttendanceResource::collection($attendanceData);
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => $attendanceList,
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function leaveRequest(LeaveRequest $request)
    {
        DB::beginTransaction();
        try{
            $nowDate  = date('Y-m-d H:i:s', time());
            $guardian_id = Auth::id();
            $start_date  = Carbon::parse($request->from_date);
            $end_date    = Carbon::parse($request->to_date);
            $student_id  = $request->student_id;

            //To get registration number
            $studentData = StudentRegistration::where('student_id',$student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

            $registration_no = $studentData->registration_no;
            //insert student_leave table
            $leaveData= array(
                'student_id'        =>$student_id,
                'title'             =>$request->title,
                'description'       =>$request->description,
                'from_date'         =>$request->from_date,
                'to_date'           =>$request->to_date,
                'created_by'        =>$guardian_id,
                'updated_by'        =>$guardian_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $leaveId = DB::table('student_leave')->insertGetId($leaveData);
            if ($leaveData) {
                $insertData = [];
                while ($start_date <= $end_date) {
                    $insertData[] = array(
                        'student_id'        => $student_id,
                        'registration_no'   => $registration_no,
                        'attendance_date'   => $start_date->toDateString(), // Use toDateString() to get the date as a string
                        'attendance_status' => '2', // for leave
                        'status'            => 0,   // pending
                        'leave_id'          => $leaveId,
                        'remark'            => $request->description,
                        'created_by'        => $guardian_id,
                        'updated_by'        => $guardian_id,
                        'created_at'        => $nowDate,
                        'updated_at'        => $nowDate
                    );
                
                    // Increment the date by one day
                    $start_date->addDay();
                }
                
                if (count($insertData) > 0 ) {
                    $result=DB::table('student_attendance')->insert($insertData);                        
                    if($result){      
                        DB::commit();
                        return response()->json([
                            'status'  => 200,
                            'message' => 'Leave Submitted Successfully.'
                        ]);   
                        
                    }else{
                        return response()->json([
                            'status'  => 422,
                            'message' => 'Leave Submitted Fail.'
                        ],422); 
                    }
                }
            } else {
                return response()->json([
                    'status'  => 422,
                    'message' => 'Leave Submitted Fail.'
                ],422); 
            }        

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        } 
    }

    public function progress(StudentProfileRequest $request)
    {
        try {
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => null
                ]);
            }
            $registration_id = $studentData->registration_no;

            $prevdailyActivities = StudentDailyActivity::where('student_id', $request->student_id)
                ->where('registration_id', $registration_id)
                ->selectRaw('SUM(CASE WHEN date >= ? AND date <= ? THEN rate ELSE 0 END) as previous_month_total', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
                ->first();

            $prevData = [];
            $maxrate = 300;
            $grade = 'Need to improve';

            if (!empty($prevdailyActivities)) {
                $previous_month_total = $prevdailyActivities->previous_month_total;
                if ($previous_month_total > 501) {
                    $grade = "Excellent";
                } else if ($previous_month_total > 401) {
                    $grade = "Great";
                } else if ($previous_month_total > 300) {
                    $grade = "Good";
                } else {
                    $grade = "Need to improve";
                }
                $prevData['previous_rate_total'] = $previous_month_total;
                $prevData['max_rate'] = $maxrate;
                $prevData['grade'] = $grade;
            } else {
                $prevData['previous_rate_total'] = 0;
                $prevData['max_rate'] = $maxrate;
                $prevData['grade'] = $grade;
            }
            

            $dailyActivities = StudentDailyActivity::where('student_id', $request->student_id)
                                ->where('registration_id', $registration_id)
                                ->selectRaw('activity_id, activity_name, SUM(CASE WHEN date >= ? AND date <= ? THEN rate ELSE 0 END) as today_total', [now()->startOfDay(), now()->endOfDay()])
                                ->selectRaw('activity_id, activity_name, SUM(CASE WHEN date >= ? AND date <= ? THEN rate ELSE 0 END) as weekly_total', [now()->startOfWeek(), now()->endOfWeek()])
                                ->selectRaw('activity_id, activity_name, SUM(CASE WHEN date >= ? AND date <= ? THEN rate ELSE 0 END) as monthly_total', [now()->startOfMonth(), now()->endOfMonth()])
                                ->groupBy('activity_id', 'activity_name')
                                ->get(); 

            $response['previous_month_data'] = $prevData;
            $response['current_month_data']  = new ProgressResource($dailyActivities);
            if (count($dailyActivities) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => $response,
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => null
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function specialRequest(StudentSpecialRequest $request)
    {
        try {
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $registration_id = $studentData->registration_no;
            $guardianId = Auth::id();

            $specialRequestsQuery = StudentRequest::where('student_id', $request->student_id)
                ->where('registration_id', $registration_id)
                ->where('request_type', 1); // Special request

            if ($request->role == 'parent') {
                $specialRequestsQuery->where('request_by_parent', $guardianId);
            } else {
                $specialRequestsQuery->leftjoin('users', 'student_request.request_by_school', 'users.user_id')
                    ->where('users.role', $request->role == 'teacher' ? 3 : 1);
            }
            $specialRequests = $specialRequestsQuery->select('student_request.*');
            $specialRequests = $specialRequestsQuery->get();
                
            if (count($specialRequests) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new StudentRequestResource($specialRequests),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function saveSpecialRequest(SaveSpecialRequest $request)
    {
        DB::beginTransaction();
        try{
            $nowDate  = date('Y-m-d H:i:s', time());
            $guardian_id = Auth::id();
            $student_id  = $request->student_id;

            //To get registration number
            $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->where('student_registration.student_id',$student_id)
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

            $registration_no = $studentData->registration_no;
            $classId         = $studentData->new_class_id;
          
            $insertData = array(
                'request_type'      =>1,
                'class_id'          =>$classId,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_no,
                'request_by_parent' =>$guardian_id,
                'message'           =>$request->message,
                'date'              =>$request->date,
                'created_by'        =>$guardian_id,
                'updated_by'        =>$guardian_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            if ($request->has('photo') && $request->photo != '') {
                // Decode the base64 image
                $base64Image = $request->photo;
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
            
               // Get the file extension from the MIME type
                $mime = finfo_buffer(finfo_open(), $image, FILEINFO_MIME_TYPE);
                $extensions = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                ];
                $extension = $extensions[$mime] ?? 'jpg'; // Default to jpg if MIME type is unknown
                $image_name = time() . "." . $extension;
            
                // Save the image
                file_put_contents(public_path('assets/studentrequest_images') . '/' . $image_name, $image);

            
                // Update the photo field in the database
                $insertData['photo'] = $image_name;
            }
            $result=StudentRequest::insert($insertData);
                        
            if($result){  
                /*if ($image_name != '') {
                    $image->move(public_path('assets/studentrequest_images'), $image_name);
                }    */      
                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'Student Request saved Successfully.'
                ]); 
            }else{
                return response()->json([
                    'status'  => 422,
                    'message' => 'Student Request saved Fail.'
                ],422); 
            }      

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        } 
    }

    public function saveSpecialRequestComment(SaveSpecialRequestComment $request)
    {
        DB::beginTransaction();
        try{
            $nowDate  = date('Y-m-d H:i:s', time());
            $guardian_id = Auth::id();
                      
            $insertData = array(
                'student_request_id' =>$request->special_request_id,
                'comment_by_parent'  =>$guardian_id,
                'comment'            =>$request->comment,
                'created_by'         =>$guardian_id,
                'updated_by'         =>$guardian_id,
                'created_at'         =>$nowDate,
                'updated_at'         =>$nowDate
            );
            $result=DB::table('student_request_comment')->insert($insertData);
                        
            if($result){     
                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'Student Request Comment saved Successfully.'
                ]); 
            }else{
                return response()->json([
                    'status'  => 422,
                    'message' => 'Student Request Comment saved Fail.'
                ],422); 
            }      

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        } 
    }

    public function health(StudentProfileRequest $request)
    {
        try {
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $registration_id = $studentData->registration_no;
            $guardianId = Auth::id();

            $healthRequestsQuery = StudentRequest::where('student_id', $request->student_id)
                ->where('registration_id', $registration_id)
                ->where('request_type',2); //health request

            if ($request->role == 'parent') {
                $healthRequestsQuery->where('request_by_parent', $guardianId);
            } else {
                $healthRequestsQuery->leftjoin('users', 'student_request.request_by_school', 'users.user_id')
                    ->where('user.role', $request->role == 'teacher' ? 3 : 1);
            }

            $healthRequests = $healthRequestsQuery->get();
                
            if (count($healthRequests) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new StudentRequestResource($healthRequests),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function news(StudentProfileRequest $request)
    {
        try {
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $registration_id = $studentData->registration_no;

            $news = News::where('student_id', $request->student_id)
                                ->where('registration_id', $registration_id)
                                ->get();   

                
            if (count($news) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new NewsResource($news),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function saveNewsComment(SaveNewsCommentRequest $request)
    {
        DB::beginTransaction();
        try{
            $nowDate  = date('Y-m-d H:i:s', time());
            $guardian_id = Auth::id();
                      
            $insertData = array(
                'news_id'            =>$request->news_id,
                'comment_by_parent'  =>$guardian_id,
                'comment'            =>$request->comment,
                'created_by'         =>$guardian_id,
                'updated_by'         =>$guardian_id,
                'created_at'         =>$nowDate,
                'updated_at'         =>$nowDate
            );
            $result=DB::table('news_comment')->insert($insertData);
                        
            if($result){     
                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'News Comment saved Successfully.'
                ]); 
            }else{
                return response()->json([
                    'status'  => 422,
                    'message' => 'News Comment saved Fail.'
                ],422); 
            }      

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        } 
    }

    public function certificates(StudentProfileRequest $request)
    {
        try {
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }
            $registration_id = $studentData->registration_no;

            $certificates = Certificate::where('student_id', $request->student_id)
                                ->where('registration_id', $registration_id)
                                ->get();   

                
            if (count($certificates) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new CertificateResource($certificates),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function pocketMoneyRequest(StudentProfileRequest $request)
    {
        try {
            $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->where('student_registration.student_id',$request->student_id)
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => null
                ]);
            }
            $card_id = $studentData->card_id;

            $wallet_data = Wallet::where('student_id', $request->student_id)
                                ->where('card_id', $card_id)
                                ->first();   

                
            if (!empty($wallet_data)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new PocketMoneyRequestResource($wallet_data),
                ]);
            } else {
                $data = [];
                $data['id']                = null;
                $data['card_id']           = $card_id;
                $data['student_id']        = $request->student_id;
                $data['total_amount']      = '0';
                return response()->json([
                    'status'  =>  200,
                    'message' => 'Data not found !',
                    'data'    => $data
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function pocketMoneySave(PocketMoneyRequest $request)
    {
        DB::beginTransaction();
        try{
            $nowDate  = date('Y-m-d H:i:s', time());
            $guardian_id = Auth::id();
            $student_id  = $request->student_id;

            //To get registration number
            $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->where('student_registration.student_id',$student_id)
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
                ]);
            }

            $registration_no = $studentData->registration_no;
            $card_id         = $studentData->card_id;
            //insert guardian pocket money table table
            $insertData= array(
                'student_id'        =>$student_id,
                'guardian_id'       =>$guardian_id,
                'card_id'           =>$card_id,
                'status'            =>0,
                'amount'            =>$request->amount,
                'created_by'        =>$guardian_id,
                'updated_by'        =>$guardian_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $pocketMoney = DB::table('guardian_pocket_money')->insertGetId($insertData);
            if ($pocketMoney) {
                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'Pocket Money saved Successfully.'
                ]); 
            } else {
                return response()->json([
                    'status'  => 422,
                    'message' => 'Pocket Money saved Fail.'
                ],422); 
            }        

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        } 
    }

    public function billPayment(BillPaymentRequest $request)
    {
        DB::beginTransaction();
        try{
            $nowDate  = date('Y-m-d H:i:s', time());
            $guardian_id = Auth::id();
            $invoiceId  = $request->invoice_id;
            $inoviceData = Invoice::where('invoice_id',$invoiceId)->first();

            $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->where('student_registration.student_id',$inoviceData->student_id)
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 422,
                    'message' => 'Paid Fail.'
                ],422); 
            }
           // $guardian_id    = $studentData->guardian_id;
            $card_id         = $studentData->card_id;

            //to substract the money in wallet
            $checkCurrent = Wallet::where('card_id',$card_id)->first();
            $totalAmount = 0;
            if (!empty($checkCurrent)) {
                $totalAmount =  $checkCurrent->total_amount - $inoviceData->net_total;
                if ($totalAmount < 0) {
                    return response()->json([
                        'status'  => 422,
                        'message' => 'Insufficutent Amount.'
                    ],422); 
                }
                $deletCurrent = Wallet::where('id',$checkCurrent->id)->delete();
            } else {
                return response()->json([
                    'status'  => 422,
                    'message' => 'Insufficutent Amount.'
                ],422); 
            }
            
            $walletinsertData = array(
                'card_id'           =>$card_id,
                'student_id'        =>$inoviceData->student_id,
                'amount'            =>$inoviceData->net_total,
                'total_amount'      =>$totalAmount,
                'created_by'        =>$guardian_id,
                'updated_by'        =>$guardian_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $wallet_id=Wallet::insertGetId($walletinsertData);
            if ($wallet_id) {
                $wallethistoryinsertData = array(
                    'card_id'           =>$card_id,
                    'student_id'        =>$inoviceData->student_id,
                    'status'            =>'2', //Out status
                    'status_id'         =>$invoiceId,
                    'amount'            =>$inoviceData->net_total,
                    'created_by'        =>$guardian_id,
                    'updated_by'        =>$guardian_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $wallethistoryresult=WalletHistory::insert($wallethistoryinsertData);
            }

            $paidData = array(
                'invoice_id'        => $invoiceId,
                'student_id'        => $inoviceData->student_id,
                'paid_date'         => $nowDate,
                'paid_type'         => $inoviceData->payment_type,
                'remark'            => 'guardian paid',
                'created_by'        => $guardian_id,
                'updated_by'        => $guardian_id,
                'created_at'        => $nowDate,
                'updated_at'        => $nowDate
            );

            $result = Payment::insert($paidData);

            if ($result) {
                //change invoice paid status
                $updateData = array(
                    'paid_status'        => 1,
                );
                $result = Invoice::where('invoice_id', $invoiceId)->update($updateData);                

                //Send Message
                $updatedInvoice = Invoice::where('invoice_id',$invoiceId)->first();
                $msgdata = [];
                $msgdata['student_id'] = $request->paid_studentid;
                $msgdata['title']      = 'Paid Invoice';
                $msgdata['description']= 'Payment Successful with the Date (from '.
                date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).' to '.date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).
                ') - Amount - '.$updatedInvoice->net_total;
                $msgdata['remark']   ='';
                $msg = $this->regRepository->sendMessage($msgdata);

                //send noti
                $data['receiver_id'] = $guardian_id;
                $data['title'] = "Paid Invoice";
                $data['body']  = 'Payment Successful with the Date (from '.
                date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).' to '.date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).
                ') - Amount - '.$updatedInvoice->net_total;
                $data['source'] = "Payment";
                $data['source_id'] = $invoiceId;

                $result = $this->notificationService->sendNotification($data);              


                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'Paid Successfully.'
                ]); 
            } else {
                return response()->json([
                    'status'  => 422,
                    'message' => 'Paid Fail.'
                ],422); 
            }    

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        } 
    }

    public function studentFerryTracking(StudentProfileRequest $request){
        try {
            $ferryData = FerryStudent::leftJoin('school_bus_track_detail', 'school_bus_track_detail.ferry_student_id', 'ferry_student.id')
                                ->leftJoin('school_bus_track', 'school_bus_track.id', 'school_bus_track_detail.school_bus_track_id')
                                ->leftJoin('driver_info','school_bus_track.driver_id','driver_info.driver_id')
                                ->where('ferry_student.student_id',$request->student_id)
                                ->select('driver_info.*','school_bus_track.track_no as track_no',
                                'school_bus_track.car_no as car_no','ferry_student.student_id as student_id')
                                ->first();
              
                
            if (!empty($ferryData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new FerryTrackingResource($ferryData),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => null
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function checkNewSpecialRequest(StudentProfileRequest $request)
    {
        try {
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    =>  [
                        'has_new_comment' => false
                    ]
                ]);
            }
            $registration_id = $studentData->registration_no;

            $guardianId = Auth::id();


            $res = StudentRequest::leftJoin('student_request_comment', 'student_request.id', '=', 'student_request_comment.student_request_id')
                ->selectRaw('student_request.*, IF(MAX(student_request_comment.created_at) IS NOT NULL AND (student_request.guardian_last_read_at IS NULL OR (MAX(student_request_comment.created_at) > student_request.guardian_last_read_at)), 1, 0) AS has_new_comment')
                ->where('student_id', $request->student_id)
                ->where('registration_id', $registration_id)
                ->where('request_type', 1)
                ->groupBy('student_request.id')
                ->orderByRaw('MAX(student_request_comment.created_at) DESC')
                ->get();
                
            if (count($res) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new CheckNewSpecialRequestResource($res),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => [
                        'has_new_comment' => false
                    ]
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function checkNewSpecialRequestRole(StudentSpecialRequest $request)
    {
        try {
            $studentData = StudentRegistration::where('student_id',$request->student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*')
                        ->latest('student_registration.created_at')
                        ->first();
            if (empty($studentData)) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    =>  [
                        'has_new_comment' => false
                    ]
                ]);
            }
            $registration_id = $studentData->registration_no;

            $guardianId = Auth::id();


            $res = StudentRequest::leftJoin('student_request_comment', 'student_request.id', '=', 'student_request_comment.student_request_id')
                ->selectRaw('student_request.*, IF(MAX(student_request_comment.created_at) IS NOT NULL AND (student_request.guardian_last_read_at IS NULL OR (MAX(student_request_comment.created_at) > student_request.guardian_last_read_at)), 1, 0) AS has_new_comment')
                ->where('student_id', $request->student_id)
                ->where('registration_id', $registration_id)
                ->where('request_type', 1);
            if ($request->role == 'parent') {
                $res->where('request_by_parent', $guardianId);
            } else {
                $res->leftjoin('users', 'student_request.request_by_school', 'users.user_id')
                    ->where('users.role', $request->role == 'teacher' ? 3 : 1);
            }
            $res= $res->groupBy('student_request.id')
                ->orderByRaw('MAX(student_request_comment.created_at) DESC')
                ->get();
                
                
            if (count($res) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new CheckNewSpecialRequestResource($res),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => [
                        'has_new_comment' => false
                    ]
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function saveSpecialRequestLastReadAt(SaveSpecialRequestLastReadAt $request)
    {
        $guardianId = Auth::id();
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try {
            $res = StudentRequest::where('id', $request->special_request_id)
                    ->update(['guardian_last_read_at' => $nowDate, 'updated_at' => $nowDate]);
                
            if ($res) {
                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'Last read at time saved'
                ]);   
            } else {
                DB::rollback();
                return response()->json([
                    'status'  => '500',
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);  
            }
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
}

