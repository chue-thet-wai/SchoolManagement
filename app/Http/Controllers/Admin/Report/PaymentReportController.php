<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportPayment;
use App\Http\Controllers\Controller;
use App\Models\PaymentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PaymentReportController extends Controller
{

    public function __construct() 
    {
       
    }

    public function paymentReport(Request $request) {
        $res = PaymentRegistration::leftjoin('student_registration','student_registration.registration_no','payment_registration.registration_no')
                ->leftJoin('student_info','student_info.student_id','=','student_registration.student_id')
                ->where('payment_registration.payment_type',0);
        if ($request['action'] == 'search') {
            if (request()->has('payment_regno') && request()->input('payment_regno') != '') {
                $res->where('payment_registration.registration_no', request()->input('payment_regno'));
            }
            if (request()->has('payment_paymentId') && request()->input('payment_paymentId') != '') {
                $res->where('payment_id', request()->input('payment_paymentId'));
            }
            if (request()->has('payment_name') && request()->input('payment_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('payment_name') . '%');
            }
        } else if ($request['action'] == 'export') {

            if (request()->has('payment_regno') && request()->input('payment_regno') != '') {
                $res->where('payment_registration.registration_no', request()->input('payment_regno'));
            }
            if (request()->has('payment_paymentId') && request()->input('payment_paymentId') != '') {
                $res->where('payment_id', request()->input('payment_paymentId'));
            }
            if (request()->has('payment_name') && request()->input('payment_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('payment_name') . '%');
            }

            $res->select('payment_registration.*','student_info.name');
            $paymentRes = $res->get();

            $paymentData = [];
            foreach ($paymentRes as $res) {
                $resArr['payment_id']     = $res->payment_id;
                $resArr['registration_no']= $res->registration_no;
                $resArr['name']           = $res->name;

                if ($res->pay_date != '') {
                    $resArr['pay_date'] = date('Y-m-d',strtotime($res->pay_date));
                } else {
                    $resArr['pay_date'] = $res->pay_date;
                }

                $resArr['total_amount']      = $res->total_amount;
                $resArr['discount_percent']  = $res->discount_percent;
                $resArr['net_total']         = $res->net_total;
                $paymentData[] = $resArr;
            }
    
            return Excel::download(new ExportPayment($paymentData), 'payment_export.csv');
        } else {
            request()->merge([
                'payment_regno' => null,
                'payment_paymentId' => null,
                'payment_name'      => null,
            ]);
        }       
        $res->select('payment_registration.*','student_info.name');
        $res = $res->paginate(5);
        return view('admin.report.paymentreport',['list_result' => $res]);
    }

}
