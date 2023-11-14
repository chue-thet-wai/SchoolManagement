<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = Section::paginate(20);
        return view('admin.category.section_index',['list_result' => $res]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SectionList(Request $request)
    {  
        $res = Section::select('section.*');
        if ($request['action'] == 'search') {
            if (request()->has('section_name') && request()->input('section_name') != '') {
                $res->where('name','Like', '%' . request()->input('section_name') . '%');
            }
        }else {
            request()->merge([
                'section_name'      => null,
            ]);
        }     
        $res = $res->paginate(20);  

        return view('admin.category.section_index',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.section_registration',[
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
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id,
            'created_at'     =>$nowDate,
            'updated_at'     =>$nowDate
        );

        $result=Section::insert($insertData);
        
        if($result){
            $res = Section::paginate(10);
            return redirect(route('section.index',['list_result' => $res]))
                            ->with('success','Section Added Successfully!');
        }else{
            return redirect()->back()->with('danger','Section Added Fail !');
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
    
        $update_res = Section::where('id',$id)->get();
        return view('admin.category.section_registration',[
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
                'name'               =>$request->name,
                'updated_by'         =>$login_id,
                'updated_at'         =>$nowDate
            );
            
            $result=Section::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(route('section.index'))->with('success','Section Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Section Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Section Updared Fail !');
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
        $checkData = Section::where('id',$id)->get()->toArray();
        if (!empty($checkData)) {
            
            $res = Section::where('id',$id)->delete();
            if($res){
                $listres = Section::paginate(10);
                return redirect(route('section.index',['list_result' => $listres]))
                            ->with('success','Section Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this section.');
        }
    }
}
