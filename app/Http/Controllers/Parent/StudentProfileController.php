<?php

namespace App\Http\Controllers\Parent;

use DateTime;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StudentRegistration;
use App\Models\AcademicYear;
use App\Models\Homework;
use App\Models\Event;
use App\Models\ExamMarks;
use App\Models\ExamTerms;
use App\Models\Message;
use App\Models\Invoice;
use App\Models\StudentAttendance;
use App\Models\ExamTermsDetail;
use App\Models\Schedules;

class StudentProfileController extends Controller
{
    private $academic_year_id;
    public function __construct()
    {
        $currentDate = date("Y-m-d");
        //current academic year
        $currentAcademic = AcademicYear::where('start_date', '<=', $currentDate)
                        ->where('end_date', '>=', $currentDate)
                        ->first();
        $this->academic_year_id = $currentAcademic ? $currentAcademic->id : null;
    }

    public function parentStudentProfile($student_id) {
        return view('parent.parent_studentprofile',[
            'student_id' => $student_id
        ]);        
    }

    public function profileExamDate($student_id) {
        $currentDate = new DateTime();
        $studentData = StudentRegistration::where('student_id',$student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->select('class_setup.grade_id')
                        ->latest('student_registration.created_at')
                        ->first();
        $grade_id = $studentData->grade_id;

        $examTerms = ExamTerms::where('grade_id',$grade_id)
                        ->where('academic_year_id',$this->academic_year_id)
                        ->get()->toArray();
        $examTermsIds = array_column($examTerms,'id');
        $examTermsDetail = ExamTermsDetail::leftjoin('subject','subject.id','exam_terms_detail.subject_id')
                            ->whereIn('exam_terms_id',$examTermsIds)
                            ->where('exam_date','>',$currentDate)
                            ->select('exam_terms_detail.*','subject.name as subject_name')
                            ->get();

        $examTermsDetailArr = [];
        foreach ($examTermsDetail as $detail) {
            $detailArr=[];
            $endDate = new DateTime($detail->exam_date);
            $interval = $currentDate->diff($endDate);
            $daysLeft = $interval->days;
            $detailArr['subject_name'] = $detail->subject_name;
            $detailArr['exam_date']    = $daysLeft;
            $detailArr['subject_image']= $detail->subject_image;
            $examTermsDetailArr[]      = $detailArr;
        }
        return view('parent.parent_examdate',[
            'student_id'        => $student_id,
            'exam_terms_detail' => $examTermsDetailArr
        ]);
    }

    public function profileHomework($student_id) {
        $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$student_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name')
                        ->latest('student_registration.created_at')
                        ->first();
        $class_id = $studentData->new_class_id;
       
        $homework = Homework::leftjoin('subject','subject.id','homework.subject_id')
                        ->where('class_id',$class_id)
                        ->where('academic_year_id',$this->academic_year_id)
                        ->select('homework.*','subject.name as subject_name')
                        ->get();

        return view('parent.parent_homework',[
            'homework'     => $homework,
            'student_data' => $studentData,
            'student_id'   => $student_id
        ]);
    }
    public function profileAttendance($student_id) {
        return view('parent.parent_attendance',[
            'student_id' => $student_id
        ]);
    }

    
    public function leaveRequest($student_id) {
        $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$student_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name')
                        ->latest('student_registration.created_at')
                        ->first();

        return view('parent.parent_leaverequest',[
            'student_id'   => $student_id,
            'student_data' => $studentData
        ]);        
    }

    public function leaveRequestSubmit(Request $request,$student_id){
        DB::beginTransaction();
        try{
            $nowDate  = date('Y-m-d H:i:s', time());
            $guardian_id = session()->get('guardian_id');
            $start_date = Carbon::parse($request->from_date);
            $end_date   = Carbon::parse($request->to_date);

            //To get registration number
            $studentData = StudentRegistration::where('student_id',$student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->latest('student_registration.created_at')
                        ->first();

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
                        return redirect(url('parent/student_profile/'.$student_id.'/attendance'))->with('success','Leave Submit Successfully!');
                    }else{
                        return redirect()->back()->with('danger','Leave Submit Fail !');
                    }
                }
            } else {
                return redirect()->back()->with('danger','Leave Submit Fail !');
            }            

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Leave Submit Fail !');
        } 
    }

    public function profileExamResult($student_id) {
        $studentData = StudentRegistration::where('student_id',$student_id)
                        ->join('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('class_setup.grade_id')
                        ->latest('student_registration.created_at')
                        ->first();
        $grade_id = $studentData->grade_id;

        $examTerms = ExamTerms::where('academic_year_id',$this->academic_year_id)
                        ->where('grade_id',$grade_id)
                        ->get();
        return view('parent.parent_examresult',[
            'exam_terms' => $examTerms,
            'student_id' => $student_id
        ]);
    }

    public function profileExamResultDetail($student_id,$exam_terms_id) {
        $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$student_id)
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name')
                        ->latest('student_registration.created_at')
                        ->first();
        $class_id = $studentData->new_class_id;

        $examTerm = ExamTerms::where('id',$exam_terms_id)->first();

        $passMinMarks = 50;

        $examMarks = ExamMarks::join('subject','subject.id','exam_marks.subject_id')
                        ->where('student_id',$student_id)
                        ->where('class_id',$class_id)
                        ->where('exam_terms_id',$exam_terms_id)
                        ->select('exam_marks.*','subject.name as subject_name', 
                            DB::raw('CASE WHEN exam_marks.mark >= ' . $passMinMarks . ' THEN "Pass" ELSE "Fail" END as result'))
                        ->get();
        return view('parent.parent_examresult_detail',[
            'exam_marks' => $examMarks,
            'exam_term' => $examTerm,
            'student_id' => $student_id
        ]);
    }
    public function profileCurriculum($student_id) {
        $currentDayNumber = date('N');
        return redirect(url('parent/student_profile/'.$student_id.'/curriculum/'.$currentDayNumber));
    }
    public function profileCurriculumDay($student_id,$day) {
        $currentDate = new DateTime();
        // Check if it's the current day
        $isCurrentDay = ($currentDate->format('N') == $day);

        $currentDate->modify('this week');
        $daysDifference = $currentDate->format('N') - $day;
        $currentDate->modify("-$daysDifference days");
        $dateName   = $currentDate->format('j F Y');
        $dayName    = $currentDate->format('l');
        $dateNumber = $currentDate->format('N');

        $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$student_id)
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name')
                        ->latest('student_registration.created_at')
                        ->first();
        $class_id = $studentData->new_class_id;

        $schedule = Schedules::leftjoin('subject','subject.id','schedules.subject_id')
                        ->where('class_id',$class_id)
                        ->where('weekdays',$day)
                        ->select('schedules.*','subject.name as subject_name')
                        ->orderBy('start_time')
                        ->get();

        return view('parent.parent_curriculum',[
            'student_id'  => $student_id,
            'day'         => $day,
            'dateName'    => $dateName,
            'dayName'     => $dayName,
            'isCurrentDay'=> $isCurrentDay,
            'dateNumber'  => $dateNumber,
            'schedules'   => $schedule
        ]);
    }

    public function profileMessages($student_id) {
        $messages = Message::where('student_id',$student_id)->get();
        $message_arr = [];
        foreach ($messages as $m) {
            $carbonDate = Carbon::parse($m->created_at);
            $day = $carbonDate->format('d');
            $monthAbbreviation = $carbonDate->format('M');

            $one_message = [];
            $one_message['title']       = $m->title;
            $one_message['description'] = $m->description;
            $one_message['remark']      = $m->remark;
            $one_message['date']        = $day.' '.$monthAbbreviation;
            $message_arr[] = $one_message;
        }
        return view('parent.parent_studentmessage',[
            'messages'       =>$message_arr,
            'student_id'     =>$student_id
        ]);   
    }

    public function profileEvent($student_id) {
        $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$student_id)
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name',
                        'class_setup.grade_id as grade_id')
                        ->latest('student_registration.created_at')
                        ->first();
        $grade_id = $studentData->grade_id;

        $event = Event::where('grade_id',$grade_id)
                    ->where('academic_year_id',$this->academic_year_id)
                    ->get();
        
        return view('parent.parent_event',[
            'student_id' => $student_id,
            'event'      => $event
        ]);        
    }

    public function profileBilling($student_id) 
    {
        $studentData = StudentRegistration::join('student_info','student_info.student_id','student_registration.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->where('student_registration.student_id',$student_id)
                        ->where('class_setup.academic_year_id',$this->academic_year_id)
                        ->select('student_registration.*','student_info.*','class_setup.name as class_name')
                        ->latest('student_registration.created_at')
                        ->first();

        $unpaidinvoices = Invoice::where('student_id',$student_id)
                            ->where('paid_status',0)->get();
        $paidinvoices = Invoice::where('student_id',$student_id)
                            ->where('paid_status',1)->get();

        return view('parent.parent_billing',[
            'student_data'     => $studentData,
            'unpaid_invoices'  => $unpaidinvoices,
            'paid_invoices'    => $paidinvoices,
            'student_id'       => $student_id
        ]);        
    }
    
}
