<?php

namespace App\Http\Controllers\Admin\Operation;

use App\Http\Controllers\Controller;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Models\AcademicYear;
use App\Models\Homework;
use App\Models\HomeworkStatus;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeworkStatusController extends Controller
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
    public function homeworkStatusList(Request $request,$getHomeworkId = null)
    {
        if ($getHomeworkId) {
            $homework_id = $getHomeworkId;
        } else {
            $homework_id = $request->homework_id;
        }
        $res = HomeworkStatus::select('homework_status.*')
                    ->leftjoin('homework','homework.id','homework_status.homework_id')
                    ->leftjoin('student_info','student_info.student_id','homework_status.student_id');
    
        $res=$res->where('homework_status.homework_id',$homework_id);
        $res=$res->select('homework_status.*','homework.title as homework_title','student_info.name as student_name');
        $res=$res->paginate(20);

        $status = $this->regRepository->getHomeworkStatus();

        $homework_data = Homework::leftjoin('subject','subject.id','homework.subject_id')
                            ->where('homework.id',$homework_id)
                            ->select('homework.*','subject.name as subject_name')
                            ->first();

        return view('admin.operation.homeworkstatus.index',[
            'list_result'     => $res,
            'homework_data'   => $homework_data,
            'homework_status' => $status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($homework_id)
    {
        $status = $this->regRepository->getHomeworkStatus();
        
        $homeworkData = Homework::leftjoin('class_setup','homework.class_id','class_setup.id')
                        ->where('homework.id',$homework_id)
                        ->select('homework.*', DB::raw('COALESCE(class_setup.name, "All") as class_name'))
                        ->first();

        $studentList = DB::table('student_registration')
                            ->join('student_info','student_info.student_id','student_registration.student_id')
                            ->select('student_info.*');
        if ($homeworkData->class_id != '') {
            $studentList = $studentList->where('new_class_id','=',$homeworkData->class_id);
        } else {
            $studentList = $studentList->leftjoin('class_setup','student_registration.new_class_id','class_setup.id')
                                ->where('student_registration.new_class_id',$this->academicId);
        }                          
        $studentList = $studentList->get();
       
        return view('admin.operation.homeworkstatus.create',[
            'status_list'     =>$status,
            'homework_data'   =>$homeworkData,
            'student_list'    =>$studentList
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
            'homework_id'    =>'required',
            'student_id'     =>'required'
        ]);

        $regData = StudentRegistration::where('student_id',$request->student_id)
                        ->latest('student_registration.created_at')
                        ->first();
        $registration_id = '';
        if ($regData) {
            $registration_id = $regData->registration_no;
        }
        $checkHomeworkStatus = HomeworkStatus::where('homework_id',$request->homework_id)
                                ->where('student_id',$request->student_id)
                                ->where('registration_id',$registration_id)
                                ->first();
        if (!empty($checkHomeworkStatus)) {
            return redirect()->back()->with('danger','Homework Status already exist');
        }
       
        DB::beginTransaction();
        try{
            $insertData = array(
                'homework_id'       =>$request->homework_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'status'            =>$request->status,
                'remark'            =>$request->remark,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=HomeworkStatus::insert($insertData);
                        
            if($result){ 
                DB::commit();
                return redirect(url('admin/homework_status/list/'.$request->homework_id))->with('success','Homework Status Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Homework Status Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Homework Status Created Fail !');
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
        $res = HomeworkStatus::where('homework_status.id',$id)
                ->leftjoin('homework','homework.id','homework_status.homework_id')
                ->select('homework_status.*')
                ->get();

        $status = $this->regRepository->getHomeworkStatus();
        $homeworkData = Homework::join('class_setup','homework.class_id','class_setup.id')
                        ->where('homework.id',$res[0]->homework_id)
                        ->select('homework.*','class_setup.name as class_name')
                        ->first();
        $studentList = DB::table('student_registration')
                            ->join('student_info','student_info.student_id','student_registration.student_id')
                            ->select('student_info.*')
                            ->where('new_class_id','=',$homeworkData->class_id)
                            ->get();
        
        return view('admin.operation.homeworkstatus.update',[
            'result'          =>$res,
            'status_list'     =>$status,
            'homework_data'   =>$homeworkData,
            'student_list'    =>$studentList
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
            'homework_id'    =>'required',
            'student_id'     =>'required'
        ]); 
        $regData = StudentRegistration::where('student_id',$request->student_id)
                        ->latest('student_registration.created_at')
                        ->first();
        $registration_id = '';
        if ($regData) {
            $registration_id = $regData->registration_no;
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'homework_id'       =>$request->homework_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'status'            =>$request->status,
                'remark'            =>$request->remark,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            $result=HomeworkStatus::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/homework_status/list/'.$request->homework_id))->with('success','Homework Status Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Homework Status Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Homework Status Updared Fail !');
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
            $checkData = HomeworkStatus::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = HomeworkStatus::where('id',$id)->delete();
            }else{
                return redirect()->back()->with('danger','There is no result with this homework status.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/homework_status/list/'.$checkData->homework_id))->with('success','Homework Status Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Homework status Deleted Failed!');
        }
    }

    public function homeworkSearch(Request $request) {
        $homeworkList = Homework::where('class_id',$request->class_id)->get();
    
        if (!empty($homeworkList)) {
            $studentList = DB::table('student_registration')
                            ->join('student_info','student_info.student_id','student_registration.student_id')
                            ->select('student_info.*')
                            ->where('new_class_id','=',$request->class_id)
                            ->get();
           
            return response()->json(array(
                'msg'             => 'found',
                'homework_data'   => $homeworkList,
                'student_data'    => $studentList
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }
    
}
