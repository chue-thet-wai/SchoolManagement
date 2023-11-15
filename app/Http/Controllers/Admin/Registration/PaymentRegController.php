<?php

namespace App\Http\Controllers\Admin\Registration;

use App\Http\Controllers\Controller;
use App\Models\AdditionalFee;
use App\Models\PaymentRegistration;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\RegistrationRepositoryInterface;

class PaymentRegController extends Controller
{
    private RegistrationRepositoryInterface $regRepository;

    public function __construct(RegistrationRepositoryInterface $regRepository) 
    {
        $this->regRepository     = $regRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentList(Request $request)
    {
        $res = PaymentRegistration::select('payment_registration.*');
        if ($request['action'] == 'search') {
            if (request()->has('payment_id') && request()->input('payment_id') != '') {
                $res->where('payment_id',request()->input('payment_id'));
            }
            if (request()->has('payment_regno') && request()->input('payment_regno') != '') {
                $res->where('registration_no', request()->input('payment_regno'));
            }
        }else {
            request()->merge([
                'payment_id'      => null,
                'payment_regno'   => null
            ]);
        }  
        
        $res=$res->paginate(20);

        $paymentType = array(
            '0'  => 'Monthly',
            '1'  => 'Yearly'
        );
        return view('admin.registration.paymentreg.index',[
            'list_result' => $res,
            'payment_type'=> $paymentType
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $addition_fee_list = AdditionalFee::get();
        return view('admin.registration.paymentreg.create',[
            'additional_fee' => $addition_fee_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'registration_no'            =>'required',
        ]); 
        $studentRegSearch = StudentRegistration::where('registration_no',$request->registration_no)->first();
        if (empty($studentRegSearch)) {
            return redirect()->back()->with('danger','Registration ID not found!');
        }

        $discount_precent =0;
        if($request->discount_percent != '') {
            $discount_precent = $request->discount_percent;
        }
     
        $paymentID = $this->regRepository->generatePaymentID();

        DB::beginTransaction();
        try{
            $additionalFee = $request->additionalFee;
            if (!empty($additionalFee)) {
                for ($i=0;$i<count($additionalFee);$i++) {
                    $fee = array();
                    $fee = explode('|', $additionalFee[$i]);
                    $additionalData = array(
                        'payment_id'        => $paymentID,
                        'additional_fee_id' => $fee[0],
                        'additional_amount' => $fee[1],
                        'created_by'        =>$login_id,
                        'updated_by'        =>$login_id,
                        'created_at'        =>$nowDate,
                        'updated_at'        =>$nowDate
                    );
                    $res=DB::table('payment_additional_fee')->insert($additionalData);
                }                
            }
            
            $insertData = array(
                'payment_id'        =>$paymentID,
                'registration_no'   =>$request->registration_no,
                'pay_date'          =>$request->pay_date,
                'payment_type'      =>$request->payment_type,
                'pay_from_period'   =>$request->pay_from_period,
                'pay_to_period'     =>$request->pay_to_period,
                'grade_level_fee'   =>$request->grade_level_fee,
                'total_amount'      =>$request->total_amount,
                'discount_percent'  =>$discount_precent,
                'net_total'         =>$request->net_total,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=PaymentRegistration::insert($insertData);
                        
            if($result){            
                DB::commit();
                return redirect(url('admin/payment/list'))->with('success','Payment Registration Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Payment Registration Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Payment Registration Created Fail !');
        }       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $res = [];
        $paymentAddFeeArr = array();
        $res = PaymentRegistration::leftjoin('student_registration','student_registration.registration_no','=','payment_registration.registration_no')
                ->leftjoin('class_setup','class_setup.id','=','student_registration.new_class_id')
                ->leftjoin('grade','grade.id','=','class_setup.grade_id')
                ->where('payment_registration.payment_id',$id)
                ->select('payment_registration.*','grade.name as grade_name')
                ->get();
        if (count($res) > 0) {
            $paymentAddFee = DB::table('payment_additional_fee')
                            ->select('payment_additional_fee.*')
                            ->where('payment_id',$id)
                            ->get()->toArray(); 
            $paymentAddFeeArr = array_column($paymentAddFee,'additional_fee_id');
        }  
        
        $addition_fee_list = AdditionalFee::get();
        $paymentType = array(
            '0'  => 'Monthly',
            '1'  => 'Yearly'
        );
        return view('admin.registration.paymentreg.update',[
            'result'=>$res,
            'payment_additional_fee'=>$paymentAddFeeArr,
            'addition_fee_list'     =>$addition_fee_list,
            'payment_type'          =>$paymentType
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'registration_no'            =>'required',
        ]); 
        $studentRegSearch = StudentRegistration::where('registration_no',$request->registration_no)->first();
        if (empty($studentRegSearch)) {
            return redirect()->back()->with('danger','Registration ID not found!');
        }
        $discount_precent =0;
        if($request->discount_percent != '') {
            $discount_precent = $request->discount_percent;
        }

        DB::beginTransaction();
        try{
            $additionalFee = $request->additionalFee;
            $delAddFee = DB::table('payment_additional_fee')
                            ->where('payment_id',$id)
                            ->delete();
            
            if (!empty($additionalFee)) {
                for ($i=0;$i<count($additionalFee);$i++) {
                    $fee = array();
                    $fee = explode('|', $additionalFee[$i]);
                    
                    $additionalData = array(
                        'payment_id'        => $id,
                        'additional_fee_id' => $fee[0],
                        'additional_amount' => $fee[1],
                        'created_by'        =>$login_id,
                        'updated_by'        =>$login_id,
                        'created_at'        =>$nowDate,
                        'updated_at'        =>$nowDate
                    );
                    
                    $res=DB::table('payment_additional_fee')->insert($additionalData);
                    
                }                
            }
            
            
            $updateData = array(
                'payment_id'        =>$id,
                'registration_no'   =>$request->registration_no,
                'pay_date'          =>$request->pay_date,
                'payment_type'      =>$request->payment_type,
                'pay_from_period'   =>$request->pay_from_period,
                'pay_to_period'     =>$request->pay_to_period,
                'grade_level_fee'   =>$request->grade_level_fee,
                'total_amount'      =>$request->total_amount,
                'discount_percent'  =>$discount_precent,
                'net_total'         =>$request->net_total,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            $result=PaymentRegistration::where('payment_id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/payment/list'))->with('success','Payment Registration Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Payment Registration Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Payment Registration Updared Fail !');
        }          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $nowDate  = date('Y-m-d H:i:s', time());

        try{
            $checkData = PaymentRegistration::where('payment_id',$id)->first();

            if (!empty($checkData)) {
                
                $res = PaymentRegistration::where('payment_id',$id)->delete();
                $delAddFee = DB::table('payment_additional_fee')
                            ->where('payment_id',$id)
                            ->update(array('deleted_at'=>$nowDate));
            }else{
                return redirect()->back()->with('error','There is no result with this payment registraion.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/payment/list'))->with('success','Payment Registration Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Payment Registration Deleted Failed!');
        }
    }
}
