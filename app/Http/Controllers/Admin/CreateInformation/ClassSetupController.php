<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Models\ClassSetup;
use App\Models\Room;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassSetupController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private $academicList;
    private $branchList;
    private $sectionList;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository,CategoryRepositoryInterface $categoryRepository) 
    {
        $this->createInfoRepository = $createInfoRepository;
        $this->categoryRepository = $categoryRepository;

        $branch_list_data   = $this->categoryRepository->getBranch();
        $branch_list=[];
        foreach($branch_list_data as $b) {
            $branch_list[$b->id] = $b->name;
        } 
        $this->branchList = $branch_list;

        $academic_list = $this->categoryRepository->getAcademicYear();
        $academic=[];
        foreach($academic_list as $a) {
            $academic[$a->id] = $a->name;
        }
        $this->academicList = $academic;

        $section_list    = $this->categoryRepository->getSection();
        $section=[];
        foreach($section_list as $a) {
            $section[$a->id] = $a->name;
        }
        $this->sectionList = $section;
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function classSetupList(Request $request)
    {  
        $res = ClassSetup::select('class_setup.*');
        if ($request['action'] == 'search') {
            if (request()->has('classsetup_name') && request()->input('classsetup_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('classsetup_name') . '%');
            }
            if (request()->has('classsetup_room') && request()->input('classsetup_room') != '') {
                $res->where('room_id', request()->input('classsetup_room'));
            }
            if (request()->has('classsetup_academic') && request()->input('classsetup_academic') != '') {
                $res->where('academic_year_id', request()->input('classsetup_academic'));
            }
            if (request()->has('classsetup_branch') && request()->input('classsetup_branch') != '') {
                $res->where('banch_id', request()->input('classsetup_branch'));
            }
        }else {
            request()->merge([
                'classsetup_name' => null,
                'classsetup_room' => null,
                'classsetup_academic' => null,
                'classsetup_branch' => null,
            ]);
        }       
    
        $res = $res->paginate(20);
        
        $branch_list_data   = $this->categoryRepository->getBranch();
        $branch_list=[];
        foreach($branch_list_data as $b) {
            $branch_list[$b->id] = $b->name;
        } 

        $academic_list = $this->categoryRepository->getAcademicYear();
        $academic=[];
        foreach($academic_list as $a) {
            $academic[$a->id] = $a->name;
        }
        $grade_list    = $this->categoryRepository->getGrade();
        $grade=[];
        foreach($grade_list as $a) {
            $grade[$a->id] = $a->name;
        }
        $section_list    = $this->categoryRepository->getSection();
        $section=[];
        foreach($section_list as $a) {
            $section[$a->id] = $a->name;
        }
        $room_list    = $this->categoryRepository->getRoom();
        $room=[];
        foreach($room_list as $a) {
            $room[$a->id] = $a->name;
        }

        return view('admin.createinformation.classsetup.index',[
            'branch_list'  => $branch_list,
            'room_list'    => $room,
            'academic_list'=> $academic,
            'grade_list'   => $grade,
            'section_list' => $section,
            'list_result'  => $res]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_list = $this->categoryRepository->getAcademicYear();
        $grade_list    = $this->categoryRepository->getGrade();
        $section_list  = $this->categoryRepository->getSection();
        $room_list     = $this->categoryRepository->getRoom();
        $branch_list   = $this->categoryRepository->getBranch();

        return view('admin.createinformation.classsetup.create',[
            'branch_list'  =>$branch_list,
            'room_list'    =>$room_list,
            'academic_list'=>$academic_list,
            'grade_list'   =>$grade_list,
            'section_list' =>$section_list,
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
            'branch_id'            =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->branch_id == '99') {
            array_push($errmsg,'Branch');
        } 
        if ($request->room_id == '99') {
            array_push($errmsg,'Room');
        } 
        if ($request->grade_id == '99') {
            array_push($errmsg,'Grade');
        }  
        if ($request->section_id == '99') {
            array_push($errmsg,'Section');
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
            $name = $this->academicList[$request->academic_year_id].'('.$this->branchList[$request->branch_id]
                    .')'.':'.$this->sectionList[$request->section_id];
            $insertData = array(
                'name'              =>$name,
                'branch_id'         =>$request->branch_id,
                'room_id'           =>$request->room_id,
                'grade_id'          =>$request->grade_id,
                'section_id'        =>$request->section_id,
                'academic_year_id'  =>$request->academic_year_id,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=ClassSetup::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/class_setup/list'))->with('success','Class Setup Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Class Setup Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Class Setup Created Fail !');
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
        $academic_list = $this->categoryRepository->getAcademicYear();
        $grade_list    = $this->categoryRepository->getGrade();
        $section_list  = $this->categoryRepository->getSection();
        $room_list     = $this->categoryRepository->getRoom();
        $branch_list   = $this->categoryRepository->getBranch();

        $res = ClassSetup::where('id',$id)->get();
        return view('admin.createinformation.classsetup.update',[
            'branch_list'  => $branch_list,
            'room_list'    => $room_list,
            'academic_list'=> $academic_list,
            'grade_list'   => $grade_list,
            'section_list' => $section_list,
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
            'branch_id'            =>'required',
        ]); 
        $errmsg =array();
        if ($request->branch_id == '99') {
            array_push($errmsg,'Branch');
        } 
        if ($request->room_id == '99') {
            array_push($errmsg,'Room');
        } 
        if ($request->grade_id == '99') {
            array_push($errmsg,'Grade');
        }  
        if ($request->section_id == '99') {
            array_push($errmsg,'Section');
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
            $name = $this->academicList[$request->academic_year_id].'('.$this->branchList[$request->branch_id]
                    .')'.':'.$this->sectionList[$request->section_id];
            $classData = array(
                'name'              =>$name,
                'branch_id'         =>$request->branch_id,
                'room_id'           =>$request->room_id,
                'grade_id'          =>$request->grade_id,
                'section_id'        =>$request->section_id,
                'academic_year_id'  =>$request->academic_year_id,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate

            );
            
            $result=ClassSetup::where('id',$id)->update($classData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/class_setup/list'))->with('success','Class Setup Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Class Setup Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Class Setup Updared Fail !');
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
            $checkData = ClassSetup::where('id',$id)->first();

            if (!empty($checkData)) {
                try {
                    // Attempt to delete the record
                    $res = ClassSetup::where('id',$id)->forceDelete();
                   
                    if($res){
                        DB::commit();

                        return redirect(url('admin/class_setup/list'))->with('success','Class Deleted Successfully!');
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    // Check if the exception is due to a foreign key constraint violation
                    if ($e->errorInfo[1] === 1451) {
                        return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                    }
                    return redirect()->back()->with('danger','An error occurred while deleting the record.');
                }
                
            }else{
                return redirect()->back()->with('danger','There is no result with this class.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Class Deleted Failed!');
        }
    }

    //Get class with branch
    public function getRoomwithBranch(Request $request)
    {
        $room_res = Room::where('branch_id',$request->branch_id)->get();

        if (!empty($room_res)) {
            return response()->json(array(
                'msg'            => 'found',
                'room_data'     => $room_res,
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }
}
