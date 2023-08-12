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
        $paymentType = array(
            '2'  => 'Monthly',
            '1'  => 'Yearly'
        );  
        $res = PaymentRegistration::leftjoin('student_registration','student_registration.registration_no','payment_registration.registration_no')
                ->leftJoin('student_info','student_info.student_id','=','student_registration.student_id');
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
            if (request()->has('payment_type') && request()->input('payment_type') != '') {
                if (request()->input('payment_type')==2) {
                    $res->where('payment_type', 0);  
                } else {
                    $res->where('payment_type', request()->input('payment_type'));
                }               
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
            if (request()->has('payment_type') && request()->input('payment_type') != '') {
                if (request()->input('payment_type')==2) {
                    $res->where('payment_type', 0);  
                } else {
                    $res->where('payment_type', request()->input('payment_type'));
                }               
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
                if ($res->payment_type == 0) {
                    $resArr['payment_type']      = $paymentType[2];
                } else {
                    $resArr['payment_type']      = $paymentType[$res->payment_type];
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
        $res = $res->paginate(20);
        return view('admin.report.paymentreport',['list_result' => $res,'payment_types'=> $paymentType]);
    }

}