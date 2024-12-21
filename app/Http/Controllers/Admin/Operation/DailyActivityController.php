<?php

namespace App\Http\Controllers\Admin\Operation;

use App\Http\Controllers\Controller;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Models\AcademicYear;
use App\Models\StudentDailyActivity;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyActivityController extends Controller
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
    public function dailyActivityList(Request $request)
    {
        $res = StudentDailyActivity::select('student_daily_activity.*');
        if ($request['action'] == 'search') {
            if (request()->has('dailyactivity_studentid') && request()->input('dailyactivity_studentid') != '') {
                $res->where('student_id',request()->input('dailyactivity_studentid'));
            }
            if (request()->has('dailyactivity_classid') && request()->input('dailyactivity_classid') != '') {
                $res->where('class_id', request()->input('dailyactivity_classid'));
            }
        }else {
            request()->merge([
                'dailyactivity_studentid'   => null,
                'dailyactivity_classid'     => null
            ]);
        }  
        $res=$res->paginate(20);

        $daily_activities = $this->regRepository->getDailyActivity();
        $class_list       = $this->createInfoRepository->getClassSetup();
        $classes = [];
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        return view('admin.operation.dailyactivity.index',[
            'list_result'      => $res,
            'daily_activities' => $daily_activities,
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
        $daily_activities = $this->regRepository->getDailyActivity();
        $class_list = $this->createInfoRepository->getClassSetup();

        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        $rate_list = $this->rateList();
       
        return view('admin.operation.dailyactivity.create',[
            'activity_list'     =>$daily_activities,
            'class_list'        =>$classes,
            'rate_list'         =>$rate_list
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
            'student_id'     =>'required'
        ]); 
        $regData = StudentRegistration::where('student_id',$request->student_id)
                        ->latest('student_registration.created_at')
                        ->first();
        $registration_id = '';
        if ($regData) {
            $registration_id = $regData->registration_no;
        }

        $daily_activities = $this->regRepository->getDailyActivity();
       
        DB::beginTransaction();
        try{
            $insertData = array(
                'class_id'          =>$request->class_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'activity_id'       =>$request->activity_id,
                'activity_name'     =>$daily_activities[$request->activity_id],
                'date'              =>$request->activity_date,
                'rate'              =>$request->rate,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=StudentDailyActivity::insert($insertData);
                        
            if($result){            
                DB::commit();
                return redirect(url('admin/daily_activity/list'))->with('success','Student Daily Activity Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Student Daily Activity Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Student Daily Activity Created Fail !');
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
        $res = StudentDailyActivity::where('id',$id)->get();
        $class_id = $res[0]->class_id;
        
        $daily_activities = $this->regRepository->getDailyActivity();
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

        $rate_list = $this->rateList();

        return view('admin.operation.dailyactivity.update',[
            'result'           => $res,
            'activity_list'    => $daily_activities,
            'class_list'       => $classes,
            'student_list'     => $studentList,
            'rate_list'        => $rate_list
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

        $daily_activities = $this->regRepository->getDailyActivity();
       
        DB::beginTransaction();
        try{
            $updateData = array(
                'class_id'          =>$request->class_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'activity_id'       =>$request->activity_id,
                'activity_name'     =>$daily_activities[$request->activity_id],
                'date'              =>$request->activity_date,
                'rate'              =>$request->rate,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            $result=StudentDailyActivity::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/daily_activity/list'))->with('success','Student Daily Activity Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Student Daily Activity Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Student Daily Activity Updared Fail !');
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
            $checkData = StudentDailyActivity::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = StudentDailyActivity::where('id',$id)->delete();
            }else{
                return redirect()->back()->with('danger','There is no result with this student daily activity.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/daily_activity/list'))->with('success','Student daily activity Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Student daily activity Deleted Failed!');
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

    public function rateList(){
        return [
            "1" => "1",
            "2" => "2",
            "3" => "3"
        ];
    }
    
}
