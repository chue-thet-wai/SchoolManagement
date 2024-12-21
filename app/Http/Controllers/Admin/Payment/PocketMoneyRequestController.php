<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PocketMoneyRequestController extends Controller
{
    public function pocketMoneyRequest(Request $request) 
    {
        $approveStatus = array(
            '0'=>'Pending',
            '1'=>'Confirm',
            '2'=>'Reject'
        );

        $res = DB::table('guardian_pocket_money')
                    ->leftjoin('student_info','student_info.student_id','=','guardian_pocket_money.student_id')
                    ->leftjoin('student_guardian','student_info.guardian_id','student_guardian.id');
        if ($request['action'] == 'search') {
            if (request()->has('pocketmoney_studentid') && request()->input('pocketmoney_studentid') != '') {
                $res->where('guardian_pocket_money.student_id', request()->input('pocketmoney_studentid'));
            }
            if (request()->has('pocketmoney_studentname') && request()->input('pocketmoney_studentname') != '') {
                $res->where('student_info.name', 'Like', '%' . request()->input('pocketmoney_studentname') . '%');
            }
            if (request()->has('pocketmoney_guardianname') && request()->input('pocketmoney_guardianname') != '') {
                $res->where('student_guardian.name', 'Like', '%' . request()->input('pocketmoney_guardianname') . '%');
            }
        } 
            
        request()->merge([
            'pocketmoney_studentid'       => null,
            'pocketmoney_studentname'     => null,
            'pocketmoney_guardianname'    => null,
        ]);

        $res->select('guardian_pocket_money.*','student_info.name as student_name',
            'student_guardian.name as guardian_name');
        $res->orderBy('created_at','desc');
        $res = $res->paginate(20);
        return view('admin.payment.pocketmoney.pocketmoneyrequest',[
            'list_result' => $res,
            'approveStatus'=>$approveStatus
        ]);
    }

    public function pocketMoneyApprove(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'pocketmoney_id'          => 'required',
            'approve_status'          => 'required'
        ]);

        DB::beginTransaction();
        try {

            //change invoice paid status
            $updateData = array(
                'status'            => $request->approve_status,
                //'remark'            => $request->remark
            );
            $result = DB::table('guardian_pocket_money')->where('id', $request->pocketmoney_id)->update($updateData);
            //add money to wallet
            $pocketMoney = DB::table('guardian_pocket_money')->where('id', $request->pocketmoney_id)->first();

            $checkCurrent = Wallet::where('card_id',$pocketMoney->card_id)->first();
            $totalAmount = 0;

            if (!empty($checkCurrent)) {
                $totalAmount = $pocketMoney->amount + $checkCurrent->total_amount;
                $deletCurrent = Wallet::where('id',$checkCurrent->id)->delete();
            } else {
                $totalAmount = $pocketMoney->amount;
            }
            
            $insertData = array(
                'card_id'           =>$pocketMoney->card_id,
                'student_id'        =>$pocketMoney->student_id,
                'amount'            =>$pocketMoney->amount,
                'total_amount'      =>$totalAmount,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $wallet_id=Wallet::insertGetId($insertData);
            if ($wallet_id) {
                $insertData = array(
                    'card_id'           =>$pocketMoney->card_id,
                    'student_id'        =>$pocketMoney->student_id,
                    'status'            =>'1', //In status
                    'status_id'         =>$wallet_id,
                    'amount'            =>$pocketMoney->amount,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $result=WalletHistory::insert($insertData);

                if ($result) {              
                    DB::commit();
                    return redirect(url('admin/pocket_money_request'))->with('success', 'Successfully Approve!');
                }
            }
            return redirect()->back()->with('danger', 'Approve Fail!');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Approve Fail !');
        }
    }
}
