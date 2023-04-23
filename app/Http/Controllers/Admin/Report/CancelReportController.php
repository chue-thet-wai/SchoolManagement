<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportCancel;
use App\Http\Controllers\Controller;
use App\Models\CancelRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CancelReportController extends Controller
{

    public function __construct() 
    {
       
    }

    public function cancelReport(Request $request) {
        $res = CancelRegistration::leftJoin('student_info','student_info.student_id','=','cancel_registration.student_id');
        if ($request['action'] == 'search') {
            if (request()->has('cancel_regno') && request()->input('cancel_regno') != '') {
                $res->where('registration_no', request()->input('cancel_regno'));
            }
            if (request()->has('cancel_studentId') && request()->input('cancel_studentId') != '') {
                $res->where('cancel_registration.student_id', request()->input('cancel_studentId'));
            }
            if (request()->has('cancel_name') && request()->input('cancel_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('cancel_name') . '%');
            }
        } else if ($request['action'] == 'export') {

            if (request()->has('cancel_regno') && request()->input('cancel_regno') != '') {
                //$res->where('registration_no', 'Like', '%' . request()->input('cancel_regno') . '%');
                $res->where('registration_no', request()->input('cancel_regno'));
            }
            if (request()->has('cancel_studentId') && request()->input('cancel_studentId') != '') {
                $res->where('cancel_registration.student_id', request()->input('cancel_studentId'));
            }
            if (request()->has('cancel_name') && request()->input('cancel_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('cancel_name') . '%');
            }

            $res->select('cancel_registration.*','student_info.name');
            $calcelRes = $res->get();

            $cancelData = [];
            foreach ($calcelRes as $res) {
                $resArr['registration_no']= $res->registration_no;
                $resArr['student_id']     = $res->student_id;
                $resArr['name']           = $res->name;

                if ($res->cancel_date != '') {
                    $resArr['cancel_date'] = date('Y-m-d',strtotime($res->cancel_date));
                } else {
                    $resArr['cancel_date'] = $res->cancel_date;
                }

                $resArr['refund_amount']  = $res->refund_amount;
                $cancelData[] = $resArr;
            }
    
            return Excel::download(new ExportCancel($cancelData), 'cancel_export.csv');
        } else {
            request()->merge([
                'cancel_regno' => null,
                'cancel_studentId' => null,
                'cancel_name'      => null,
            ]);
        }       
        $res->select('cancel_registration.*','student_info.name');
        $res = $res->paginate(20);
        return view('admin.report.cancelreport',['list_result' => $res]);
    }

}
