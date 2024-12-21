<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\AdditionalFee;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\StudentInfo;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Services\NotificationService;

class PaymentRegController extends Controller
{
    private RegistrationRepositoryInterface $regRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private $notificationService;

    public function __construct(RegistrationRepositoryInterface $regRepository, CategoryRepositoryInterface $categoryRepository,NotificationService $notificationService)
    {
        $this->regRepository      = $regRepository;
        $this->categoryRepository = $categoryRepository;
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentList(Request $request)
    {
        $res = Invoice::select('invoice.*', 'payment.paid_date as paid_date')
            ->leftjoin('payment', 'payment.invoice_id', '=', 'invoice.invoice_id')
            ->whereNotIn('invoice.payment_type', ['3', '4']);//not ferry and food order
        if ($request['action'] == 'search') {
            if (request()->has('payment_invoiceid') && request()->input('payment_invoiceid') != '') {
                $res->where('invoice.invoice_id', request()->input('payment_invoiceid'));
            }
            if (request()->has('payment_regno') && request()->input('payment_regno') != '') {
                $res->where('invoice.student_id', request()->input('payment_regno'));
            }
        } else {
            request()->merge([
                'payment_invoiceid'  => null,
                'payment_regno'      => null
            ]);
        }
        $res = $res->paginate(20);

        $paymentType = array(
            '0'  => 'Monthly',
            '1'  => 'Yearly',
            '2'  => 'One Time'
        );
        $paidType  = $this->paidType();
        return view('admin.payment.paymentreg.index', [
            'list_result' => $res,
            'payment_type' => $paymentType,
            'paid_type'   => $paidType
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nowDate  = date('Y-m-d H:i:s', time());
        $addition_fee_list = AdditionalFee::with('grade', 'grade:id,name')->get();

        $branch_list_data   = $this->categoryRepository->getBranch();
        $branch_list = [];
        foreach ($branch_list_data as $b) {
            $branch_list[$b->id] = $b->name;
        }

        $currentAcademicYear = DB::table('academic_year')
            ->where('start_date', '<=', $nowDate)
            ->where('end_date', '>=', $nowDate)
            ->get()->toArray();
        $academic_start = null;
        $academic_end  = null;
        if (!empty($currentAcademicYear)) {
            $academic_start = date('Y-m-d', strtotime($currentAcademicYear[0]->start_date));
            $academic_end = date('Y-m-d', strtotime($currentAcademicYear[0]->end_date));
        }

        /*$class_list = $this->regRepository->getClass(); 
        $class=[];
        foreach($class_list as $a) {
            $class[$a->id] = $a->name;
        }*/
        $class = [];

        return view('admin.payment.paymentreg.create', [
            'additional_fee' => $addition_fee_list,
            'branch_list'    => $branch_list,
            'class_list'     => $class,
            'academic_start' => $academic_start,
            'academic_end'   => $academic_end
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

        $regno_list = array();
        if ($request->invoice_type == 1) { //branch
            $request->validate([
                'branch_id'            => 'required',
                'class_id'             => 'required',
            ]);
            $class_id = explode("/", $request->class_id);
            $student_res = StudentRegistration::select("student_id")
                ->where('new_class_id', $class_id[0])
                ->get()->toArray();

            $regno_list = array_column($student_res, 'student_id');
        } else {
            $request->validate([
                'student_id'            => 'required',
            ]);
            $studentRegSearch = StudentRegistration::where('student_id', $request->student_id)->first();
            if (empty($studentRegSearch)) {
                return redirect()->back()->with('danger', 'Registration ID not found!');
            } else {
                if ($request->payment_type_choose != '2') {
                    if ($request->pay_from_period =='') {
                        return redirect()->back()->with('danger', 'Please select Pay Period (From).');
                    } else if ($request->pay_to_period == ''){
                        return redirect()->back()->with('danger', 'Please select Pay Period (To).');
                    }
                    $checkInvoice = Invoice::where('student_id', $request->student_id)
                        ->where('pay_to_period', '>=', $request->pay_from_period)
                        ->get()->toArray();
                    if (!empty($checkInvoice)) {
                        return redirect()->back()->with('danger', 'Invoice already Created for Pay Period (From)!');
                    }
                }
                array_push($regno_list, $request->student_id);
            }
        }

        $discount_precent = 0;
        if ($request->discount_percent != '') {
            $discount_precent = $request->discount_percent;
        }

        DB::beginTransaction();
        try {
            $additionalFee = $request->additionalFee;
            $insertData = [];

            for ($j = 0; $j < count($regno_list); $j++) {
                //check invoice already created or not for pay_from_period
                if ($request->payment_type_choose != '2') {
                    $checkInvoice = Invoice::where('student_id', $regno_list[$j])
                        ->where('pay_to_period', '>=', $request->pay_from_period)
                        ->get()->toArray();
                } else {
                    $checkInvoice = [];
                }
                
                if (empty($checkInvoice) || ($request->payment_type_choose == '2')) {
                    $invoiceID = $this->regRepository->generatePaymentInvoiceID();

                    if (!empty($additionalFee)) {
                        for ($i = 0; $i < count($additionalFee); $i++) {
                            $fee = array();
                            $fee = explode('|', $additionalFee[$i]);
                            $additionalData = array(
                                'invoice_id'        => $invoiceID,
                                'additional_fee_id' => $fee[0],
                                'additional_amount' => $fee[1],
                                'created_by'        => $login_id,
                                'updated_by'        => $login_id,
                                'created_at'        => $nowDate,
                                'updated_at'        => $nowDate
                            );
                            $res = DB::table('payment_additional_fee')->insert($additionalData);
                        }
                    }

                    $insertData[] = array(
                        'invoice_id'        => $invoiceID,
                        'student_id'        => $regno_list[$j],
                        'payment_type'      => $request->payment_type_choose,
                        'pay_from_period'   => $request->pay_from_period,
                        'pay_to_period'     => $request->pay_to_period,
                        'grade_level_fee'   => $request->grade_level_fee,
                        'total_amount'      => $request->total_amount,
                        'discount_percent'  => $discount_precent,
                        'net_total'         => $request->net_total,
                        'created_by'        => $login_id,
                        'updated_by'        => $login_id,
                        'created_at'        => $nowDate,
                        'updated_at'        => $nowDate
                    );
                }
            }

            if (count($insertData) > 0) {
                $result = Invoice::insert($insertData);
                
                if ($result) {
                    DB::commit();
                    return redirect(url('admin/invoice/list'))->with('success', 'Invoice Created Successfully!');
                } else {
                    return redirect()->back()->with('danger', 'Invoice Created Fail !');
                }
            } else {
                return redirect()->back()->with('danger', 'No Data to create Invoice !');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Invoice Created Fail !');
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
        $nowDate  = date('Y-m-d H:i:s', time());
        $res = [];
        $paymentAddFeeArr = array();
        $res = Invoice::leftjoin('student_registration', 'student_registration.student_id', '=', 'invoice.student_id')
            ->leftjoin('class_setup', 'class_setup.id', '=', 'student_registration.new_class_id')
            ->leftjoin('grade', 'grade.id', '=', 'class_setup.grade_id')
            ->where('invoice.invoice_id', $id)
            ->select('invoice.*', 'grade.name as grade_name')
            ->get();
        if (count($res) > 0) {
            $paymentAddFee = DB::table('payment_additional_fee')
                ->select('payment_additional_fee.*')
                ->where('invoice_id', $id)
                ->get()->toArray();
            $paymentAddFeeArr = array_column($paymentAddFee, 'additional_fee_id');
        }

        $addition_fee_list = AdditionalFee::get();
        $paymentType = array(
            '0'  => 'Monthly',
            '1'  => 'Yearly',
            '2'  => 'One Time'
        );

        $currentAcademicYear = DB::table('academic_year')
            ->where('start_date', '<=', $nowDate)
            ->where('end_date', '>=', $nowDate)
            ->get()->toArray();
        $academic_start = null;
        $academic_end  = null;
        if (!empty($currentAcademicYear)) {
            $academic_start = date('Y-m-d', strtotime($currentAcademicYear[0]->start_date));
            $academic_end = date('Y-m-d', strtotime($currentAcademicYear[0]->end_date));
        }

        return view('admin.payment.paymentreg.update', [
            'result' => $res,
            'payment_additional_fee' => $paymentAddFeeArr,
            'addition_fee_list'     => $addition_fee_list,
            'payment_type'          => $paymentType,
            'academic_start'        => $academic_start,
            'academic_end'          => $academic_end
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
            'student_id'            => 'required',
        ]);
        $studentRegSearch = Invoice::where('student_id', $request->student_id)->first();
        if (empty($studentRegSearch)) {
            return redirect()->back()->with('danger', 'Registration ID not found!');
        }
        $discount_precent = 0;
        if ($request->discount_percent != '') {
            $discount_precent = $request->discount_percent;
        }

        DB::beginTransaction();
        try {
            $additionalFee = $request->additionalFee;
            $delAddFee = DB::table('payment_additional_fee')
                ->where('invoice_id', $id)
                ->delete();

            if (!empty($additionalFee)) {
                for ($i = 0; $i < count($additionalFee); $i++) {
                    $fee = array();
                    $fee = explode('|', $additionalFee[$i]);

                    $additionalData = array(
                        'invoice_id'        => $id,
                        'additional_fee_id' => $fee[0],
                        'additional_amount' => $fee[1],
                        'created_by'        => $login_id,
                        'updated_by'        => $login_id,
                        'created_at'        => $nowDate,
                        'updated_at'        => $nowDate
                    );

                    $res = DB::table('payment_additional_fee')->insert($additionalData);
                }
            }


            $updateData = array(
                'invoice_id'        => $id,
                'student_id'        => $request->student_id,
                'payment_type'      => $request->payment_type_choose,
                'pay_from_period'   => $request->pay_from_period,
                'pay_to_period'     => $request->pay_to_period,
                'grade_level_fee'   => $request->grade_level_fee,
                'total_amount'      => $request->total_amount,
                'discount_percent'  => $discount_precent,
                'net_total'         => $request->net_total,
                'updated_by'        => $login_id,
                'updated_at'        => $nowDate
            );

            $result = Invoice::where('invoice_id', $id)->update($updateData);

            if ($result) {
                DB::commit();
                return redirect(url('admin/invoice/list'))->with('success', 'Invoice Updated Successfully!');
            } else {
                return redirect()->back()->with('danger', 'Invoie Updated Fail !');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Invoice Updated Fail !');
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

        try {
            $checkData = Invoice::where('invoice_id', $id)->first();

            if (!empty($checkData)) {

                $res = Invoice::where('invoice_id', $id)->delete();
                $delAddFee = DB::table('payment_additional_fee')
                    ->where('invoice_id', $id)
                    ->update(array('deleted_at' => $nowDate));
            } else {
                return redirect()->back()->with('danger', 'There is no result with this invoice.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/invoice/list'))->with('success', 'Invoice Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Invoice Deleted Failed!');
        }
    }

    //To make paid the invoice
    public function paidInvoice(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'paid_invoiceid'            => 'required',
        ]);

        DB::beginTransaction();
        try {
            //substract card amount
            if ($request->paid_paidtype == '3' ) {//card
                $invoiceData = Invoice::where('invoice_id',$request->paid_invoiceid)->first();
                $cardId = StudentInfo::where('student_id', $request->paid_studentid)->value('card_id');
                if ($cardId) {
                    $checkCurrent = Wallet::where('card_id',$cardId)->first();
                    if (empty($checkCurrent)) {
                        return redirect()->back()->with('danger', 'Plese fill the card amount firstly.');
                    }
                    $totalAmount = 0;
                    $totalAmount =  $checkCurrent->total_amount - $invoiceData->net_total;
                    if ($totalAmount < 0) {
                        return redirect()->back()->with('danger', 'Insufficutent Card Amount for Payment!');
                    } else {
                        $deletCurrent = Wallet::where('id',$checkCurrent->id)->delete();
                        $walletinsertData = array(
                            'card_id'           =>$cardId,
                            'student_id'        =>$request->paid_studentid,
                            'amount'            =>$invoiceData->net_total,
                            'total_amount'      =>$totalAmount,
                            'created_by'        =>$login_id,
                            'updated_by'        =>$login_id,
                            'created_at'        =>$nowDate,
                            'updated_at'        =>$nowDate
                        );
                        $wallet_id=Wallet::insertGetId($walletinsertData);
                        if ($wallet_id) {
                            $wallethistoryinsertData = array(
                                'card_id'           =>$cardId,
                                'student_id'        =>$request->paid_studentid,
                                'status'            =>'2', //Out status
                                'status_id'         =>$request->paid_invoiceid,
                                'amount'            =>$invoiceData->net_total,
                                'created_by'        =>$login_id,
                                'updated_by'        =>$login_id,
                                'created_at'        =>$nowDate,
                                'updated_at'        =>$nowDate
                            );
                            $wallethistoryresult=WalletHistory::insert($wallethistoryinsertData);
                        }
                    }
                } else {
                    return redirect()->back()->with('danger', 'Card ID not found!');
                }
            }

            $paidData = array(
                'invoice_id'        => $request->paid_invoiceid,
                'student_id'        => $request->paid_studentid,
                'paid_date'         => $request->paid_paiddate,
                'paid_type'         => $request->paid_paidtype,
                'remark'            => $request->paid_remark,
                'created_by'        => $login_id,
                'updated_by'        => $login_id,
                'created_at'        => $nowDate,
                'updated_at'        => $nowDate
            );

            $result = Payment::insert($paidData);

            if ($result) {
                //change invoice paid status
                $updateData = array(
                    'paid_status'        => 1,
                );
                $result = Invoice::where('invoice_id', $request->paid_invoiceid)->update($updateData);

                DB::commit();

                //Send Message
                $updatedInvoice = Invoice::where('invoice_id',$request->paid_invoiceid)->first();
                $data = [];
                $data['student_id'] = $request->paid_studentid;
                $data['title']      = 'Paid Invoice';
                $data['description']= 'Payment Successful with the Date (from '.
                date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).' to '.date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).
                ') - Amount - '.$updatedInvoice->net_total;
                $data['remark']   ='';
                $msg = $this->regRepository->sendMessage($data);

                $guardianId = StudentInfo::where('student_id', $request->paid_studentid)->value('guardian_id');

                //send noti
                $data['receiver_id'] = $guardianId;
                $data['title'] = "Paid Invoice";
                $data['body']= 'Payment Successful with the Date (from '.
                date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).' to '.date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).
                ') - Amount - '.$updatedInvoice->net_total;
                $data['source'] = "Payment";
                $data['source_id'] = $request->paid_invoiceid;

                $noti = $this->notificationService->sendNotification($data); 

                return redirect(url('admin/invoice/list'))->with('success', 'Successfully Paid!');
            } else {
                return redirect()->back()->with('danger', 'Paid Fail!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger', 'Paid Fail !');
        }
    }

    //Get class with branch
    public function getClasswithBranch(Request $request)
    {

        /*$res = StudentRegistration::join("class_setup", function ($join) {
            $join->on("class_setup.id", "=", "student_registration.new_class_id");
        })
        ->leftJoin("grade_level_fee", function ($join) use ($request) {
            $join->on("grade_level_fee.academic_year_id", "=", "class_setup.academic_year_id")
                 ->where("grade_level_fee.grade_id", "=", "class_setup.grade_id")
                 ->where("grade_level_fee.branch_id", $request->branch_id);
        })
        ->select("class_setup.id as class_id", "class_setup.name as class_name", "grade_level_fee.grade_level_amount as grade_level_amount");
    
        $class_res = $res->get();*/
        $class_res = StudentRegistration::join('class_setup', 'class_setup.id', '=', 'student_registration.new_class_id')
            ->leftjoin('grade_level_fee', 'grade_level_fee.grade_id', '=', 'class_setup.grade_id')
            ->leftjoin('grade', 'grade.id', '=', 'grade_level_fee.grade_id')
            ->leftjoin('academic_year','academic_year.id','=','class_setup.academic_year_id')
            ->where('grade_level_fee.branch_id', $request->branch_id)
            ->select(
                "class_setup.id as class_id",
                "class_setup.name as class_name",
                DB::raw('MAX(grade_level_fee.grade_level_amount) as grade_level_amount'),
                'grade.name as grade_level',
                DB::raw('MAX(grade_level_fee.fee_type) as fee_type'),
                'academic_year.start_date as academic_year_start','academic_year.end_date as academic_year_end'
            )
            ->groupBy('class_setup.id', 'grade.id')
            ->get();

        if (!empty($class_res)) {
            return response()->json(array(
                'msg'            => 'found',
                'class_data'     => $class_res,
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    //To get the paid type
    function paidType()
    {
        return array(
            "1" => "Cash",
            "2" => "Bank",
            "3" => "Card"
        );
    }
}
