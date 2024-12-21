<?php

namespace App\Http\Controllers\Admin\Registration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\StudentInfo;
use App\Models\WaitingRegistration;
use App\Models\StudentRegistration;
use App\Models\TeacherInfo;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\User;

class StudentRegistrationController extends Controller
{
    private RegistrationRepositoryInterface $regRepository;
    private UserRepositoryInterface $userRepository;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(RegistrationRepositoryInterface $regRepository,UserRepositoryInterface $userRepository,CategoryRepositoryInterface $categoryRepository) 
    {
        $this->regRepository     = $regRepository;
        $this->userRepository    = $userRepository;
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('admin.registration.studentreg.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'reg_type'        =>'required',
        ]); 
        $waiting_name = '';
        $waitingId = '';
        if (isset($request->waiting_id)) {
            $waitingInfo = WaitingRegistration::where('id',$request->waiting_id)->first();
            if (!empty($waitingInfo)) {
                $waiting_name = $waitingInfo->name;
            }
            $waitingId = $request->waiting_id;
        }

        $register_type = $request->reg_type;
        $gender   = $this->userRepository->getGender(); 
        $township = $this->userRepository->getTownship(); 
        $class = $this->regRepository->getClass(); 
 
        return view('admin.registration.studentreg.create',[
            'register_type'=>$register_type,
            'gender'       =>$gender,
            'township'     =>$township,
            'class'        =>$class,
            'waiting_name' =>$waiting_name,
            'waiting_id'   =>$waitingId
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

        if ($request->registration_type == 1) { //new student
            $request->validate([
                'registration_type' =>'required',
                'student_profile'   => 'mimes:jpeg,jpg,png',
                'name'              => 'required',
                'name_mm'           => 'required',
                'religion'          => 'required',
                'nationality'       => 'required',
                'date_of_birth'     => 'required',
                'father_name'       => 'required',
                'father_name_mm'    => 'required',
                'mother_name'       => 'required',
                'mother_name_mm'    => 'required',
                'father_phone'      => 'required',
                'mother_phone'      => 'required',
                'registration_date' => 'required',
                'new_class'         => 'required',
                //'card_id'           => 'required'
            ]); 
            $studentID = $this->regRepository->generateStudentID();

            if($request->hasFile('student_profile')){
                $image=$request->file('student_profile');
                $extension = $image->extension();
                $image_name = $studentID. "_" . time() . "." . $extension;
            }else{
                $image_name="";
            } 

            if($request->hasFile('biography')){
                $biography=$request->file('biography');
                $extension = $biography->extension();
                $biography_name = $studentID. "_" . time() . "." . $extension;
            }else{
                $biography = '';
                $biography_name="";
            }      
                    
            DB::beginTransaction();
            try{
                //student guardian save
                if (isset($request->guardian_id) && $request->guardian_id != '') {
                    $guardianId = $request->guardian_id;
                } else {
                    if ($request->guardian_name =='' || $request->guardian_phone=='' || $request->guardian_address=='') {
                        return redirect()->back()->with('danger','Please fill all guardian information !');
                    }
                    $guardianData = array(
                        'name'              =>$request->guardian_name,
                        'phone'             =>$request->guardian_phone,
                        'address'           =>$request->guardian_address,
                        'password'          =>bcrypt('12345'),
                        'created_by'        =>$login_id,
                        'updated_by'        =>$login_id,
                        'created_at'        =>$nowDate,
                        'updated_at'        =>$nowDate
                    );
                    $guardianId = DB::table('student_guardian')->insertGetId($guardianData);
                }                

                //student info save
                $studentInfoData = array(
                    'student_id'        =>$studentID,
                    'card_id'           =>$request->card_id,
                    'name'              =>$request->name,
                    'name_mm'           =>$request->name_mm,
                    'date_of_birth'     =>$request->date_of_birth,
                    'gender'            =>$request->gender,
                    'religion'          =>$request->religion,
                    'nationality'       =>$request->nationality,
                    'township'          =>$request->township,
                    'old_school_name'   =>$request->old_school_name,
                    'old_grade'         =>$request->old_grade,
                    'old_academic_year' =>$request->old_academic_year,
                    'father_name'       =>$request->father_name,
                    'father_name_mm'    =>$request->father_name_mm,
                    'mother_name'       =>$request->mother_name,
                    'mother_name_mm'    =>$request->mother_name_mm,
                    'father_phone'      =>$request->father_phone,
                    'mother_phone'      =>$request->mother_phone,
                    'address_1'         =>$request->address_1,
                    'address_2'         =>$request->address_2,
                    'guardian_id'        =>$guardianId,
                    'student_profile'   =>$image_name,
                    'student_biography' =>$biography_name,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $studentInfoRes=StudentInfo::insert($studentInfoData);
               
                $registration_no = $this->regRepository->generateRegistrationNo();
                //student registration save
                $studentRegData = array(
                    'student_id'        =>$studentID,
                    'registration_no'   =>$registration_no,
                    'new_class_id'      =>$request->new_class,
                    'registration_date' =>$request->registration_date,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );

                $studentRegRes=StudentRegistration::insert($studentRegData);
                
                            
                if($studentInfoRes && $studentRegRes){
                    if ($image_name != "") {
                        $image->move(public_path('assets/student_images'),$image_name);   
                    }
                    if ($biography != "") {
                        $biography->move(public_path('assets/student_biography'),$biography_name);   
                    }  
                    //change waiting status
                    if ($request->waiting_id !='')  {
                        // Retrieve the model instance
                        $waiting = WaitingRegistration::findOrFail($request->waiting_id);
                        // Update the status (replace 'status' with your actual status field)
                        $waiting->update(['status' => '2']);
                    }           
                    DB::commit();
                    return redirect(route('student_reg.index'))->with('success','Student Registration Created Successfully!');
                }else{
                    return redirect()->back()->with('danger','Student Registration Created Fail !');
                }

            }catch(\Exception $e){
                DB::rollback();
                Log::info($e->getMessage());
                return redirect()->back()->with('danger','Student Registration Created Fail !');
            }  
        } else {
            $request->validate([
                'registration_type' =>'required',
                'registration_date' => 'required',
                'new_class'         => 'required',
            ]); 
            DB::beginTransaction();
            try{
                $registration_no = $this->regRepository->generateRegistrationNo();
                //student registration save
                $studentRegData = array(
                    'student_id'        =>$request->student_id,
                    'registration_no'   =>$registration_no,
                    'old_class_id'      =>$request->old_class,
                    'new_class_id'      =>$request->new_class,
                    'registration_date' =>$request->registration_date,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
                $studentRegRes=StudentRegistration::insert($studentRegData);
                            
                if($studentRegRes){             
                    DB::commit();
                    return redirect(route('student_reg.index'))->with('success','Student Registration Created Successfully!');
                }else{
                    return redirect()->back()->with('danger','Student Registration Created Fail !');
                }

            }catch(\Exception $e){
                DB::rollback();
                Log::info($e->getMessage());
                return redirect()->back()->with('danger','Student Registration Created Fail !');
            }  
        }       
        
    }

}
