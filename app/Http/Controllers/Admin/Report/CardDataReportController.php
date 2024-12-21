<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportCardData;
use App\Http\Controllers\Controller;
use App\Models\CancelRegistration;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CardDataReportController extends Controller
{

    public function __construct() 
    {
       
    }

    public function cardDataReport(Request $request) {
        $cardStatus = $this->cardStatus();
        $res = WalletHistory::leftJoin('student_info','student_info.student_id','=','wallet_history.student_id')
                            ->orderBy('wallet_history.created_at','desc');
        if ($request['action'] == 'search') {
            if (request()->has('carddata_studentid') && request()->input('carddata_studentid') != '') {
                $res->where('wallet_history.student_id', request()->input('carddata_studentid'));
            }
            if (request()->has('carddata_cardid') && request()->input('carddata_cardid') != '') {
                $res->where('wallet_history.card_id', request()->input('carddata_cardid'));
            }
            if (request()->has('carddata_studentname') && request()->input('carddata_studentname') != '') {
                $res->where('name', 'Like', '%' . request()->input('carddata_studentname') . '%');
            }
            if (request()->has('carddata_fromdate') && request()->input('carddata_fromdate') != '') {
                $res->where('wallet_history.created_at','>=', request()->input('carddata_fromdate'));
            }
            if (request()->has('carddata_todate') && request()->input('carddata_todate') != '') {
                $res->where('wallet_history.created_at','<=', request()->input('carddata_todate'));
            }
        } else if ($request['action'] == 'export') {

            if (request()->has('carddata_studentid') && request()->input('carddata_studentid') != '') {
                $res->where('wallet_history.student_id', request()->input('carddata_studentid'));
            }
            if (request()->has('carddata_cardid') && request()->input('carddata_cardid') != '') {
                $res->where('wallet_history.card_id', request()->input('carddata_cardid'));
            }
            if (request()->has('carddata_studentname') && request()->input('carddata_studentname') != '') {
                $res->where('name', 'Like', '%' . request()->input('carddata_studentname') . '%');
            }
            if (request()->has('carddata_fromdate') && request()->input('carddata_fromdate') != '') {
                $res->where('wallet_history.created_at','>=', request()->input('carddata_fromdate'));
            }
            if (request()->has('carddata_todate') && request()->input('carddata_todate') != '') {
                $res->where('wallet_history.created_at','<=', request()->input('carddata_todate'));
            }
            $res->select('wallet_history.*','student_info.name');
            $queryResult = $res->get();

            $returnData = [];
            foreach ($queryResult as $res) {
                $resArr['student_id']     = $res->student_id;
                $resArr['card_id']        = $res->card_id;
                $resArr['name']           = $res->name;
                $resArr['amount']         = $res->amount;
                $resArr['status']         = $cardStatus[$res->status];
                $resArr['date']    = date('Y-m-d',strtotime($res->created_at));
                $returnData[] = $resArr;
            }
    
            return Excel::download(new ExportCardData($returnData), 'carddata_export.csv');
        } else {
            request()->merge([
                'carddata_studentid'   => null,
                'carddata_cardid'      => null,
                'carddata_studentname' => null,
                'carddata_fromdate'    => null,
                'carddata_todate'      => null,
            ]);
        }       
        $res->select('wallet_history.*','student_info.name');
        $res = $res->paginate(20);
    
        return view('admin.report.carddatareport',['list_result' => $res,'card_status'=>$cardStatus]);
    }

    function cardStatus(){
        return array(
            '1'=>'IN',
            '2'=>'OUT'
        );
    }

}
