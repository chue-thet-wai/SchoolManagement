<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CreateInfoRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class ActivityController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepository;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository) 
    {
        $this->createInfoRepository = $createInfoRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = Activity::paginate(10);
        
        $class_list = $this->createInfoRepository->getClassSetup();
        $classes=[];
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        return view('admin.createinformation.activity.index',[
            'classes'      =>$classes,
            'list_result'  => $res]);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ActivityList(Request $request)
    {  
        $res = Activity::select('activities.*');
        if ($request['action'] == 'search') {
            if (request()->has('activity_classid') && request()->input('activity_classid') != '') {
                $res->where('class_id', request()->input('activity_classid'));
            }
            if (request()->has('activity_date') && request()->input('activity_date') != '') {
                $res->where('date', request()->input('activity_date'));
            }
        }else {
            request()->merge([
                'activity_classid' => null,
                'activity_date' => null,
            ]);
        }       
    
        $res = $res->paginate(20);
             
        $class_list = $this->createInfoRepository->getClassSetup();
        $classes=[];
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }        

        return view('admin.createinformation.activity.index',[
            'classes'      =>$classes,
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

        return view('admin.createinformation.activity.create',[
            'classes'      =>$class_list,
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
            'activity_date'       =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        } 
       
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'class_id'           =>$request->class_id,
                'date'               =>$request->activity_date,
                'description'        =>$request->description,
                'remark'             =>$request->remark,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=Activity::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(route('activity.index'))->with('success','Acivity Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Activity Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Activity Created Fail !');
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

        $res = Activity::where('id',$id)->get();
        return view('admin.createinformation.activity.update',[
            'classes'      =>$class_list,
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
            'activity_date'       =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        } 
        
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }

        DB::beginTransaction();
        try{
            $activityData = array(
                'class_id'           =>$request->class_id,
                'date'               =>$request->activity_date,
                'description'        =>$request->description,
                'remark'             =>$request->remark,
                'updated_by'         =>$login_id,
                'updated_at'         =>$nowDate

            );
            
            $result=Activity::where('id',$id)->update($activityData);
                      
            if($result){
                DB::commit();               
                return redirect(route('activity.index'))->with('success','Activity Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Activity Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Activity Updared Fail !');
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
            $checkData = Activity::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = Activity::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(route('activity.index'))->with('success','Activity Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('error','There is no result with this activity.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Activity Deleted Failed!');
        }
    }
}
