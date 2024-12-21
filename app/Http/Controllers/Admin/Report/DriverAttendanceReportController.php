<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportDriverAttendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DriverAttendanceReportController extends Controller
{
    public function driverAttendanceReport(Request $request) 
    {
        $res = DB::table('driver_attendance')->join('driver_info','driver_info.driver_id','=','driver_attendance.driver_id');
        if ($request['action'] == 'search') {
            if (request()->has('driver_attfrom') && request()->input('driver_attfrom') != '') {
                $res->where('attendance_date','>=', request()->input('driver_attfrom'));
            }
            if (request()->has('driver_attTo') && request()->input('driver_attTo') != '') {
                $res->where('attendance_date','<=', request()->input('driver_attTo'));
            }
            if (request()->has('driver_name') && request()->input('driver_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('driver_name') . '%');
            }
        } else if ($request['action'] == 'export') {
            if (request()->has('driver_attfrom') && request()->input('driver_attfrom') != '') {
                $res->where('attendance_date','>=', request()->input('driver_attfrom'));
            }
            if (request()->has('driver_attTo') && request()->input('driver_attTo') != '') {
                $res->where('attendance_date','<=', request()->input('driver_attTo'));
            }
            if (request()->has('driver_name') && request()->input('driver_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('driver_name') . '%');
            }

            $res->select('driver_attendance.*','driver_info.name');
            $attRes = $res->get();

            $resData = [];
            foreach ($attRes as $res) {
                $resArr['driver_id']      = $res->driver_id;
                $resArr['name']           = $res->name;
                $resArr['attendance_date']= date('Y-m-d',strtotime($res->attendance_date));
                $resArr['check_in']       = $res->check_in;
                $resArr['check_out']      = $res->check_out;
                $resData[] = $resArr;
            }
    
            return Excel::download(new ExportDriverAttendance($resData), 'driver_attendance_export.csv');
        } else {
            request()->merge([
                'driver_attfrom' => null,
                'driver_attTo'   => null,
                'driver_name'    => null,
            ]);
        }       
        $res->select('driver_attendance.*','driver_info.name');
        $res = $res->paginate(20);
        return view('admin.report.driverattreport',[
            'list_result' => $res
        ]);
    }
}
