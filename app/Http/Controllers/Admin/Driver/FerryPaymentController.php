<?php

namespace App\Http\Controllers\Admin\Driver;

use App\Exports\ExportPayment;
use App\Exports\FerryPayment;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class FerryPaymentController extends Controller
{

    public function __construct() 
    {
       
    }

    public function ferryPayment(Request $request) {
        $paymentType = array(
            '0'  => 'Monthly',
            '1'  => 'Yearly',
            '2'  => 'One Time',
            '3'  => 'Ferry Payment',
            '4'  => 'Food Order Payment'
        );
        $res = Payment::leftjoin('invoice','invoice.invoice_id','payment.invoice_id')
                ->leftJoin('student_info','student_info.student_id','=','payment.student_id')
                ->where('invoice.payment_type','3'); // ferry payment
        if ($request['action'] == 'search') {
            if (request()->has('ferrypayment_studentid') && request()->input('ferrypayment_studentid') != '') {
                $res->where('payment.student_id', request()->input('ferrypayment_studentid'));
            }
            if (request()->has('ferrypayment_paymentId') && request()->input('ferrypayment_paymentId') != '') {
                $res->where('payment.invoice_id', request()->input('ferrypayment_paymentId'));
            }
            if (request()->has('ferrypayment_name') && request()->input('ferrypayment_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('ferrypayment_name') . '%');
            }
        } else if ($request['action'] == 'export') {

            if (request()->has('ferrypayment_studentid') && request()->input('ferrypayment_studentid') != '') {
                $res->where('payment.student_id', request()->input('ferrypayment_studentid'));
            }
            if (request()->has('ferrypayment_paymentId') && request()->input('ferrypayment_paymentId') != '') {
                $res->where('payment.invoice_id', request()->input('ferrypayment_paymentId'));
            }
            if (request()->has('ferrypayment_name') && request()->input('ferrypayment_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('ferrypayment_name') . '%');
            }

            $res->select('payment.*','invoice.*','student_info.name');
            $paymentRes = $res->get();

            $paymentData = [];
            foreach ($paymentRes as $res) {
                $resArr['invoice_id']      = $res->invoice_id;
                $resArr['student_id']      = $res->student_id;
                $resArr['name']            = $res->name;

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
                $resArr['pay_from_period']       = $res->pay_from_period;
                $resArr['pay_to_period']         = $res->pay_to_period;
                $resArr['net_total']             = $res->net_total;
                $paymentData[] = $resArr;
            }
    
            return Excel::download(new FerryPayment($paymentData), 'ferry_payment.csv');
        } else {
            request()->merge([
                'ferrypayment_studentid' => null,
                'ferrypayment_paymentId' => null,
                'ferrypayment_name'      => null,
            ]);
        }  
           
        $res->select('payment.*','invoice.*','student_info.name');
        $res = $res->paginate(20);
        return view('admin.driver.ferrypayment',['list_result' => $res,'payment_types'=> $paymentType]);
    }

}
