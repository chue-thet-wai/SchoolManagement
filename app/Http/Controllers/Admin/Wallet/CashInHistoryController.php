<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashInHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CashInHistoryList(Request $request)
    {
        $res = WalletHistory::leftjoin('student_info','student_info.student_id','=','wallet_history.student_id')
                        ->select('wallet_history.*','student_info.name as name');
        if ($request['action'] == 'search') {
            if (request()->has('cashcounter_cardid') && request()->input('cashcounter_cardid') != '') {
                $res->where('wallet_history.card_id', request()->input('cashcounter_cardid'));
            }
            if (request()->has('cashcounter_studentid') && request()->input('cashcounter_studentid') != '') {
                $res->where('wallet_history.student_id', request()->input('cashcounter_studentid'));
            }
        }else {
            request()->merge([
                'cashcounter_cardid'      => null,
                'cashcounter_studentid'   => null,
            ]);
        }       
        $res = $res->paginate(20);

        $returnArray = $res->toArray();       
        
        $returnWalletData =[];
        if (!empty($returnArray['data'])) {
            foreach($returnArray['data'] as $data) {
                $walletData= [];
                $walletData['amount']=$data['amount'];
                if ($data['status'] == 1) {
                    $walletData['cash_status']='IN';
                } else {
                    $walletData['cash_status']='OUT';
                }
                $totalAmt = Wallet::where('card_id',$data['card_id'])->first('total_amount');               
                $walletData['total_amount']=$totalAmt->total_amount;
                
                $returnWalletData[$data['card_id']][] = $walletData;
            }
        }
        
        return view('admin.wallet.cashinhistory.cashinhistory_list',['list_result' => $res,
        'wallet_result'=>$returnWalletData]);
    }
}
