<?php

namespace App\Http\Controllers\Admin\Operation;

use App\Http\Controllers\Controller;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Models\AcademicYear;
use App\Models\StudentRegistration;
use App\Models\StudentRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentRequestController extends Controller
{
    private RegistrationRepositoryInterface $regRepository;
    private CreateInfoRepositoryInterface $createInfoRepository;
    private $academicId;

    public function __construct(RegistrationRepositoryInterface $regRepository,CreateInfoRepositoryInterface $createInfoRepository) 
    {
        $this->regRepository        = $regRepository;
        $this->createInfoRepository = $createInfoRepository;
        $currentDate = date("Y-m-d");

         //current academic year
         $currentAcademic = AcademicYear::where('start_date', '<=', $currentDate)
                    ->where('end_date', '>=', $currentDate)
                    ->first();
        $this->academicId = $currentAcademic ? $currentAcademic->id : null;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function studentRequestList(Request $request)
    {
        $res = StudentRequest::leftJoin('student_request_comment', 'student_request.id', '=', 'student_request_comment.student_request_id')
            ->selectRaw('student_request.*, IF(MAX(student_request_comment.created_at) IS NOT NULL AND (student_request.last_read_at IS NULL OR (MAX(student_request_comment.created_at) > student_request.last_read_at)), 1, 0) AS has_new_comment')
            ->groupBy('student_request.id')
            ->orderByRaw('MAX(student_request_comment.created_at) DESC');

        if ($request['action'] == 'search') {
            if (request()->has('studentrequest_studentid') && request()->input('studentrequest_studentid') != '') {
                $res->where('student_request.student_id', request()->input('studentrequest_studentid'));
            }
        } else {
            request()->merge([
                'studentrequest_studentid' => null
            ]);
        }

        $res = $res->paginate(20);

        $request_types    = $this->regRepository->getStudentRequestTypes();
        $class_list       = $this->createInfoRepository->getClassSetup();
        $classes = [];
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        return view('admin.operation.studentrequest.index',[
            'list_result'      => $res,
            'request_types'    => $request_types,
            'class_list'       => $classes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request_types = $this->regRepository->getStudentRequestTypes();
        $class_list = $this->createInfoRepository->getClassSetup();

        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        $request_types    = $this->regRepository->getStudentRequestTypes();
       
        return view('admin.operation.studentrequest.create',[
            'request_types'     =>$request_types,
            'class_list'        =>$classes,
            'request_types'     =>$request_types
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
            'class_id'       =>'required',
            'student_id'     =>'required',
            'photo'          =>'required|mimes:jpeg,jpg,png|max:1000',
        ]); 
        $regData = StudentRegistration::where('student_id',$request->student_id)
                        ->latest('student_registration.created_at')
                        ->first();
        $registration_id = '';
        if ($regData) {
            $registration_id = $regData->registration_no;
        }

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $extension = $image->extension();
            $image_name = time() . "." . $extension;
        } else {
            $image_name = "";
        }
       
        DB::beginTransaction();
        try{
            $insertData = array(
                'request_type'      =>$request->request_type,
                'class_id'          =>$request->class_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'request_by_school' =>$login_id,
                'message'           =>$request->message,
                'photo'             =>$image_name,
                'date'              =>$request->request_date,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=StudentRequest::insert($insertData);
                        
            if($result){  
                if ($image_name != '') {
                    $image->move(public_path('assets/studentrequest_images'), $image_name);
                }          
                DB::commit();
                return redirect(url('admin/student_request/list'))->with('success','Student Request Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Student Request Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Student Request Created Fail !');
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
        $res = StudentRequest::where('id',$id)->get();
        $class_id = $res[0]->class_id;
        
        $request_types = $this->regRepository->getStudentRequestTypes();
        $class_list = $this->createInfoRepository->getClassSetup();

        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        //student list
        $studentRes = DB::table('student_registration')
                            ->join('student_info','student_info.student_id','student_registration.student_id')
                            ->select('student_info.*')
                            ->where('new_class_id','=',$class_id)
                            ->get();
        $studentList = [];
        foreach ($studentRes as $student) {
            $studentList[$student->student_id] = $student->name;
        }

        return view('admin.operation.studentrequest.update',[
            'result'           => $res,
            'request_types'    => $request_types,
            'class_list'       => $classes,
            'student_list'     => $studentList
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
            'class_id'       =>'required',
            'student_id'     =>'required'
        ]); 
        $regData = StudentRegistration::where('student_id',$request->student_id)
                        ->latest('student_registration.created_at')
                        ->first();
        $registration_id = '';
        if ($regData) {
            $registration_id = $regData->registration_no;
        }

        if ($request->hasFile('photo')) {

            $previous_img = $request->previous_image;
            @unlink(public_path('/assets/studentrequest_images/' . $previous_img));

            $image = $request->file('photo');
            $extension = $image->extension();
            $image_name = time() . "." . $extension;
        } else {
            $image_name = "";
        }
       
        DB::beginTransaction();
        try{
            $updateData = array(
                'request_type'      =>$request->request_type,
                'class_id'          =>$request->class_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'request_by_school' =>$login_id,
                'message'           =>$request->message,
                'date'              =>$request->request_date,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            if ($image_name != "") {
                $updateData['photo'] = $image_name;
            }
            
            $result=StudentRequest::where('id',$id)->update($updateData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/studentrequest_images'), $image_name);
                }
                DB::commit();               
                return redirect(url('admin/student_request/list'))->with('success','Student Request Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Student Request Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Student Request Updared Fail !');
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
            $checkData = StudentRequest::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = StudentRequest::where('id',$id)->delete();

                //To delete image
                $image = $checkData['photo'];
                @unlink(public_path('/assets/studentrequest_images/' . $image));

            }else{
                return redirect()->back()->with('danger','There is no result with this student request.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/student_request/list'))->with('success','Student request Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Student Request Deleted Failed!');
        }
    }

    public function studentSearch(Request $request) {
        $studentList = DB::table('student_registration')
                            ->join('student_info','student_info.student_id','student_registration.student_id')
                            ->select('student_info.*')
                            ->where('new_class_id','=',$request->class_id)
                            ->get();
        if ($studentList) {
            return response()->json(array(
                'msg'             => 'found',
                'student_data'    => $studentList
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    public function saveLastReadTime(Request $request){
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try {
            $res = StudentRequest::where('id', $request->student_request_id)
                    ->update(['last_read_at' => $nowDate, 'updated_at' => $nowDate]);
                
            if ($res) {
                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'Last read at time saved'
                ]);   
            } else {
                DB::rollback();
                return response()->json([
                    'status'  => '500',
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);  
            }
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
    
}
