<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\TeacherInfo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;

class TeacherInfoController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository,UserRepositoryInterface $userRepository) 
    {
        $this->categoryRepository = $categoryRepository;
        $this->userRepository     = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = TeacherInfo::leftJoin("users", "users.user_id", "=", "teacher_info.user_id")
                ->select('teacher_info.*')
                ->paginate(10);
        $grade_list    = $this->categoryRepository->getGrade();
        return view('admin.createinformation.teacherinfo.index',['grade_list'=>$grade_list,'list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grade_list    = $this->categoryRepository->getGrade();
        $gender        = $this->userRepository->getGender();
        return view('admin.createinformation.teacherinfo.create',['grade_list'=>$grade_list,'gender'=>$gender]);
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
            'name'            =>'required|min:3',
            'email'           =>'required|min:3',
            'teacher_profile' =>'required | mimes:jpeg,jpg,png | max:1000',
        ]); 
        if ($request->grade_id == '99') {
            return redirect()->back()->with('danger','Please select grade !');
        }  
        $chkEmail = $this->userRepository->checkEmail($request->email); 
        if ($chkEmail == false) {
            return redirect()->back()->with('danger','Email already exist !');
        }

        $userID = $this->userRepository->generateUserID();

        if($request->hasFile('teacher_profile')){
            $image=$request->file('teacher_profile');
            $extension = $image->extension();
            $image_name = $userID. "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 

        if($request->hasFile('qualification_desc')){
            $qualification_desc=$request->file('qualification_desc');
            $extension = $qualification_desc->extension();
            $qualification_desc_name = $userID. "_" . time() . "." . $extension;
        }else{
            $qualification_desc_name="";
        } 
        DB::beginTransaction();
        try{
            $userData = array(
                'user_id'     =>$userID,
                'name'        =>$request->name,
                'email'       =>$request->email,
                'password'    =>bcrypt($request->password),
                'role'        =>3,
                'created_by'  =>$login_id,
                'updated_by'  =>$login_id,
                'created_at'  =>$nowDate,
                'updated_at'  =>$nowDate
            );
            
            $userRes=User::insert($userData);
            if ($userRes) {
                $insertData = array(
                    'user_id'           =>$userID,
                    'grade_id'          =>$request->grade_id,
                    'name'              =>$request->name,
                    'name_mm'           =>$request->name_mm,
                    'login_name'        =>$request->login_name,
                    'startworking_date' =>$request->startworking_date,
                    'gender'            =>$request->gender,
                    'email'             =>$request->email,
                    'contact_number'    =>$request->contact_no,
                    'address'           =>$request->address,
                    'remark'            =>$request->remark,
                    'resign_status'     =>$request->status,
                    'profile_image'     =>$image_name,
                    'position'          =>$request->position,
                    'father_name'       =>$request->father_name,
                    'mother_name'       =>$request->mother_name,
                    'qualification_name'=>$request->qualification_name,
                    'year_attended'     =>$request->year_attended,
                    'qualification_desc'=>$qualification_desc_name,
                    'job_title'         =>$request->job_title,
                    'employer'          =>$request->employer,
                    'start_date'        =>$request->start_date,
                    'end_date'          =>$request->end_date,
                    'university'        =>$request->university,
                    'education_year'    =>$request->education_year,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'  =>$nowDate,
                    'updated_at'  =>$nowDate
                );
                if ($request->status == 0) {
                    $insertData['resign_date'] = $nowDate;
                }
        
                $result=TeacherInfo::insert($insertData);
                        
                if($result){
                    if ($image_name != '') {
                        $image->move(public_path('assets/teacher_images'),$image_name); 
                    }
                    if ($qualification_desc_name != '') {
                        $qualification_desc->move(public_path('assets/teacher_qualifications'),$qualification_desc_name); 
                    }
                    DB::commit();
                    return redirect(route('teacher_info.index'))->with('success','Teacher Information Created Successfully!');
                }else{
                    return redirect()->back()->with('danger','Teacher Information Created Fail !');
                }
            }else{
                return redirect()->back()->with('danger','Teacher Information Created Fail !');
            }  
        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Teacher Information Created Fail !');
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
        $res = TeacherInfo::leftjoin('users', 'users.user_id', '=', 'teacher_info.user_id')
                ->where('teacher_info.user_id',$id)
                ->select('teacher_info.*')
                ->get();

        $grade_list    = $this->categoryRepository->getGrade();
        $gender        = $this->userRepository->getGender();
        return view('admin.createinformation.teacherinfo.update',['grade_list'=>$grade_list,'result'=>$res,'gender'=>$gender]);
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
            'name'            =>'required|min:3',
            'email'           =>'required|min:3',
            'teacher_profile' =>'mimes:jpeg,jpg,png | max:1000',
        ]); 
        if ($request->grade_id == '99') {
            return redirect()->back()->with('danger','Please select grade !');
        }  
        $chkEmail = $this->userRepository->checkEmail($request->email,$id); 
        if ($chkEmail == false) {
            return redirect()->back()->with('danger','Email already exist !');
        }

        if($request->hasFile('teacher_profile')){

            $previous_img=$request->previous_image;
            @unlink(public_path('/assets/teacher_images/'. $previous_img));

            $image=$request->file('teacher_profile');
            $extension = $image->extension();
            $image_name = $id. "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 

        if($request->hasFile('qualification_desc')){

            $previous_qualification_desc=$request->previous_qualification_desc;
            @unlink(public_path('/assets/teacher_qualifications/'. $previous_qualification_desc));

            $qualification_desc=$request->file('teacher_qualifications');
            $extension = $qualification_desc->extension();
            $qualification_desc_name = $id. "_" . time() . "." . $extension;
        }else{
            $qualification_desc_name="";
        } 
       
        DB::beginTransaction();
        try{
            $userData = array(
                'user_id'     =>$id,
                'name'        =>$request->name,
                'email'       =>$request->email,
                'role'        =>3,
                'updated_by'  =>$login_id,
                'updated_at'  =>$nowDate
            );
            if ($request->password == '') {
                $userData ['password'] = bcrypt($request->password);
            }
            
            $userRes=User::where('user_id',$id)->update($userData);
            if ($userRes) {
                $infoData = array(
                    'user_id'           =>$id,
                    'grade_id'          =>$request->grade_id,
                    'name'              =>$request->name,
                    'name_mm'           =>$request->name_mm,
                    'login_name'        =>$request->login_name,
                    'startworking_date' =>$request->startworking_date,
                    'gender'            =>$request->gender,
                    'email'             =>$request->email,
                    'contact_number'    =>$request->contact_no,
                    'address'           =>$request->address,
                    'remark'            =>$request->remark,
                    'resign_status'     =>$request->status,
                    'position'          =>$request->position,
                    'father_name'       =>$request->father_name,
                    'mother_name'       =>$request->mother_name,
                    'qualification_name'=>$request->qualification_name,
                    'year_attended'     =>$request->year_attended,
                    'job_title'         =>$request->job_title,
                    'employer'          =>$request->employer,
                    'start_date'        =>$request->start_date,
                    'end_date'          =>$request->end_date,
                    'university'        =>$request->university,
                    'education_year'    =>$request->education_year,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'updated_at'        =>$nowDate
                );
                if ($request->status == 0) {
                    $infoData['resign_date'] = $nowDate;
                }
                if ($image_name != "") {
                    $infoData['profile_image'] = $image_name;
                }
                if ($qualification_desc_name != "") {
                    $infoData['qualification_desc'] = $qualification_desc_name;
                }
        
                $result=TeacherInfo::where('user_id',$id)->update($infoData);
                        
                if($result){
                    if ($image_name != "") {
                        $image->move(public_path('assets/teacher_images'),$image_name);  
                    }
                    if ($qualification_desc_name != "") {
                        $qualification_desc->move(public_path('assets/teacher_qualifications'),$qualification_desc_name);  
                    }   
                    DB::commit();                     
                    return redirect(route('teacher_info.index'))->with('success','Teacher Information Updated Successfully!');
                }else{
                    return redirect()->back()->with('danger','Teacher Information Updated Fail !');
                }
            }else{
                return redirect()->back()->with('danger','Teacher Information Created Fail !');
            }    
        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Teacher Information Updared Fail !');
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
        DB::beginTransaction();
        try{
            $checkData = TeacherInfo::where('user_id',$id)->first();

            if (!empty($checkData)) {
                
                $res = TeacherInfo::where('user_id',$id)->delete();
                if($res){
                    //To delet user
                    $userdel = User::where('user_id',$id)->delete();

                    //To delete image
                    $image=$checkData['profile_image'];
                    @unlink(public_path('/assets/teacher_images/'. $image));

                     //To delete qualification file
                     $file=$checkData['qualification_desc'];
                     @unlink(public_path('/assets/teacher_qualifications/'. $file));
                }
            }else{
                return redirect()->back()->with('error','There is no result with this teacher information.');
            }
            DB::commit();
            //To return list
            return redirect(route('teacher_info.index'))->with('success','Teacher Information Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Teacher Information Deleted Failed!');
        }
        
    }
}
