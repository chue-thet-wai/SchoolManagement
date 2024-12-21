<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use App\Http\Controllers\Controller;
use App\Models\StudentGuardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuardianInfoController extends Controller
{
    public function __construct() 
    {
        
    }

    public function guardianInfoList(Request $request) {
        $res = StudentGuardian::select('student_guardian.*');
        if ($request['action'] == 'search') {
            if (request()->has('guardianinfo_name') && request()->input('guardianinfo_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('guardianinfo_name') . '%');
            }
            if (request()->has('guardianinfo_phone') && request()->input('guardianinfo_phone') != '') {
                $res->where('phone', request()->input('guardianinfo_phone'));
            }
        }else {
            request()->merge([
                'guardianinfo_name'      => null,
                'guardianinfo_phone'     => null
            ]);
        }       
        $res = $res->paginate(20);
       
        return view('admin.createinformation.guardianInfo.guardianlist',[
            'list_result' => $res
        ]);
    }

    public function guardianInfoEdit($id) {
        $res = StudentGuardian::where('id',$id)->get();
        
        return view('admin.createinformation.guardianInfo.guardianupdate',['result'=>$res]);
    }

    public function guardianInfoUpdate(Request $request,$id) {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'            =>'required|min:3',
            'guardian_photo'  => 'mimes:jpeg,jpg,png|max:1000',
        ]); 

        if ($request->hasFile('guardian_photo')) {

            $previous_img = $request->previous_image;
            @unlink(public_path('/assets/guardian_images/' . $previous_img));

            $image = $request->file('guardian_photo');
            $extension = $image->extension();
            $image_name = $id . "_" . time() . "." . $extension;
        } else {
            $image_name = "";
        }

        DB::beginTransaction();
        try{
            $infoData = array(
                'name'              =>$request->name,
                'phone'             =>$request->phone,
                'secondary_phone'   =>$request->secondary_phone,
                'email'             =>$request->email,
                'nrc'               =>$request->nrc,
                //'password'          =>bcrypt($request->password),
                'address'           =>$request->address,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            if ($image_name != "") {
                $infoData['photo'] = $image_name;
            }
            $guardian = StudentGuardian::where('id', $id)->first();
            if ($guardian && $guardian->password != $request->password) {
                $infoData['password'] = bcrypt($request->password);
            }
            $result=StudentGuardian::where('id',$id)->update($infoData);
                      
            if($result){
                DB::commit();   
                if ($image_name != "") {
                    $image->move(public_path('assets/guardian_images'), $image_name);
                }            
                return redirect(url('admin/guardian_info/list'))->with('success','Student Guardian Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Student Guardian Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Student Guardian Updared Fail !');
        }          
    }


}
