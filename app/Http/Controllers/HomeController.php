<?php

namespace App\Http\Controllers;

use App\Models\DriverInfo;
use App\Models\StudentInfo;
use App\Models\TeacherInfo;
use App\Models\Payment;
use App\Models\Expense;
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
        $studentCount = StudentInfo::count();;
        $teacherCount = TeacherInfo::count();
        $driverCount  = DriverInfo::count();

        // Get the current date
        $currentDate = date('Y-m-d');
        // Get the first day of the current month
        $firstDayOfMonth = date('Y-m-01', strtotime($currentDate));
        // Get the last day of the current month
        $lastDayOfMonth = date('Y-m-t', strtotime($currentDate));

        $income       = Payment::leftjoin('invoice','invoice.invoice_id','payment.invoice_id')
                        ->where('paid_date','>=',$firstDayOfMonth)
                        ->where('paid_date','<=',$lastDayOfMonth)
                        ->where('paid_status','1')
                        ->select(DB::raw('SUM(net_total) as total_amount'))
                        ->get()
                        ->pluck('total_amount')
                        ->first();
       
        $expense      = Expense::select(DB::raw('SUM(amount) as expense_amount'))
                        ->where('expense_date','>=',$firstDayOfMonth)
                        ->where('expense_date','<=',$lastDayOfMonth)
                        ->get()
                        ->pluck('expense_amount')
                        ->first();

        $event_list = Event::select('event.*')
                    //->whereDate(DB::raw('DATE_FORMAT(event_from_date, "%Y-%m-%d")'), '>=', $currentDate)
                    ->whereDate(DB::raw('DATE_FORMAT(event_to_date, "%Y-%m-%d")'), '>=', $currentDate)
                    ->get();

        $grade      = Grade::all();
        $grade_list[0]='All';
        foreach($grade as $g) {
            $grade_list[$g->id] = $g->name;
        } 
        
        return view('home',[
            'student_count'=>$studentCount,
            'teacher_count' => $teacherCount,
            'driver_count' => $driverCount,
            'income'       => $income,
            'expense'      => $expense,
            'event_list'   => $event_list,
            'grade'        => $grade_list
        ]);
    }
}
