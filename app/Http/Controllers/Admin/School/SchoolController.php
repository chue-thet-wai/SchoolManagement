<?php

namespace App\Http\Controllers\Admin\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SchoolController extends Controller
{

    private $status;
    public function __construct() 
    {
        $this->status = array(
            '0' => 'Inactive',
            '1' => 'Active'
        );
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function schoolList(Request $request)
    {
        $res = School::select('school.*');
        if ($request['action'] == 'search') {
            if (request()->has('school_name') && request()->input('school_name') != '') {
                $res->where('name','Like', '%' . request()->input('school_name') . '%');
            }
        }else {
            request()->merge([
                'school_name'      => null,
            ]);
        }       
        $res = $res->paginate(20);

        return view('admin.school.school_list',[
            'list_result' => $res,
            'status'      => $this->status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function schoolCreate()
    {        
        return view('admin.school.school_create',[
            'status'      => $this->status
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function schoolSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'                =>'required',
            'code'                =>'required',
            'url'                 =>'required',
            'school_logo'         =>'required|mimes:jpeg,jpg,png|max:1000',   
        ]); 

        $latestId = School::latest()->value('id');

        if ($request->hasFile('school_logo')) {
            $image = $request->file('school_logo');
            $extension = $image->extension();
            $image_name = (intval($latestId === null ? 0 : $latestId) + 1). "_" . time() . "." . $extension;
        } else {
            $image_name = "";
        }
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'name'              =>$request->name,
                'code'              =>$request->code,
                'url'               =>$request->url,
                'logo'              =>$image_name,
                'status'            =>$request->status,
                'start_date'        =>$request->start_date,
                'end_date'          =>$request->end_date,
                'note'              =>$request->note,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=School::insert($insertData);
                        
            if($result){ 
                if ($image_name != '') {
                    $image->move(public_path('assets/school_logo'), $image_name);
                }
                DB::commit();
                return redirect(url('admin/school_registration/list'))->with('success','School Created Successfully!');
            }else{
                return redirect()->back()->with('danger','School Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','School Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function schoolEdit($id)
    {    
        $res = School::where('id',$id)->get();
        return view('admin.school.school_update',[
            'result'      =>$res,
            'status'      => $this->status
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function schoolUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'                =>'required',
            'code'                =>'required',
            'url'                 =>'required',
            'school_logo'         =>'mimes:jpeg,jpg,png|max:1000',   
        ]); 

        if ($request->hasFile('school_logo')) {

            $previous_img = $request->previous_image;
            @unlink(public_path('/assets/school_logo/' . $previous_img));

            $image = $request->file('school_logo');
            $extension = $image->extension();
            $image_name = $id . "_" . time() . "." . $extension;
        } else {
            $image_name = "";
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'name'              =>$request->name,
                'code'              =>$request->code,
                'url'               =>$request->url,
                //'logo'              =>$request->logo,
                'status'            =>$request->status,
                'start_date'        =>$request->start_date,
                'end_date'          =>$request->end_date,
                'note'              =>$request->note,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate

            );
            if ($image_name != "") {
                $updateData['logo'] = $image_name;
            }
            
            $result=School::where('id',$id)->update($updateData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/school_logo'), $image_name);
                }
                DB::commit();               
                return redirect(url('admin/school_registration/list'))->with('success','School Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','School Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','School Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function schoolDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = School::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = School::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/school_registration/list'))->with('success','School Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this school.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','School Deleted Failed!');
        }
    }
}
