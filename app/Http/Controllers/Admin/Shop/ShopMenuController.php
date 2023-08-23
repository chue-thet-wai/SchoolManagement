<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ShopMenuController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopMenuList(Request $request)
    {
        $res = Menu::select('menu.*');
        if ($request['action'] == 'search') {
            if (request()->has('menu_name') && request()->input('menu_name') != '') {
                $res->where('name', request()->input('menu_name'));
            }
        }else {
            request()->merge([
                'menu_name'      => null,
            ]);
        }       
        $res = $res->paginate(20);

        return view('admin.shop.menu.menu_list',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopMenuCreate()
    {
        return view('admin.shop.menu.menu_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function shopMenuSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'price'     =>'required|integer',
            'name'      =>'required',
            'menu_image'=>'required | mimes:jpeg,jpg,png | max:1000',
        ]); 

        if($request->hasFile('menu_image')){
            $image=$request->file('menu_image');
            $extension = $image->extension();
            $image_name = time() . "." . $extension;
        }else{
            $image_name="";
        } 
       
        DB::beginTransaction();
        try{
            $insertData = array(
                'name'              =>$request->name,
                'price'             =>$request->price,
                'menu_image'        =>$image_name,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=Menu::insert($insertData);
                        
            if($result){   
                if ($image_name != '') {
                    $image->move(public_path('assets/menu'),$image_name); 
                }   
                DB::commit();
                return redirect(url('admin/menu/list'))->with('success','Menu Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Menu Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Menu Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shopMenuEdit($id)
    {    
        $res = Menu::where('id',$id)->get();
        return view('admin.shop.menu.menu_update',[
            'result'=>$res]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shopMenuUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'price'     =>'required|integer',
            'name'      =>'required',
            'menu_image'=>'required | mimes:jpeg,jpg,png | max:1000',
        ]); 

        if($request->hasFile('menu_image')){
            $previous_img=$request->previous_image;
            @unlink(public_path('/assets/menu/'. $previous_img));

            $image=$request->file('menu_image');
            $extension = $image->extension();
            $image_name = time() . "." . $extension;
        }else{
            $image_name="";
        } 

        DB::beginTransaction();
        try{
            $menuData = array(
                'name'              =>$request->name,
                'price'             =>$request->price,
                'menu_image'        =>$image_name,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate

            );
            
            $result=Menu::where('id',$id)->update($menuData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/menu'),$image_name);  
                }
                DB::commit();               
                return redirect(url('admin/menu/list'))->with('success','Menu Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Menu Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Menu Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function shopMenuDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = Menu::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = Menu::where('id',$id)->delete();
                if($res){
                    //To delete image
                    $image=$checkData['menu_image'];
                    @unlink(public_path('/assets/menu/'. $image));
                }
            }else{
                return redirect()->back()->with('error','There is no result with this menu.');
            }
            DB::commit();
            //To return list
            return redirect(route('menu.index'))->with('success','Menu Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Menu Deleted Failed!');
        }
        
    }
}
