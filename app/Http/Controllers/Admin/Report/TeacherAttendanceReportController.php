<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportTeacherAttendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TeacherAttendanceReportController extends Controller
{
    public function teacherAttendanceReport(Request $request) 
    {

        $attendance = array(
            '2'=>'Leave',
            '1'=>'Present',
            '0'=>'Absent'
        );

        $res = DB::table('teacher_attendance')->join('teacher_info','teacher_info.user_id','=','teacher_attendance.user_id');
        if ($request['action'] == 'search') {
            if (request()->has('teacher_attfrom') && request()->input('teacher_attfrom') != '') {
                $res->where('attendance_date','>=', request()->input('teacher_attfrom'));
            }
            if (request()->has('teacher_attTo') && request()->input('teacher_attTo') != '') {
                $res->where('attendance_date','<=', request()->input('teacher_attTo'));
            }
            if (request()->has('teacher_name') && request()->input('teacher_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('teacher_name') . '%');
            }
        } else if ($request['action'] == 'export') {
            if (request()->has('teacher_attfrom') && request()->input('teacher_attfrom') != '') {
                $res->where('attendance_date','>=', request()->input('teacher_attfrom'));
            }
            if (request()->has('teacher_attTo') && request()->input('teacher_attTo') != '') {
                $res->where('attendance_date','<=', request()->input('teacher_attTo'));
            }
            if (request()->has('teacher_name') && request()->input('teacher_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('teacher_name') . '%');
            }

            $res->select('teacher_attendance.*','teacher_info.name');
            $calcelRes = $res->get();

            $cancelData = [];
            foreach ($calcelRes as $res) {
                $resArr['user_id']        = $res->user_id;
                $resArr['name']           = $res->name;
                $resArr['attendance_date']= date('Y-m-d',strtotime($res->attendance_date));
                $resArr['attendance']     = $attendance[$res->attendance_status];
                $resArr['remark']         = $res->remark;
                $cancelData[] = $resArr;
            }
    
            return Excel::download(new ExportTeacherAttendance($cancelData), 'teacher_attendance_export.csv');
        } else {
            request()->merge([
                'teacher_attfrom' => null,
                'teacher_attTo'   => null,
                'teacher_name'    => null,
            ]);
        }       
        $res->select('teacher_attendance.*','teacher_info.name');
        $res = $res->paginate(20);
        return view('admin.report.teacherattreport',[
            'list_result' => $res,
            'attendance'  => $attendance
        ]);
    }
}
