<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportStudentAttendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentAttendanceReportController extends Controller
{
    public function studentAttendanceReport(Request $request) 
    {

        $attendance = array(
            '2'=>'Leave',
            '1'=>'Present',
            '0'=>'Absent'
        );

        $approveStatus = array(
            '0'=>'Pending',
            '1'=>'Confirm',
            '2'=>'Reject'
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
                $resArr['Status']         = $attendance[$res->status];
                $resArr['Teacher Remark'] = $attendance[$res->teacher_remark];
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
            'attendance'  => $attendance,
            'approveStatus'=>$approveStatus
        ]);
    }

    public function studentAttendanceApprove(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'attendance_id'            => 'required',
            'leave_approvestatus'      => 'required'
        ]);

        DB::beginTransaction();
        try {

            //change invoice paid status
            $updateData = array(
                'status'            => $request->leave_approvestatus,
                'teacher_remark'     =>$request->leave_teacherremark
            );
            $result = DB::table('student_attendance')->where('id', $request->attendance_id)->update($updateData);

            if ($result) {              
                DB::commit();
                return redirect(url('admin/reporting/student_attendance_report'))->with('success', 'Successfully Approve!');
            } else {
                return redirect()->back()->with('danger', 'Approve Fail!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Approve Fail !');
        }
    }
}
