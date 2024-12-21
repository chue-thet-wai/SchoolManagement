<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AcademicYear;
use App\Models\ClassSetup;
use App\Models\StudentGuardian;

class HomeController extends Controller
{
    public function parentProfile() {
        $id = session()->get('guardian_id');
        $currentDate = date("Y-m-d");

        //current academic year
        $currentAcademic = AcademicYear::where('start_date', '<=', $currentDate)
                        ->where('end_date', '>=', $currentDate)
                        ->first();
        $academicId = $currentAcademic ? $currentAcademic->id : null;

        //To get guardian data
        $guardian_data = DB::table('student_guardian')->where('id',$id)->first();

        //current academic year class
        $currentClassIds = ClassSetup::where('academic_year_id', $academicId)
                        ->pluck('id')
                        ->toArray();

        //To get student id
        $student_data  = DB::table('student_registration')
                        ->leftjoin('student_info','student_registration.student_id','student_info.student_id')
                        ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                        ->leftjoin('grade','grade.id','class_setup.grade_id')
                        ->where('guardian_id',$id)
                        ->whereIn('new_class_id',$currentClassIds)
                        ->whereNull('student_registration.deleted_at')
                        ->select('student_registration.id','student_info.name',
                        'student_registration.student_id','grade.name as grade_name')
                        ->get();

        return view('parent.parent_profile',[
            'guardian_data'      =>$guardian_data,
            'student_data'       =>$student_data
            ]);       
    }

    public function editProfile() {
        $id = session()->get('guardian_id');
        $guardian_data = DB::table('student_guardian')->where('id',$id)->first();
        return view('parent.parent_editprofile',[
            'guardian_data' => $guardian_data
        ]);        
    }

    public function editProfileSubmit(Request $request) {
        $email    = $request->email;
        $phone    = $request->phone;
        $address  = $request->address;
        $id       = session()->get('guardian_id');
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try{
            $updateData = array(
                'email'           =>$email,
                'phone'           =>$phone,
                'address'         =>$address,
                'updated_by'      =>$id,
                'updated_at'      =>$nowDate

            );           
            $result=StudentGuardian::where('id',$id)->update($updateData);                      
            if($result){ 
                DB::commit(); 
                return redirect(url('parent/home'))->with('success','Profile Edited!');              
            }else{
                return redirect()->back()->with('danger','Profile Edited Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Profile Edited Fail ');
        }    
    }

    public function changePassword() {
        return view('parent.parent_changepassword');        
    }

    public function changePasswordSubmit(Request $request) {
        $current_password    = $request->current_password;
        $new_password        = $request->new_password;
        $confirm_password    = $request->confirm_password;

        $id       = session()->get('guardian_id');
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try{
            $updateData = array(
                'password'        =>bcrypt($new_password),
                'updated_by'      =>$id,
                'updated_at'      =>$nowDate

            );           
            $result=StudentGuardian::where('id',$id)->update($updateData);                      
            if($result){ 
                DB::commit(); 
                return redirect(url('parent/home'))->with('success','Success Password Change!');              
            }else{
                return redirect()->back()->with('danger','Fail Password Change!');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Fail Password Change!');
        }     
    }

    public function parentContacts() {
        $id = session()->get('guardian_id');
        //$guardian_data = DB::table('student_guardian')->where('id',$id)->first();   
        $student_data  = DB::table('student_info')
                        ->where('guardian_id',$id)
                        ->whereNull('deleted_at')
                        ->first(); 
        $contact_data = [];
        $contact ['name']         = $student_data->father_name;
        $contact['relationship']  = 'Father';
        $contact['phone']         = $student_data->father_phone;
        $contact_data[] = $contact;
        $contact ['name']         = $student_data->mother_name;
        $contact['relationship']  = 'Mother';
        $contact['phone']         = $student_data->mother_phone;
        $contact_data[] = $contact;
        return view('parent.parent_contacts',[
            'contact_data' => $contact_data
        ]);
    }
}
