<?php

namespace App\Http\Controllers;

use App\Models\ClassSetup;
use App\Models\StudentRegistration;
use App\Models\WaitingRegistration;
use App\Models\Payment;
use App\Models\ExamMarks;
use App\Models\Event;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currentDate = date('Y-m-d');

        $classWithGrade = ClassSetup::join('grade','class_setup.grade_id','grade.id')
                            ->select('class_setup.id as class_id','grade.id as grade_id')
                            ->get()->toArray();
        $classList = array_column($classWithGrade,'class_id');

        $classGrade = [];
        foreach ($classWithGrade as $val) {
            $classGrade[$val['class_id']] = $val['grade_id'];
        }

        $studentCountWithClass = StudentRegistration::whereIn('new_class_id', $classList)
            ->select('new_class_id',\Illuminate\Support\Facades\DB::raw('count(*) as student_count'))
            ->groupBy('new_class_id')
            ->get();
        
        $studentClass = [];
        $studentTotalCount = 0;
        
        foreach ($studentCountWithClass as $stu) {
            if (array_key_exists($classGrade[$stu->new_class_id],$studentClass)) {
                $studentClass[$classGrade[$stu->new_class_id]] += $stu->student_count; 
            } else {
                $studentClass[$classGrade[$stu->new_class_id]] = $stu->student_count; 
            }
                      
            $studentTotalCount += $stu->student_count;
            
        }
        
        $examMarksWithClass = ExamMarks::whereIn('class_id', $classList)
                        ->select('class_id', \Illuminate\Support\Facades\DB::raw('count(*) as total_count'))
                        ->selectRaw('SUM(CASE WHEN mark >= 50 THEN 1 ELSE 0 END) as pass_count')
                        ->selectRaw('SUM(CASE WHEN mark < 50 THEN 1 ELSE 0 END) as fail_count')
                        ->groupBy('class_id')
                        ->get(['class_id', 'total_count', 'pass_count', 'fail_count']);
        $examMarkClass = [];
        foreach ($examMarksWithClass as $exam) {
            if (array_key_exists($classGrade[$exam->class_id],$examMarkClass)) {
                $examMarkClass[$classGrade[$exam->class_id]]['pass'] += $exam->pass_count; 
                $examMarkClass[$classGrade[$exam->class_id]]['fail'] += $exam->fail_count;

            } else {
                $examMarkClass[$classGrade[$exam->class_id]]['pass'] = $exam->pass_count; 
                $examMarkClass[$classGrade[$exam->class_id]]['fail'] = $exam->fail_count;
            }
        }

        $waitingWithClass = WaitingRegistration::join('grade', 'waiting_registration.grade_id', '=', 'grade.id')
                            ->select('grade_id', 'grade.name as grade_name', \Illuminate\Support\Facades\DB::raw('count(*) as waiting_count'))
                            ->groupBy('grade_id', 'grade.name')
                            ->get();
        $waitingClass = [];
        $waitingCount = 0;
        foreach ($waitingWithClass as $waiting) {
            $waitingClas['grade_id'] = $waiting->grade_id;
            $waitingClas['grade_name'] = $waiting->grade_name;
            $waitingClas['count'] = $waiting->waiting_count;
            $waitingClass[] = $waitingClas;
            $waitingCount += $waiting->waiting_count;
        }

        $event_list = Event::select('event.*')
                //->whereDate(DB::raw('DATE_FORMAT(event_from_date, "%Y-%m-%d")'), '>=', $currentDate)
                ->whereDate(DB::raw('DATE_FORMAT(event_to_date, "%Y-%m-%d")'), '>=', $currentDate)
                ->get();

        $gradeList = Grade::all();
        $grade_list=[];
        foreach($gradeList as $g) {
            $grade_list[$g->id] = $g->name;
        } 

        return view('home',[
            'grade_list'    => $grade_list,
            'student_count' => $studentClass,
            'student_totalcount' => $studentTotalCount,
            'exam_count'    => $examMarkClass,
            'waiting_count' => $waitingClass,
            'waiting_totalcount' => $waitingCount,
            'event_list'    => $event_list
        ]);
    }

    public function getDashboardPayment(Request $request)
    {
        $additionFee = DB::table('additional_fee')->select('id','name')->get();
       
        $paymentResult = Payment::join('invoice', 'invoice.invoice_id', '=', 'payment.invoice_id')
                        ->whereYear('payment.paid_date', '=', date('Y', strtotime($request->payment_date)))
                        ->whereMonth('payment.paid_date', '=', date('m', strtotime($request->payment_date)))
                        ->select('payment.invoice_id', 'invoice.grade_level_fee')
                        ->get();
        
        $invoice_list = array();

        $paymentData = [];
        $payment['name'] = "Registration Fee";
        $regAmt = 0;
        foreach ($paymentResult as $pay) {
            array_push($invoice_list,$pay->invoice_id);
            $regAmt += $pay->grade_level_fee;
        }
        
        if ($regAmt > 0) {
            $payment['amount'] = $regAmt;
            $paymentData[] = $payment;
        }       
       
        foreach ($additionFee as $addFee) {
            $totalAddFee = DB::table('payment_additional_fee')
                ->select(\Illuminate\Support\Facades\DB::raw('sum(additional_amount) as additional_amount'))
                ->groupBy('additional_fee_id')
                ->where('additional_fee_id',$addFee->id)
                ->whereIn('invoice_id',$invoice_list)
                ->get();

            $totalAmount = $totalAddFee->sum('additional_amount');
            if ($totalAmount > 0) {
                $payment['name'] = $addFee->name;
                $payment['amount'] = $totalAmount;
                $paymentData[] = $payment;
            }

        }

        return response()->json(array(
            'msg'             => 'found',
            'payment_data'    => $paymentData,
        ), 200);
    }

    public function getEvent(Request $request){
        /*$data = Event::whereDate('start', '>=', $request->start)
                    ->whereDate('end',   '<=', $request->end)
                    ->get(['id', 'title', 'start', 'end']);*/
        $data = Event::get(['id', 'title', 'start', 'end']);          
        log::info('event list');
        log::info($data);

        return response()->json($data);
    }
}
