<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WalletHistory;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashCounterController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CashCounterList(Request $request)
    {
        $res = Wallet::leftjoin('student_info','student_info.student_id','=','wallet.student_id')
                        ->select('wallet.*','student_info.name as name');
        if ($request['action'] == 'search') {
            if (request()->has('cashcounter_cardid') && request()->input('cashcounter_cardid') != '') {
                $res->where('wallet.card_id', request()->input('cashcounter_cardid'));
            }
            if (request()->has('cashcounter_studentid') && request()->input('cashcounter_studentid') != '') {
                $res->where('wallet.student_id', request()->input('cashcounter_studentid'));
            }
        }else {
            request()->merge([
                'cashcounter_cardid'      => null,
                'cashcounter_studentid'   => null,
            ]);
        }       
        $res = $res->paginate(20);
        
        return view('admin.wallet.cashcounter.cashcounter_list',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CashCounterCreate()
    {
        
        return view('admin.wallet.cashcounter.cashcounter_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cashCounterSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'card_id'           =>'required',
            'student_id'        =>'required',
            'amount'            =>'required',
        ]); 
       
       
        DB::beginTransaction();
        try{
            $checkCurrent = Wallet::where('card_id',$request->card_id)->first();
            $totalAmount = 0;
            if (!empty($checkCurrent)) {
                $totalAmount = $request->amount + $checkCurrent->total_amount;
                $deletCurrent = Wallet::where('id',$checkCurrent->id)->delete();
            } else {
                $totalAmount = $request->amount;
            }
            
            $insertData = array(
                'card_id'           =>$request->card_id,
                'student_id'        =>$request->student_id,
                'amount'            =>$request->amount,
                'total_amount'      =>$totalAmount,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $wallet_id=Wallet::insertGetId($insertData);
            if ($wallet_id) {
                $insertData = array(
                    'card_id'           =>$request->card_id,
                    'student_id'        =>$request->student_id,
                    'status'            =>'1', //In status
                    'status_id'         =>$wallet_id,
                    'amount'            =>$request->amount,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $result=WalletHistory::insert($insertData);
            }
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/cash_counter/list'))->with('success','Cash Insert Successfully!');
            }else{
                return redirect()->back()->with('danger','Cash Insert Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Cash Insert Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function CashCounterEdit($id)
    {    
        $res = Wallet::leftjoin('student_info','student_info.student_id','=','wallet.student_id')
                ->where('wallet.id',$id)
                ->select('wallet.*','student_info.name as name')
                ->get();
        return view('admin.wallet.cashcounter.cashcounter_update',[
            'result'=>$res]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function CashCounterUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'card_id'           =>'required',
            'student_id'        =>'required',
            'amount'            =>'required',
        ]); 

        DB::beginTransaction();
        try{
            $checkCurrent = Wallet::where('id',$id)->first();
            $totalAmount = 0;
            if (!empty($checkCurrent)) {
                $totalAmount = $checkCurrent->total_amount - $checkCurrent->amount;
                $totalAmount = $totalAmount + $request->amount;                
            }
            $walletData = array(
                'amount'            =>$request->amount,
                'total_amount'      =>$totalAmount,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate

            );            
            $result=Wallet::where('id',$id)->update($walletData);
            if ($result) {
                $walletHistoryData = array(
                    'amount'            =>$request->amount,
                    'updated_by'        =>$login_id,
                    'updated_at'        =>$nowDate
    
                );            
                $historyRes=WalletHistory::where('id',$id)->update($walletHistoryData);
            }
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/cash_counter/list'))->with('success','Cash Counter Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Cash Counter Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Cash Counter Updared Fail !');
        }  
    }
}
