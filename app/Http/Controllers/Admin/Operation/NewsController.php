<?php

namespace App\Http\Controllers\Admin\Operation;

use App\Http\Controllers\Controller;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Models\AcademicYear;
use App\Models\News;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepository;
    private $academicId;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository) 
    {
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
    public function NewsList(Request $request)
    {
        $res = News::select('news.*');
        if ($request['action'] == 'search') {
            if (request()->has('news_studentid') && request()->input('news_studentid') != '') {
                $res->where('student_id',request()->input('news_studentid'));
            }
            if (request()->has('news_classid') && request()->input('news_classid') != '') {
                $res->where('class_id', request()->input('news_classid'));
            }
        }else {
            request()->merge([
                'news_studentid'   => null,
                'news_classid'     => null
            ]);
        }  
        $res=$res->paginate(20);

        $class_list       = $this->createInfoRepository->getClassSetup();
        $classes = [];
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        return view('admin.operation.news.index',[
            'list_result'      => $res,
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
        $class_list = $this->createInfoRepository->getClassSetup();

        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }
       
        return view('admin.operation.news.create',[
            'class_list'        =>$classes
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

        $latestId = News::latest()->value('id');
        if($request->hasFile('image')){
            $image=$request->file('image');
            $extension = $image->extension();
            $image_name = (intval($latestId === null ? 0 : $latestId) + 1). "_" . time() . "." . $extension;
        }else{
            $image_name="";
        }   
       
        DB::beginTransaction();
        try{
            $insertData = array(
                'class_id'          =>$request->class_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'title'             =>$request->title,
                'description'       =>$request->description,
                'image'             =>$image_name,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=News::insert($insertData);
                        
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/news_images'),$image_name);   
                }              
                DB::commit();
                return redirect(url('admin/news/list'))->with('success','New Created Successfully!');
            }else{
                return redirect()->back()->with('danger','New Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','New Created Fail !');
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
        $res = News::where('id',$id)->get();
        $class_id = $res[0]->class_id;
        
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

        return view('admin.operation.news.update',[
            'result'           => $res,
            'class_list'       => $classes,
            'student_list'     => $studentList,
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

        if($request->hasFile('image')){

            $previous_img=$request->previous_image;
            @unlink(public_path('/assets/news_images/'. $previous_img));

            $image=$request->file('image');
            $extension = $image->extension();
            $image_name = $id. "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 
       
        DB::beginTransaction();
        try{
            $updateData = array(
                'class_id'          =>$request->class_id,
                'student_id'        =>$request->student_id,
                'registration_id'   =>$registration_id,
                'title'             =>$request->title,
                'description'       =>$request->description,
                'image'             =>$image_name,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            if ($image_name != "") {
                $updateData['image'] = $image_name;
            }
            
            $result=News::where('id',$id)->update($updateData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/news_images'),$image_name);  
                } 
                 
                DB::commit();               
                return redirect(url('admin/news/list/'))->with('success','New Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','New Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','New Updared Fail !');
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
            $checkData = News::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = News::where('id',$id)->delete();
                if($res){
                    //To delete image
                    $image=$checkData['image'];
                    if($image !=''){
                        @unlink(public_path('/assets/news_images/'. $image));
                    }            
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this new.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/news/list/'))->with('success','New Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','New Deleted Failed!');
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
    
}
