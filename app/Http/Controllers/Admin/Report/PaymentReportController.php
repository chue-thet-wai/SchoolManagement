<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportPayment;
use App\Http\Controllers\Controller;
use App\Models\Payment;
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
            '0'  => 'Monthly',
            '1'  => 'Yearly',
            '2'  => 'One Time',
            '3'  => 'Ferry Payment',
            '4'  => 'Food Order Payment'
        );
        $res = Payment::leftjoin('invoice','invoice.invoice_id','payment.invoice_id')
                //->leftjoin('student_registration','student_registration.student_id','payment.student_id')
                ->leftJoin('student_info','student_info.student_id','=','payment.student_id')
                ->whereNotIn('invoice.payment_type', ['3', '4']);//not ferry and food order
        if ($request['action'] == 'search') {
            if (request()->has('payment_studentid') && request()->input('payment_studentid') != '') {
                $res->where('payment.student_id', request()->input('payment_studentid'));
            }
            if (request()->has('payment_paymentId') && request()->input('payment_paymentId') != '') {
                $res->where('payment.invoice_id', request()->input('payment_paymentId'));
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

            if (request()->has('payment_studentid') && request()->input('payment_studentid') != '') {
                $res->where('payment.student_id', request()->input('payment_studentid'));
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

            $res->select('payment.*','invoice.*','student_info.name');
            $paymentRes = $res->get();

            $paymentData = [];
            foreach ($paymentRes as $res) {
                $resArr['invoice_id']     = $res->invoice_id;
                $resArr['student_id']     = $res->student_id;
                $resArr['name']           = $res->name;

                if ($res->paid_date != '') {
                    $resArr['paid_date'] = date('Y-m-d',strtotime($res->paid_date));
                } else {
                    $resArr['paid_date'] = $res->paid_date;
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
                'payment_studentid' => null,
                'payment_paymentId' => null,
                'payment_name'      => null,
            ]);
        }  
           
        $res->select('payment.*','invoice.*','student_info.name');
        $res = $res->paginate(20);
        return view('admin.report.paymentreport',['list_result' => $res,'payment_types'=> $paymentType]);
    }

}
