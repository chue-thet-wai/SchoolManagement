<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function BranchList(Request $request)
    {  
        $res = Branch::select('branch.*');
        if ($request['action'] == 'search') {
            if (request()->has('branch_name') && request()->input('branch_name') != '') {
                $res->where('name','Like', '%' . request()->input('branch_name') . '%');
            }
            if (request()->has('branch_phone') && request()->input('branch_phone') != '') {
                $res->where('phone', request()->input('branch_phone'));
            }
        }else {
            request()->merge([
                'branch_name'      => null,
                'branch_phone'     => null,
            ]);
        }     
        $res = $res->paginate(20);   

        return view('admin.category.branch_index',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.branch_registration',[
            'action'=>'Add'
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
            'name'      =>'required|min:3',
        ]);
        $insertData = array(
            'name'           =>$request->name,
            'phone'          =>$request->phone,
            'address'        =>$request->address,
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id,
            'created_at'     =>$nowDate,
            'updated_at'     =>$nowDate
        );

        $result=Branch::insert($insertData);
        
        if($result){
            return redirect(url('admin/branch/list'))->with('success','Branch Added Successfully!');
        }else{
            return redirect()->back()->with('danger','Branch Added Fail !');
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
        $update_res = Branch::where('id',$id)->get();
        return view('admin.category.branch_registration',[
            'result'      => $update_res,
            'action'      => 'Update'
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
            'name'            =>'required|min:3'
        ]); 

        DB::beginTransaction();
        try{
            $updateData = array(
                'name'           =>$request->name,
                'phone'          =>$request->phone,
                'address'        =>$request->address,
                'updated_by'     =>$login_id,
                'updated_at'     =>$nowDate
            );
            
            $result=Branch::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/branch/list'))->with('success','Branch Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Branch Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Branch Updared Fail !');
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
        $checkData = Branch::where('id',$id)->get()->toArray();

        if (!empty($checkData)) {
            
            $res = Branch::where('id',$id)->delete();
            if($res){
                return redirect(url('admin/branch/list'))->with('success','Branch Deleted Successfully!');                
            }
        }else{
            return redirect()->back()->with('error','There is no result with this branch.');
        }
    }
}
