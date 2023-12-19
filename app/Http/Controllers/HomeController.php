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
                            ->select('class_setup.id as class_id','grade.name as grade')
                            ->get()->toArray();
        $classList = array_column($classWithGrade,'class_id');

        $classGrade = [];
        foreach ($classWithGrade as $val) {
            $classGrade[$val['class_id']] = $val['grade'];
        }

        $studentCountWithClass = StudentRegistration::whereIn('new_class_id', $classList)
            ->select('new_class_id',\DB::raw('count(*) as student_count'))
            ->groupBy('new_class_id')
            ->get();
        
        $studentClass = [];
        $studentTotalCount = 0;
        
        foreach ($studentCountWithClass as $stu) {
            $stdCls = []; // Create a new array for each iteration
        
            $stdCls['class_id'] = $stu->new_class_id;
            $stdCls['count']    = $stu->student_count;
            $studentTotalCount += $stu->student_count;
            
            $studentClass[] = $stdCls;
        }
        
        $examMarksWithClass = ExamMarks::whereIn('class_id', $classList)
                        ->select('class_id', \DB::raw('count(*) as total_count'))
                        ->selectRaw('SUM(CASE WHEN mark >= 50 THEN 1 ELSE 0 END) as pass_count')
                        ->selectRaw('SUM(CASE WHEN mark < 50 THEN 1 ELSE 0 END) as fail_count')
                        ->groupBy('class_id')
                        ->get(['class_id', 'total_count', 'pass_count', 'fail_count']);
        $examMarkClass = [];
        foreach ($examMarksWithClass as $exam) {
            $examMr['class_id'] = $exam->class_id;
            $examMr['pass'] = $exam->pass_count;
            $examMr['fail'] = $exam->pass_count;
            $examMarkClass[] = $examMr;
        }

        $waitingWithClass = WaitingRegistration::join('grade', 'waiting_registration.grade_id', '=', 'grade.id')
                            ->select('grade_id', 'grade.name as grade_name', \DB::raw('count(*) as waiting_count'))
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

        return view('home',[
            'class_grade'   => $classGrade,
            'student_count' => $studentClass,
            'student_totalcount' => $studentTotalCount,
            'exam_count'    => $examMarkClass,
            'waiting_count' => $waitingClass,
            'waiting_totalcount' => $waitingCount,
            'event_list'    => $event_list
        ]);
    }
}
