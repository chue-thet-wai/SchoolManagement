<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FoodOrder;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class FoodOrderController extends Controller
{

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
            $invoice_id = '12321';
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
                'invoice_id'        => $invoice_id,
                'card_id'           => $request->card_id,
                'total_amount'      => $toal_amount,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $res=DB::table('food_order')->insert($insertOrder);

            DB::commit();
            return redirect(url('admin/menu/list'))->with('success','Food Order Created Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Food Order Created Fail !');
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
            return redirect()->back()->with('error','Food Order Updared Fail !');
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
                return redirect()->back()->with('error','There is no result with this food order.');
            }
            DB::commit();
            //To return list
            return redirect(route('food_order.index'))->with('success','Food Order Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Food Order Deleted Failed!');
        }
        
    }

    public function getMenus() {
        $login_id = Auth::user()->user_id;
        $menu_list = Menu::where('created_by',$login_id)->get();      
        return $menu_list;
    }
}
