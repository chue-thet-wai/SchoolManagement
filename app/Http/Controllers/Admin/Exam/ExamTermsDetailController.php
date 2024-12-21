<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\ExamTermsDetail;
use App\Models\ExamTerms;
use App\Models\Subject;

class ExamTermsDetailController extends Controller
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
    public function ExamTermsDetailList(Request $request)
    {  
        $exam_terms_id = $request->exam_terms_id;
        $exam_term = ExamTerms::where('exam_terms.id',$exam_terms_id)
                        ->leftjoin('grade','grade.id','exam_terms.grade_id')
                        ->leftjoin('academic_year','academic_year.id','exam_terms.academic_year_id')
                        ->select('exam_terms.id as exam_terms_id','exam_terms.name as exam_terms_name',
                        'grade.name as grade_name','academic_year.name as academic_year_name')
                        ->first()->toArray();
        
        $exam_term_data = [];
        if ($exam_term) {
            $exam_term_data['id']            = $exam_terms_id;
            $exam_term_data['name']          = $exam_term['exam_terms_name'];
            $exam_term_data['grade']         = $exam_term['grade_name'];
            $exam_term_data['academic_year'] = $exam_term['academic_year_name'];
        }
        $res = ExamTermsDetail::where('exam_terms_id',$exam_terms_id)->select('exam_terms_detail.*');
        $res = $res->paginate(20);
        
        $subject_list = $this->categoryRepository->getSubject();
        $subject = [];
        foreach($subject_list as $a) {
            $subject[$a->id] = $a->name;
        } 
        
        return view('admin.exam.examtermsdetail.index',[
            'subject_list'  =>$subject,
            'exam_term_data'=>$exam_term_data,
            'list_result'   => $res]);
    }

    public function ExamTermsDetailListwithGet($exam_terms_id)
    {  
        $exam_term = ExamTerms::where('exam_terms.id',$exam_terms_id)
                        ->leftjoin('grade','grade.id','exam_terms.grade_id')
                        ->leftjoin('academic_year','academic_year.id','exam_terms.academic_year_id')
                        ->select('exam_terms.id as exam_terms_id','exam_terms.name as exam_terms_name',
                        'grade.name as grade_name','academic_year.name as academic_year_name')
                        ->first()->toArray();
        
        $exam_term_data = [];
        if ($exam_term) {
            $exam_term_data['id']            = $exam_terms_id;
            $exam_term_data['name']          = $exam_term['exam_terms_name'];
            $exam_term_data['grade']         = $exam_term['grade_name'];
            $exam_term_data['academic_year'] = $exam_term['academic_year_name'];
        }
        $res = ExamTermsDetail::where('exam_terms_id',$exam_terms_id)->select('exam_terms_detail.*');
        $res = $res->paginate(20);
        
        $subject_list = $this->categoryRepository->getSubject();
        
        $subject = [];
        foreach($subject_list as $a) {
            $subject[$a->id] = $a->name;
        } 
        
        return view('admin.exam.examtermsdetail.index',[
            'subject_list'  =>$subject,
            'exam_term_data'=>$exam_term_data,
            'list_result'   => $res]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($exam_terms_id)
    {    
        $exam_term = ExamTerms::where('exam_terms.id',$exam_terms_id)
                        ->leftjoin('grade','grade.id','exam_terms.grade_id')
                        ->leftjoin('academic_year','academic_year.id','exam_terms.academic_year_id')
                        ->select('exam_terms.id as exam_terms_id','exam_terms.name as exam_terms_name',
                        'grade.name as grade_name','grade.id as grade_id','academic_year.name as academic_year_name')
                        ->first()->toArray();  
        $exam_term_data = [];
        $grade_id='';
        if ($exam_term) {
            $exam_term_data['id']            = $exam_terms_id;
            $exam_term_data['name']          = $exam_term['exam_terms_name'];
            $exam_term_data['grade']         = $exam_term['grade_name'];
            $exam_term_data['academic_year'] = $exam_term['academic_year_name'];
            $grade_id = $exam_term['grade_id'];
        }  
        //$subject_list = $this->categoryRepository->getSubject();  
        $subject_list = Subject::leftjoin('grade','grade.id','subject.grade_id')
                ->where('subject.grade_id',$grade_id)
                ->select('subject.*','grade.name as grade_name')
                ->get();
        
        return view('admin.exam.examtermsdetail.create',[
            'exam_term_data'=>$exam_term_data,
            'subject_list'  =>$subject_list
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
            'exam_terms_id'   =>'required',
            'subject_id'      =>'required',
            //'subject_image'   =>'mimes:jpeg,jpg,png | max:1000',
        ]); 
       
        $latestId = ExamTermsDetail::latest()->value('id');

        if($request->hasFile('subject_image')){
            $image=$request->file('subject_image');
            $extension = $image->extension();
            $image_name = (intval($latestId === null ? 0 : $latestId) + 1). "_" . time() . "." . $extension;
        }else{
            $image_name="";
        }     
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'exam_terms_id'     =>$request->exam_terms_id,
                'subject_id'        =>$request->subject_id,
                'exam_date'         =>$request->exam_date,
                'subject_image'     =>$image_name,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=ExamTermsDetail::insert($insertData);
                        
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/subject_images'),$image_name);   
                }              
                DB::commit();
                return redirect(url('admin/exam_terms_detail/list/'.$request->exam_terms_id))->with('success','Exam Terms Detail Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Exam Terms Detail Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Exam Terms Detail Created Fail !');
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

        $res = ExamTermsDetail::where('id',$id)->get();
        $exam_terms_id = $res[0]['exam_terms_id'];

        $exam_term = ExamTerms::where('exam_terms.id',$exam_terms_id)
                ->leftjoin('grade','grade.id','exam_terms.grade_id')
                ->leftjoin('academic_year','academic_year.id','exam_terms.academic_year_id')
                ->select('exam_terms.id as exam_terms_id','exam_terms.name as exam_terms_name',
                'grade.name as grade_name','grade.id as grade_id','academic_year.name as academic_year_name')
                ->first()->toArray();  
        $exam_term_data = [];
        $grade_id = '';
        if ($exam_term) {
            $exam_term_data['id']            = $exam_terms_id;
            $exam_term_data['name']          = $exam_term['exam_terms_name'];
            $exam_term_data['grade']         = $exam_term['grade_name'];
            $exam_term_data['academic_year'] = $exam_term['academic_year_name'];
            $grade_id = $exam_term['grade_id'];
        }  

         //$subject_list = $this->categoryRepository->getSubject();  
         $subject_list = Subject::leftjoin('grade','grade.id','subject.grade_id')
            ->where('subject.grade_id',$grade_id)
            ->select('subject.*','grade.name as grade_name')
            ->get();

        return view('admin.exam.examtermsdetail.update',[
            'subject_list'  =>$subject_list,
            'exam_term_data'=>$exam_term_data,
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
            'exam_terms_id'   =>'required',
            'subject_id'      =>'required',
            //'subject_image' =>'mimes:jpeg,jpg,png | max:1000',
        ]); 

        if($request->hasFile('subject_image')){

            $previous_img=$request->previous_image;
            @unlink(public_path('/assets/subject_images/'. $previous_img));

            $image=$request->file('subject_image');
            $extension = $image->extension();
            $image_name = $id. "_" . time() . "." . $extension;
        }else{
            $image_name="";
        } 

        DB::beginTransaction();
        try{
            $updateData = array(
                'exam_terms_id'     =>$request->exam_terms_id,
                'subject_id'        =>$request->subject_id,
                'exam_date'         =>$request->exam_date,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            if ($image_name != "") {
                $updateData['subject_image'] = $image_name;
            }
            
            $result=ExamTermsDetail::where('id',$id)->update($updateData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/subject_images'),$image_name);  
                } 
                 
                DB::commit();               
                return redirect(url('admin/exam_terms_detail/list/'.$request->exam_terms_id))->with('success','Exam Terms Detail Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Exam Terms Detail Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Exam Terms Detail Updared Fail !');
        }         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = ExamTermsDetail::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = ExamTermsDetail::where('id',$id)->delete();
                if($res){
                    //To delete image
                    $image=$checkData['subject_image'];
                    if($image !=''){
                        @unlink(public_path('/assets/subject_images/'. $image));
                    }            
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this exam terms detail.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/exam_terms_detail/list/'.$checkData['exam_terms_id']))->with('success','Exam Terms Detail Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Exam Terms Detail Deleted Failed!');
        }
    }

    public function getExamTerms() {
        $examterms_list = ExamTerms::all();      
        return $examterms_list;
    }
}
