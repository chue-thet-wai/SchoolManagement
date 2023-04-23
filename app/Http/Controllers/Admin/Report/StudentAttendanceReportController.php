<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportStudentAttendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentAttendanceReportController extends Controller
{
    public function studentAttendanceReport(Request $request) 
    {

        $attendance = array(
            '1'=>'Present',
            '0'=>'Absent'
        );

        $res = DB::table('student_attendance')->join('student_info','student_info.student_id','=','student_attendance.student_id');
        if ($request['action'] == 'search') {
            if (request()->has('student_attfrom') && request()->input('student_attfrom') != '') {
                $res->where('attendance_date','>=', request()->input('student_attfrom'));
            }
            if (request()->has('student_attTo') && request()->input('student_attTo') != '') {
                $res->where('attendance_date','<=', request()->input('student_attTo'));
            }
            if (request()->has('student_name') && request()->input('student_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('student_name') . '%');
            }
        } else if ($request['action'] == 'export') {
            if (request()->has('student_attfrom') && request()->input('student_attfrom') != '') {
                $res->where('attendance_date','>=', request()->input('student_attfrom'));
            }
            if (request()->has('student_attTo') && request()->input('student_attTo') != '') {
                $res->where('attendance_date','<=', request()->input('student_attTo'));
            }
            if (request()->has('student_name') && request()->input('student_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('student_name') . '%');
            }

            $res->select('student_attendance.*','student_info.name');
            $calcelRes = $res->get();

            $cancelData = [];
            foreach ($calcelRes as $res) {
                $resArr['student_id']     = $res->student_id;
                $resArr['name']           = $res->name;
                $resArr['attendance_date']= date('Y-m-d',strtotime($res->attendance_date));
                $resArr['attendance']     = $attendance[$res->attendance_status];
                $resArr['remark']         = $res->remark;
                $cancelData[] = $resArr;
            }
    
            return Excel::download(new ExportStudentAttendance($cancelData), 'student_attendance_export.csv');
        } else {
            request()->merge([
                'student_attfrom' => null,
                'student_attTo'   => null,
                'student_name'    => null,
            ]);
        }       
        $res->select('student_attendance.*','student_info.name');
        $res = $res->paginate(20);
        return view('admin.report.studentattreport',[
            'list_result' => $res,
            'attendance'  => $attendance
        ]);
    }
}
