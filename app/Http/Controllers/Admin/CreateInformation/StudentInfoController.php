<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use App\Http\Controllers\Controller;
use App\Models\StudentInfo;
use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentInfoController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository     = $userRepository;
    }

    public function studentInfoList(Request $request) {
        $res = StudentInfo::select('student_info.*');
        if ($request['action'] == 'search') {
            if (request()->has('studentinfo_name') && request()->input('studentinfo_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('studentinfo_name') . '%');
            }
            if (request()->has('studentinfo_studentid') && request()->input('studentinfo_studentid') != '') {
                $res->where('student_id', request()->input('studentinfo_studentid'));
            }
            if (request()->has('studentinfo_gender') && request()->input('studentinfo_gender') != '') {
                $res->where('gender', request()->input('studentinfo_gender'));
            }
        }else {
            request()->merge([
                'studentinfo_name'      => null,
                'studentinfo_studentid' => null,
                'studentinfo_gender'    => null,
            ]);
        }       
        $res = $res->paginate(20);
        $gender        = $this->userRepository->getGender();
        return view('admin.createinformation.studentInfo.studentlist',['list_result' => $res,'gender' => $gender]);
    }

    public function studentInfoEdit($id) {
        $res = StudentInfo::where('student_id',$id)->get();
        $gender        = $this->userRepository->getGender();
        $township      = $this->userRepository->getTownship();
        return view('admin.createinformation.studentInfo.studentupdate',['result'=>$res,'gender' => $gender,'township'=>$township]);
    }

    public function studentInfoUpdate(Request $request,$id) {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'            =>'required|min:3',
            'card_id'         => 'required',
            'student_profile' => 'mimes:jpeg,jpg,png|max:1000',
        ]); 

        if ($request->hasFile('student_profile')) {

            $previous_img = $request->previous_image;
            @unlink(public_path('/assets/student_images/' . $previous_img));

            $image = $request->file('student_profile');
            $extension = $image->extension();
            $image_name = $id . "_" . time() . "." . $extension;
        } else {
            $image_name = "";
        }

        DB::beginTransaction();
        try{
            $infoData = array(
                'card_id'           =>$request->card_id,
                'name'              =>$request->name,
                'name_mm'           =>$request->name_mm,
                'date_of_birth'     =>$request->date_of_birth,
                'gender'            =>$request->gender,
                'religion'          =>$request->religion,
                'nationality'       =>$request->nationality,
                'township'          =>$request->township,
                'father_name'       =>$request->father_name,
                'father_name_mm'    =>$request->father_name_mm,
                'mother_name'       =>$request->mother_name,
                'mother_name_mm'    =>$request->mother_name_mm,
                'father_phone'      =>$request->father_phone,
                'mother_phone'      =>$request->mother_phone,
                'address_1'         =>$request->address_1,
                'address_2'         =>$request->address_2,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );

            if ($image_name != "") {
                $infoData['student_profile'] = $image_name;
            }

            $result=StudentInfo::where('student_id',$id)->update($infoData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/student_images'), $image_name);
                }
                DB::commit();               
                return redirect(url('admin/student_info/list'))->with('success','Student Information Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Student Information Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Student Information Updared Fail !');
        }          
    }


}
