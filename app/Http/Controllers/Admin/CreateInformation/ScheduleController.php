<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\CreateInfoRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Schedules;

class ScheduleController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepository;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository,CategoryRepositoryInterface $categoryRepository) 
    {
        $this->createInfoRepository = $createInfoRepository;
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = Schedules::paginate(10);
        
        $class_list = $this->createInfoRepository->getClassSetup();
        $classes=[];
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }
        $teacher_list    = $this->createInfoRepository->getTeacherList();
        $teachers=[];
        foreach($teacher_list as $a) {
            $teachers[$a->id] = $a->name;
        }
        $subject_list    = $this->categoryRepository->getSubject();
        $subjects=[];
        foreach($subject_list as $a) {
            $subjects[$a->id] = $a->name;
        }
        $weekdays    = $this->createInfoRepository->getWeekDays();

        return view('admin.createinformation.schedule.index',[
            'classes'      =>$classes,
            'teacher_list' =>$teachers,
            'subjects'     =>$subjects,
            'weekdays'     =>$weekdays,
            'list_result'  => $res]);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ScheduleList(Request $request)
    {  
        $res = Schedules::select('schedules.*');
        if ($request['action'] == 'search') {
            if (request()->has('schedule_classid') && request()->input('schedule_classid') != '') {
                $res->where('class_id', request()->input('schedule_classid'));
            }
            if (request()->has('schedule_teacher') && request()->input('schedule_teacher') != '') {
                $res->where('teacher_id', request()->input('schedule_teacher'));
            }
            if (request()->has('schedule_weekday') && request()->input('schedule_weekday') != '') {
                $res->where('weekdays', request()->input('schedule_weekday'));
            }
        }else {
            request()->merge([
                'schedule_classid' => null,
                'schedule_teacher' => null,
                'schedule_weekday' => null,
            ]);
        }       
    
        $res = $res->paginate(20);
             
        $class_list = $this->createInfoRepository->getClassSetup();
        $classes=[];
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }
        $teacher_list    = $this->createInfoRepository->getTeacherList();
        $teachers=[];
        foreach($teacher_list as $a) {
            $teachers[$a->id] = $a->name;
        }
        $subject_list    = $this->categoryRepository->getSubject();
        $subjects=[];
        foreach($subject_list as $a) {
            $subjects[$a->id] = $a->name;
        }
        $weekday_list    = $this->createInfoRepository->getWeekDays();

        return view('admin.createinformation.schedule.index',[
            'classes'      =>$classes,
            'teacher_list' =>$teachers,
            'subjects'     =>$subjects,
            'weekdays'     =>$weekday_list,
            'list_result'  => $res]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class_list = $this->createInfoRepository->getClassSetup();       
        $teacher_list    = $this->createInfoRepository->getTeacherList();        
        $subject_list    = $this->categoryRepository->getSubject();        
        $weekday_list    = $this->createInfoRepository->getWeekDays();
        

        return view('admin.createinformation.schedule.create',[
            'classes'      =>$class_list,
            'teacher_list' =>$teacher_list,
            'subjects'     =>$subject_list,
            'weekdays'     =>$weekday_list,
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
            'start_time'            =>'required',
            'end_time'              =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
         } 
        if ($request->teacher_id == '99') {
            array_push($errmsg,'Teacher');
        }  
        if ($request->subject_id == '99') {
            array_push($errmsg,'Subject');
        }
        
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'class_id'           =>$request->class_id,
                'teacher_id'         =>$request->teacher_id,
                'subject_id'         =>$request->subject_id,
                'weekdays'           =>$request->weekdays,
                'start_time'         =>$request->start_time,
                'end_time'           =>$request->end_time,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=Schedules::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(route('schedule.index'))->with('success','Schedule Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Schedule Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Schedule Created Fail !');
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
        $class_list = $this->createInfoRepository->getClassSetup();       
        $teacher_list    = $this->createInfoRepository->getTeacherList();        
        $subject_list    = $this->categoryRepository->getSubject();        
        $weekday_list    = $this->createInfoRepository->getWeekDays();

        $res = Schedules::where('id',$id)->get();
        return view('admin.createinformation.schedule.update',[
            'classes'      =>$class_list,
            'teacher_list' =>$teacher_list,
            'subjects'     =>$subject_list,
            'weekdays'     =>$weekday_list,
            'result'=>$res]);
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
            'start_time'            =>'required',
            'end_time'              =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
         } 
        if ($request->teacher_id == '99') {
            array_push($errmsg,'Teacher');
        }  
        if ($request->subject_id == '99') {
            array_push($errmsg,'Subject');
        }
        
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }

        DB::beginTransaction();
        try{
            $scheduleData = array(
                'class_id'           =>$request->class_id,
                'teacher_id'         =>$request->teacher_id,
                'subject_id'         =>$request->subject_id,
                'weekdays'           =>$request->weekdays,
                'start_time'         =>$request->start_time,
                'end_time'           =>$request->end_time,
                'updated_by'         =>$login_id,
                'updated_at'         =>$nowDate

            );
            
            $result=Schedules::where('id',$id)->update($scheduleData);
                      
            if($result){
                DB::commit();               
                return redirect(route('schedule.index'))->with('success','Schedule Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Schedule Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Schedule Updared Fail !');
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
            $checkData = Schedules::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = Schedules::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(route('schedule.index'))->with('success','Schedule Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('error','There is no result with this schedule.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Schedule Deleted Failed!');
        }
    }
}
