<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FoodOrder;
use App\Models\Invoice;
use App\Models\Menu;
use App\Models\Payment;
use App\Models\StudentInfo;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class FoodOrderController extends Controller
{

    private RegistrationRepositoryInterface $regRepository;
    private UserRepositoryInterface $userRepository;
    private $notificationService;

    public function __construct(UserRepositoryInterface $userRepository,RegistrationRepositoryInterface $regRepository, 
    NotificationService $notificationService)
    {
        $this->userRepository = $userRepository;
        $this->regRepository      = $regRepository;
        $this->notificationService = $notificationService;
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function foodOrderList(Request $request)
    {
        $res = FoodOrder::select('food_order.*');
        if ($request['action'] == 'search') {
            if (request()->has('order_invoiceid') && request()->input('order_invoiceid') != '') {
                $res->where('invoice_id', request()->input('order_invoiceid'));
            }
        }else {
            request()->merge([
                'order_invoiceid'      => null,
            ]);
        }       
        $res = $res->paginate(20);

        return view('admin.shop.foodorder.foodorder_list',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function foodOrderCreate()
    {
        $menu_list = $this->getMenus();
        return view('admin.shop.foodorder.foodorder_create',[
            'menu_list'   => $menu_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function foodOrderSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'card_id'     =>'required',
        ]); 

        $checkMenu = $request->checkMenu;
        if (empty($checkMenu)) {
            return redirect()->back()->with('danger','Please check menu !');
        }
       
        DB::beginTransaction();
        try{
            //generate invoice id
            $invoice_id = $this->regRepository->generatePaymentInvoiceID();
            $studentId = StudentInfo::where('card_id', $request->card_id)->value('student_id');
            if (!$studentId) {
                return redirect()->back()->with('danger', 'Student Data not found!');
            }

            $toal_amount =0 ;
            for ($i=0;$i<count($checkMenu);$i++) {
                $menu_id = $checkMenu[$i];
                $qty_data     = $request[$menu_id.'-qty'];
                $qty_data     = explode('-',$qty_data);
                $qty     = $qty_data[0];
                $price   = $qty_data[2];

                $remark     = $request[$menu_id.'-remark'];
                if ($remark==null) {
                    $remark = "";
                }
                
                $insertItem = array(
                    'invoice_id'        => $invoice_id,
                    'menu_id'           => $menu_id,
                    'price'             => $price,
                    'quantity'          => $qty,
                    'description'       => $remark,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $res=DB::table('food_order_items')->insert($insertItem);
                if (!$res) {
                    return redirect()->back()->with('danger','Food Order Fail !');
                } else {
                    $toal_amount += $qty * $price;
                }
            }
            //check payment            
            $checkCurrent = Wallet::where('card_id',$request->card_id)->first();
            if (!$checkCurrent) {
                return redirect()->back()->with('danger', 'Plese fill the card amount firstly.');
            }
            $totalAmount = 0;
            $totalAmount =  $checkCurrent->total_amount - $toal_amount;
            if ($totalAmount < 0) {
                return redirect()->back()->with('danger', 'Insufficutent Card Amount for Payment!');
            } else {
                $deletCurrent = Wallet::where('id',$checkCurrent->id)->delete();
                $walletinsertData = array(
                    'card_id'           =>$request->card_id,
                    'student_id'        =>$studentId,
                    'amount'            =>$toal_amount,
                    'total_amount'      =>$totalAmount,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $wallet_id=Wallet::insertGetId($walletinsertData);
                if ($wallet_id) {
                    $wallethistoryinsertData = array(
                        'card_id'           =>$request->card_id,
                        'student_id'        =>$studentId,
                        'status'            =>'2', //Out status
                        'status_id'         =>$invoice_id,
                        'amount'            =>$toal_amount,
                        'created_by'        =>$login_id,
                        'updated_by'        =>$login_id,
                        'created_at'        =>$nowDate,
                        'updated_at'        =>$nowDate
                    );
                    $wallethistoryresult=WalletHistory::insert($wallethistoryinsertData);
                }
            }

            $insertOrder = array(
                'invoice_id'        => $invoice_id,
                'card_id'           => $request->card_id,
                'total_amount'      => $toal_amount,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $res=DB::table('food_order')->insert($insertOrder);

            //create invoice
            $invoiceData = array(
                'invoice_id'        => $invoice_id,
                'student_id'        => $studentId,
                'payment_type'      => 4, // food order payment
                'pay_from_period'   => null,
                'pay_to_period'     => null,
                'grade_level_fee'   => '0',
                'total_amount'      => $toal_amount,
                'discount_percent'  => '0',
                'net_total'         => $toal_amount,
                'paid_status'       => '1',
                'created_by'        => $login_id,
                'updated_by'        => $login_id,
                'created_at'        => $nowDate,
                'updated_at'        => $nowDate
            );

            $invoiceResult = Invoice::insert($invoiceData);
            //paid
            $paidData = array(
                'invoice_id'        => $invoice_id,
                'student_id'        => $studentId,
                'paid_date'         => $nowDate,
                'paid_type'         => 3,
                'remark'            => 'food order',
                'created_by'        => $login_id,
                'updated_by'        => $login_id,
                'created_at'        => $nowDate,
                'updated_at'        => $nowDate
            );
            $paymentResult = Payment::insert($paidData);

            if ($invoiceResult && $paymentResult) {

                DB::commit();

                //Send Message
                $updatedInvoice = Invoice::where('invoice_id',$invoice_id)->first();
                $data = [];
                $data['student_id'] = $studentId;
                $data['title']      = 'Food Order';
                $data['description']= 'Payment Successful for your food order with Amount -'.$updatedInvoice->net_total;
                $data['remark']   ='';
                $msg = $this->regRepository->sendMessage($data);

                $guardianId = StudentInfo::where('student_id', $studentId)->value('guardian_id');

                //send noti
                $data['receiver_id'] = $guardianId;
                $data['title'] = "Food Order";
                $data['body']= 'Payment Successful for your food order with Amount -'.$updatedInvoice->net_total;
                $data['source'] = "Payment";
                $data['source_id'] = $invoice_id;

                $noti = $this->notificationService->sendNotification($data); 

                return redirect(url('admin/food_order/list'))->with('success','Food Order Created Successfully!');
            } else {
                DB::rollback();
                return redirect()->back()->with('danger','Food Order Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Food Order Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function foodOrderEdit($id)
    {    
        $res = FoodOrder::where('id',$id)->get();
        $order_item = DB::table('food_order_items')
                        ->whereNull('deleted_at')
                        ->where('invoice_id',$res[0]['invoice_id'])
                        ->get();
        $order_item_array=[];
        foreach ($order_item as $item) {
            $item_array['quantity']  = $item->quantity;
            $item_array['price']     = $item->price;
            $item_array['qty_total'] = $item->quantity * $item->price;
            $order_item_array[$item->menu_id] = $item_array;
        }
        $menu_list = $this->getMenus();
        return view('admin.shop.foodorder.foodorder_update',[
            'menu_list'   => $menu_list,
            'order_item'  => $order_item_array,
            'result'      =>$res
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function foodOrderUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'card_id'     =>'required',
        ]); 

        $checkMenu = $request->checkMenu;
        if (empty($checkMenu)) {
            return redirect()->back()->with('danger','Please check menu !');
        }

        DB::beginTransaction();
        try{
            $checkData = FoodOrder::where('id',$id)->first();
            if (!empty($checkData)) {
                $invoice_id = $checkData['invoice_id'];
                //To delete old food item
                $res = DB::table('food_order_items')->where('invoice_id',$invoice_id)->delete();

                $toal_amount =0 ;
                for ($i=0;$i<count($checkMenu);$i++) {
                    $menu_id = $checkMenu[$i];
                    $qty_data     = $request[$menu_id.'-qty'];
                    $qty_data     = explode('-',$qty_data);
                    $qty     = $qty_data[0];
                    $price   = $qty_data[2];

                    $remark     = $request[$menu_id.'-remark'];
                    if ($remark==null) {
                        $remark = "";
                    }
                    
                    $insertItem = array(
                        'invoice_id'        => $invoice_id,
                        'menu_id'           => $menu_id,
                        'price'             => $price,
                        'quantity'          => $qty,
                        'description'       => $remark,
                        'created_by'        =>$login_id,
                        'updated_by'        =>$login_id,
                        'created_at'        =>$nowDate,
                        'updated_at'        =>$nowDate
                    );
                    $res=DB::table('food_order_items')->insert($insertItem);
                    if (!$res) {
                        return redirect()->back()->with('danger','Food Order Fail !');
                    } else {
                        $toal_amount += $qty * $price;
                    }
                }
                $insertOrder = array(
                    'card_id'           => $request->card_id,
                    'total_amount'      => $toal_amount,
                    'updated_by'        => $login_id,
                    'updated_at'        => $nowDate
                );
                $result=DB::table('food_order')->where('id',$id)->update($insertOrder);
                        
                if($result){
                    DB::commit();               
                    return redirect(url('admin/food_order/list'))->with('success','Food Order Updated Successfully!');
                }else{
                    return redirect()->back()->with('danger','Food Order Updated Fail !');
                }
            } else {
                return redirect()->back()->with('danger','Food Order Updated Data not found !');
            }            

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Food Order Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function foodOrderDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = FoodOrder::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = FoodOrder::where('id',$id)->delete();
                if($res){
                    //To delete food item
                    $res = DB::table('food_order_items')->where('invoice_id',$checkData['invoice_id'])->delete();
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this food order.');
            }
            DB::commit();
            //To return list
            return redirect(route('food_order.index'))->with('success','Food Order Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Food Order Deleted Failed!');
        }
        
    }

    public function getMenus() {
        $login_id = Auth::user()->user_id;
        $menu_list = Menu::where('created_by',$login_id)->get();      
        return $menu_list;
    }
}
