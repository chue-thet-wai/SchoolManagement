<?php

namespace App\Http\Controllers\Admin\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class EventController extends Controller
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
    public function EventList(Request $request)
    {  
        $res = Event::select('event.*');
        if ($request['action'] == 'search') {
            if (request()->has('event_title') && request()->input('event_title') != '' && request()->input('event_title') != '99') {
                $res->where('title', request()->input('event_title'));
            }
            if (request()->has('event_gradeid') && request()->input('event_gradeid') != '') {
                if (request()->input('event_gradeid') == '0') {
                    $res->whereNull('grade_id');
                } else {
                    $res->where('grade_id', request()->input('event_gradeid'));
                }
            }
        }else {
            request()->merge([
                'event_title' => '',
                'event_gradeid' => '99',
            ]);
        }       
    
        $res = $res->paginate(20);
             
        $grade_list = $this->categoryRepository->getGrade();
        $grade[0]="All";
        foreach($grade_list as $a) {
            $grade[$a->id] = $a->name;
        }  
        
        $academic_list = $this->categoryRepository->getAcademicYear();
        $academic=[];
        foreach($academic_list as $a) {
            $academic[$a->id] = $a->name;
        }
       

        return view('admin.operation.event.index',[
            'grade'        => $grade,
            'academic'     => $academic,
            'list_result'  => $res]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grade_list = $this->categoryRepository->getGrade();
        
        $academic_list = $this->categoryRepository->getAcademicYear();            

        return view('admin.operation.event.create',[
            'grade'        => $grade_list,
            'academic'     => $academic_list,
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
            'title'       =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->grade_id == '99') {
            array_push($errmsg,'Grade');
        } 
        if ($request->academic_year_id == '99') {
            array_push($errmsg,'Academic Year');
        } 
       
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'title'              =>$request->title,
                'grade_id'           =>$request->grade_id !== '0' ? $request->grade_id : null,
                'academic_year_id'   =>$request->academic_year_id,
                'description'        =>$request->description,
                'event_from_date'    =>$request->event_from_date,
                'event_to_date'      =>$request->event_to_date,
                'remark'             =>$request->remark,
                'created_by'         =>$login_id,
                'updated_by'         =>$login_id,
                'created_at'         =>$nowDate,
                'updated_at'         =>$nowDate
            );
            $result=Event::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/event/list'))->with('success','Event Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Event Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Event Created Fail !');
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
        $grade_list = $this->categoryRepository->getGrade();
        
        $academic_list = $this->categoryRepository->getAcademicYear();         

        $res = Event::where('id',$id)->get();
        return view('admin.operation.event.update',[
            'grade'        => $grade_list,
            'academic'     => $academic_list,
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
            'title'       =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->grade_id == '99') {
            array_push($errmsg,'Grade');
        }
        if ($request->academic_year == '99') {
            array_push($errmsg,'Academic Year');
        } 
       
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }

        DB::beginTransaction();
        try{
            $eventData = array(
                'title'              =>$request->title,
                'grade_id'           =>$request->grade_id !== '0' ? $request->grade_id : null,
                'academic_year_id'   =>$request->academic_year_id,
                'description'        =>$request->description,
                'event_from_date'    =>$request->event_from_date,
                'event_to_date'      =>$request->event_to_date,
                'remark'             =>$request->remark,
                'updated_by'         =>$login_id,
                'updated_at'         =>$nowDate

            );
            
            $result=Event::where('id',$id)->update($eventData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/event/list'))->with('success','Event Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Event Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Event Updared Fail !');
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
            $checkData = Event::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = Event::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/event/list'))->with('success','Event Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this event.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Event Deleted Failed!');
        }
    }
}
