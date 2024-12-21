<?php

namespace App\Http\Controllers\Admin\Driver;

use App\Http\Controllers\Controller;
use App\Interfaces\RegistrationRepositoryInterface;
use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolBusTrack;
use App\Models\StudentInfo;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Services\NotificationService;

class SchoolBusTrackDetailController extends Controller
{
    private RegistrationRepositoryInterface $regRepository;
    private UserRepositoryInterface $userRepository;
    private $status;
    private $paidType;
    private $notificationService;

    public function __construct(UserRepositoryInterface $userRepository,RegistrationRepositoryInterface $regRepository,
                                NotificationService $notificationService) 
    {
        $this->userRepository = $userRepository;
        $this->regRepository      = $regRepository;
        $this->notificationService = $notificationService;

        $this->status = array(
            '0'=>'Pending',
            '1'=>'Confirm',
            '2'=>'Active',
            '3'=>'Inactive',
            '4'=>'Reject'
        );
        $this->paidType = array(
            "1" => "Cash",
            "2" => "Bank",
            "3" => "Card"
        );
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SchoolBusTrackDetailList(Request $request)
    {  
        $school_bus_track_id = $request->school_bus_track_id;
        $school_bus_track = SchoolBusTrack::where('school_bus_track.id',$school_bus_track_id)
                            ->leftjoin('driver_info','driver_info.driver_id','school_bus_track.driver_id')
                            ->select('school_bus_track.*','driver_info.*')
                            ->first();
        
        $school_bus_track_data = [];
        $school_bus_track_township='';
        if ($school_bus_track) {
            $school_bus_track_township= $school_bus_track->township;
            $school_bus_track_data['id']            = $school_bus_track_id;
            $school_bus_track_data['track_no']      = $school_bus_track->track_no;
            $school_bus_track_data['driver_id']     = $school_bus_track->driver_id;
            $school_bus_track_data['name']          = $school_bus_track->name;
            $school_bus_track_data['phone']         = $school_bus_track->phone;
            $school_bus_track_data['township']      = $school_bus_track_township;
            $school_bus_track_data['car_type']      = $school_bus_track->car_type;
            $school_bus_track_data['car_no']        = $school_bus_track->car_no;
            $school_bus_track_data['two_way_amount']        = $school_bus_track->two_way_amount;
            $school_bus_track_data['oneway_pickup_amount']  = $school_bus_track->oneway_pickup_amount;
            $school_bus_track_data['oneway_back_amount']    = $school_bus_track->oneway_back_amount;
            $school_bus_track_data['arrive_student_no']     = $school_bus_track->arrive_student_no;
        }
        $detail_data = DB::table('school_bus_track_detail')
                        ->leftJoin('ferry_student','ferry_student.id','school_bus_track_detail.ferry_student_id')
                        ->leftJoin('student_info','student_info.student_id','ferry_student.student_id')
                        ->where('school_bus_track_id',$school_bus_track_id)
                        ->select('ferry_student.*','student_info.name as name');
        $detail_data = $detail_data->paginate(20);
        $ferry_students = DB::table('ferry_student')
                        ->leftJoin('student_info','student_info.student_id','ferry_student.student_id')
                        ->where('status',0)
                        ->where('ferry_student.township',$school_bus_track_township)
                        ->select('ferry_student.*','student_info.name as name');
        $ferry_students = $ferry_students->paginate(20);

        $township      = $this->userRepository->getTownship();
        $ferryway      = $this->getFerryWay();
        
        return view('admin.driver.schoolbustrackdetail.index',[
            'school_bus_track_data'  =>$school_bus_track_data,
            'detail_data'            =>$detail_data,
            'ferry_students'         =>$ferry_students,
            'township'               =>$township,
            'status_list'            =>$this->status,
            'paid_type'              =>$this->paidType,
            'ferry_way'              =>$ferryway
        ]);
    }

    public function SchoolBusTrackDetailListwithGet($school_bus_track_id)
    {  
        
        $school_bus_track = SchoolBusTrack::where('school_bus_track.id',$school_bus_track_id)
                            ->leftjoin('driver_info','driver_info.driver_id','school_bus_track.driver_id')
                            ->select('school_bus_track.*','driver_info.*')
                            ->first();
        
        $school_bus_track_data = [];
        $school_bus_track_township='';
        if ($school_bus_track) {
            $school_bus_track_township= $school_bus_track->township;
            $school_bus_track_data['id']            = $school_bus_track_id;
            $school_bus_track_data['track_no']      = $school_bus_track->track_no;
            $school_bus_track_data['driver_id']     = $school_bus_track->driver_id;
            $school_bus_track_data['name']          = $school_bus_track->name;
            $school_bus_track_data['phone']         = $school_bus_track->phone;
            $school_bus_track_data['township']      = $school_bus_track_township;
            $school_bus_track_data['car_type']      = $school_bus_track->car_type;
            $school_bus_track_data['car_no']        = $school_bus_track->car_no;
            $school_bus_track_data['two_way_amount']        = $school_bus_track->two_way_amount;
            $school_bus_track_data['oneway_pickup_amount']  = $school_bus_track->oneway_pickup_amount;
            $school_bus_track_data['oneway_back_amount']    = $school_bus_track->oneway_back_amount;
            $school_bus_track_data['arrive_student_no']     = $school_bus_track->arrive_student_no;
        }
        $detail_data = DB::table('school_bus_track_detail')
                        ->leftJoin('ferry_student','ferry_student.id','school_bus_track_detail.ferry_student_id')
                        ->leftJoin('student_info','student_info.student_id','ferry_student.student_id')
                        ->where('school_bus_track_id',$school_bus_track_id)
                        ->select('ferry_student.*','student_info.name as name');
        $detail_data = $detail_data->paginate(20);
        $ferry_students = DB::table('ferry_student')
                        ->leftJoin('student_info','student_info.student_id','ferry_student.student_id')
                        ->where('status',0)
                        ->where('ferry_student.township',$school_bus_track_township)
                        ->select('ferry_student.*','student_info.name as name');
        $ferry_students = $ferry_students->paginate(20);
        
        $township      = $this->userRepository->getTownship();
        $ferryway      = $this->getFerryWay();
        
        return view('admin.driver.schoolbustrackdetail.index',[
            'school_bus_track_data'  =>$school_bus_track_data,
            'detail_data'            =>$detail_data,
            'ferry_students'         =>$ferry_students,
            'township'               =>$township,
            'status_list'            =>$this->status,
            'paid_type'              =>$this->paidType,
            'ferry_way'              =>$ferryway
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addFerryStudentDetail(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'school_bus_track_id'   =>'required',
        ]); 
        $school_bus_track_id = $request->school_bus_track_id;
       
        $checkFerryStudent = $request->checkFerryStudent;
        if (empty($checkFerryStudent)) {
            return redirect(url('admin/school_bus_track_detail/list/'.$school_bus_track_id))->with('danger','Please check ferry student !');
        }
        if ( count($request->checkFerryStudent) > $request->available_students ) {
            return redirect(url('admin/school_bus_track_detail/list/'.$school_bus_track_id))->with('danger','Added student count is exceeded than available students.');
        }

        DB::beginTransaction();
        try{
            for ($i=0;$i<count($checkFerryStudent);$i++) {
                $ferry_student_id = $checkFerryStudent[$i];
               
                $insertData = array(
                    'school_bus_track_id'=> $school_bus_track_id,
                    'ferry_student_id'  => $ferry_student_id[$i],
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $res=DB::table('school_bus_track_detail')->insert($insertData);
                
                if (!$res) {
                    return redirect(url('admin/school_bus_track_detail/list/'.$school_bus_track_id))->with('danger','Ferry Student Added Fail !');
                }
                //update ferry student status
                $updateres = DB::table('ferry_student')->where('id',$ferry_student_id[$i])->update(['status'=>'1']);
            }
            DB::commit();
            return redirect(url('admin/school_bus_track_detail/list/'.$school_bus_track_id))->with('success','Ferry Student Added Successfully!');
      
        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect(url('admin/school_bus_track_detail/list/'.$school_bus_track_id))->with('danger','Ferry Student Added Fail !');
        }       
          
    }

    //To make paid the invoice
    public function paidFerry(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'paid_studentid'        => 'required',
            'paid_registrationid'   => 'required',
            'paid_paiddate_from'    => 'required',
            'paid_paiddate_to'      => 'required',
            'paid_nettotal'         => 'required',
            'paid_paidtype'         => 'required',
            'paid_paiddate'         => 'required',
        ]);

        DB::beginTransaction();
        try {
            $invoiceID = $this->regRepository->generatePaymentInvoiceID();
            
            //substract card amount
            if ($request->paid_paidtype == '3' ) {//card
                $cardId = StudentInfo::where('student_id', $request->paid_studentid)->value('card_id');
                if ($cardId) {
                    $checkCurrent = Wallet::where('card_id',$cardId)->first();
                    if (empty($checkCurrent)) {
                        return redirect()->back()->with('danger', 'Plese fill the card amount firstly.');
                    }
                    $totalAmount = 0;
                    $totalAmount =  $checkCurrent->total_amount - $request->paid_nettotal;
                    if ($totalAmount < 0) {
                        return redirect()->back()->with('danger', 'Insufficutent Card Amount for Payment!');
                    } else {
                        $deletCurrent = Wallet::where('id',$checkCurrent->id)->delete();
                        $walletinsertData = array(
                            'card_id'           =>$cardId,
                            'student_id'        =>$request->paid_studentid,
                            'amount'            =>$request->paid_nettotal,
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
                                'status_id'         =>$invoiceID,
                                'amount'            =>$request->paid_nettotal,
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
                   
            //create invoice
            $invoiceData = array(
                'invoice_id'        => $invoiceID,
                'student_id'        => $request->paid_studentid,
                'payment_type'      => 3, // ferry paid
                'pay_from_period'   => $request->paid_paiddate_from,
                'pay_to_period'     => $request->paid_paiddate_to,
                'grade_level_fee'   => '0',
                'total_amount'      => $request->paid_nettotal,
                'discount_percent'  => '0',
                'net_total'         => $request->paid_nettotal,
                'paid_status'       => '1',
                'created_by'        => $login_id,
                'updated_by'        => $login_id,
                'created_at'        => $nowDate,
                'updated_at'        => $nowDate
            );

            $invoiceResult = Invoice::insert($invoiceData);
            //paid
            $paidData = array(
                'invoice_id'        => $invoiceID,
                'student_id'        => $request->paid_studentid,
                'paid_date'         => $request->paid_paiddate,
                'paid_type'         => $request->paid_paidtype,
                'remark'            => $request->paid_remark,
                'created_by'        => $login_id,
                'updated_by'        => $login_id,
                'created_at'        => $nowDate,
                'updated_at'        => $nowDate
            );
            $paymentResult = Payment::insert($paidData);

            if ($invoiceResult && $paymentResult) {

                DB::commit();

                //change ferry student status to active
                $updateres = DB::table('ferry_student')->where('id',$request->ferry_student_id)->update(['status'=>'2']);

                //Send Message
                $updatedInvoice = Invoice::where('invoice_id',$invoiceID)->first();
                $data = [];
                $data['student_id'] = $request->paid_studentid;
                $data['title']      = 'Paid Ferry';
                $data['description']= 'Payment Successful with the Date (from '.
                date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).' to '.date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).
                ') - Amount - '.$updatedInvoice->net_total;
                $data['remark']   ='';
                $msg = $this->regRepository->sendMessage($data);

                $guardianId = StudentInfo::where('student_id', $request->paid_studentid)->value('guardian_id');

                //send noti
                $data['receiver_id'] = $guardianId;
                $data['title'] = "Paid Ferry";
                $data['body']= 'Payment Successful with the Date (from '.
                date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).' to '.date('Y-m-d',strtotime($updatedInvoice->pay_from_period)).
                ') - Amount - '.$updatedInvoice->net_total;
                $data['source'] = "Payment";
                $data['source_id'] = $invoiceID;

                $noti = $this->notificationService->sendNotification($data); 

                return redirect(url('admin/school_bus_track_detail/list/'.$request->school_bus_track_id))->with('success', 'Successfully Ferry Paid!');
            } else {
                return redirect(url('admin/school_bus_track_detail/list/'.$request->school_bus_track_id))->with('danger', 'Paid Fail!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage());
            return redirect(url('admin/school_bus_track_detail/list/'.$request->school_bus_track_id))->with('danger', 'Ferry Paid Fail !');
        }
    }

    public function getFerryWay() {
        return [
            '1' => 'One Way PickUp',
            '2' => 'One Way Back',
            '3' => 'Two Way'
        ];
    }
}
