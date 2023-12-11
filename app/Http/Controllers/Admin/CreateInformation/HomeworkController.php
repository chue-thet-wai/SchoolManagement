<?php

namespace App\Http\Controllers\Admin\CreateInformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Homework;

class HomeworkController extends Controller
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
    public function HomeworkList(Request $request)
    {  
        $res = Homework::select('homework.*');
        if ($request['action'] == 'search') {
            if (request()->has('homework_title') && request()->input('homework_title') != '') {
                $res->where('title', request()->input('homework_title'));
            }
            if (request()->has('homework_classid') && request()->input('homework_classid') != '' && request()->input('homework_classid') != '99') {
                $res->where('class_id', request()->input('homework_classid'));
            }
            if (request()->has('homework_subject') && request()->input('homework_subject') != '' && request()->input('homework_subject') != '99') {
                $res->where('subject_id', request()->input('homework_subject'));
            }
            
        }else {
            request()->merge([
                'homework_title'   => '',
                'homework_classid' => '99',
                'homework_subject' => '99',
            ]);
        }       
    
        $res = $res->paginate(20);
             
        $class_list = $this->createInfoRepository->getClassSetup();
        $classes[0]="All";
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }  
        
        $subject_list = $this->categoryRepository->getSubject();
        foreach($subject_list as $a) {
            $subject[$a->id] = $a->name;
        }  
        
        $academic_list = $this->categoryRepository->getAcademicYear();
        $academic=[];
        foreach($academic_list as $a) {
            $academic[$a->id] = $a->name;
        }

        return view('admin.createinformation.homework.index',[
            'classes'       =>$classes,
            'subject'       =>$subject,
            'academic'      =>$academic,
            'list_result'   => $res]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class_list = $this->createInfoRepository->getClassSetup();  
        
        $subject_list = $this->categoryRepository->getSubject();
        
        $academic_list = $this->categoryRepository->getAcademicYear();             

        return view('admin.createinformation.homework.create',[
            'classes'       =>$class_list,
            'subject'       =>$subject_list,
            'academic'      =>$academic_list,
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
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        } 
        if ($request->academic_year == '99') {
            array_push($errmsg,'Academic Year');
        } 
        if ($request->subject == '99') {
            array_push($errmsg,'Subject');
        } 
       
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }

        $latestId = Homework::latest()->value('id');

        if($request->hasFile('homework_file')){
            $homeworkfile=$request->file('homework_file');
            $extension = $homeworkfile->extension();
            $homeworkfile_name = (intval($latestId) + 1). "_" . time() . "." . $extension;
        }else{
            $homeworkfile_name="";
        }    
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'title'              =>$request->title,
                'class_id'           =>$request->class_id,
                'academic_year_id'   =>$request->academic_year_id,
                'subject_id'         =>$request->subject_id,
                'homework_file'      =>$homeworkfile_name,
                'due_date'           =>$request->due_date,
                'description'        =>$request->description,
                'remark'             =>$request->remark,
                'created_by'         =>$login_id,
                'updated_by'         =>$login_id,
                'created_at'         =>$nowDate,
                'updated_at'         =>$nowDate
            );
            $result=Homework::insert($insertData);
                        
            if($result){  
                if ($homeworkfile_name != "") {
                    $homeworkfile->move(public_path('assets/homework_files'),$homeworkfile_name);   
                }      
                DB::commit();
                return redirect(url('admin/homework/list'))->with('success','Homework Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Homework Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Homework Created Fail !');
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
        
        $subject_list = $this->categoryRepository->getSubject();
        
        $academic_list = $this->categoryRepository->getAcademicYear();     

        $res = Homework::where('id',$id)->get();
        return view('admin.createinformation.homework.update',[
            'classes'       =>$class_list,
            'subject'       =>$subject_list,
            'academic'      =>$academic_list,
            'result'        =>$res
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
            'title'       =>'required',
        ]); 
       
        $errmsg =array();
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        } 
        if ($request->academic_year == '99') {
            array_push($errmsg,'Academic Year');
        } 
        if ($request->subject == '99') {
            array_push($errmsg,'Subject');
        } 

        if($request->hasFile('homework_file')){

            $previous_homeworkfile=$request->previous_homeworkfile;
            @unlink(public_path('/assets/homework_files/'. $previous_homeworkfile));

            $homeworkfile=$request->file('homework_file');
            $extension = $homeworkfile->extension();
            $homeworkfile_name = $id. "_" . time() . "." . $extension;
        }else{
            $homeworkfile_name="";
        } 
        
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'title'              =>$request->title,
                'class_id'           =>$request->class_id,
                'academic_year_id'   =>$request->academic_year_id,
                'subject_id'         =>$request->subject_id,
                'due_date'           =>$request->due_date,
                'description'        =>$request->description,
                'remark'             =>$request->remark,
                'updated_by'         =>$login_id,
                'updated_at'         =>$nowDate

            );
            if ($homeworkfile_name != "") {
                $updateData['homework_file'] = $homeworkfile_name;
            }
            $result=Homework::where('id',$id)->update($updateData);
                      
            if($result){
                if ($homeworkfile_name != "") {
                    $homeworkfile->move(public_path('assets/homework_files'),$homeworkfile_name);  
                }  
                DB::commit();               
                return redirect(url('admin/homework/list'))->with('success','Homework Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Homework Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Homework Updared Fail !');
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
            $checkData = Homework::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = Homework::where('id',$id)->delete();
                if($res){
                    $file=$checkData['homework_file'];
                    if($file != ''){
                        @unlink(public_path('/assets/homework_files/'. $file));
                    } 
                    DB::commit();
                    //To return list
                    return redirect(url('admin/homework/list'))->with('success','Homework Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('error','There is no result with this Homework.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Homework Deleted Failed!');
        }
    }
}
