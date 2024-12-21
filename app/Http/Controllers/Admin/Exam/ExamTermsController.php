<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamTerms;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamTermsController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository) 
    {
        $this->categoryRepository = $categoryRepository;
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function examTermsList(Request $request)
    {
        $res = ExamTerms::select('exam_terms.*');
        if ($request['action'] == 'search') {
            if (request()->has('examterms_name') && request()->input('examterms_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('examterms_name') . '%');
            }
            if (request()->has('examterms_grade') && request()->input('examterms_grade') != '') {
                $res->where('grade_id', request()->input('examterms_grade'));
            }
            if (request()->has('examterms_academicyear') && request()->input('examterms_academicyear') != '') {
                $res->where('academic_year_id', request()->input('examterms_academicyear'));
            }
        }else {
            request()->merge([
                'examterms_name'      => null,
                'examterms_grade' => null,
                'examterms_academicyear'    => null,
            ]);
        }       
        $res = $res->paginate(20);
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
        return view('admin.exam.examterms.examterms_list',['list_result' => $res,'grade_list' => $grade,
        'academic_list' => $academic]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function examTermsCreate()
    {
        $academic_list = $this->categoryRepository->getAcademicYear();
        $grade_list    = $this->categoryRepository->getGrade();
        
        return view('admin.exam.examterms.examterms_create',[
            'academic_list'=>$academic_list,
            'grade_list'   =>$grade_list,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function examTermsSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'            =>'required|min:3',
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
            if ($request->grade_id=='0') {
                $grades = Grade::get('id');
                foreach ($grades as $grade) {
                    $insertData[] = array(
                        'name'              =>$request->name,
                        'grade_id'          =>$grade['id'],
                        'academic_year_id'  =>$request->academic_year_id,
                        'created_by'        =>$login_id,
                        'updated_by'        =>$login_id,
                        'created_at'        =>$nowDate,
                        'updated_at'        =>$nowDate
                    );
                }
            } else {
                $insertData = array(
                    'name'              =>$request->name,
                    'grade_id'          =>$request->grade_id,
                    'academic_year_id'  =>$request->academic_year_id,
                    'created_by'        =>$login_id,
                    'updated_by'        =>$login_id,
                    'created_at'        =>$nowDate,
                    'updated_at'        =>$nowDate
                );
            }
            
            $result=ExamTerms::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/exam_terms/list'))->with('success','Exam Terms Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Exam Terms Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Exam Terms Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examTermsEdit($id)
    {
        $academic_list = $this->categoryRepository->getAcademicYear();
        $grade_list    = $this->categoryRepository->getGrade();
    
        $res = ExamTerms::where('id',$id)->get();
        return view('admin.exam.examterms.examterms_update',[
            'academic_list'=>$academic_list,
            'grade_list'   =>$grade_list,
            'result'=>$res]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examTermsUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'name'            =>'required|min:3'
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
            $termsData = array(
                'name'              =>$request->name,
                'grade_id'          =>$request->grade_id,
                'academic_year_id'  =>$request->academic_year_id,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate

            );
            
            $result=ExamTerms::where('id',$id)->update($termsData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/exam_terms/list'))->with('success','Exam Terms Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Exam Terms Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Exam Terms Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examTermsDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = ExamTerms::where('id',$id)->first();

            if (!empty($checkData)) {
                try {
                    // Attempt to delete the record
                    $res = ExamTerms::where('id',$id)->forceDelete();
                   
                    if($res){
                        DB::commit();
                        //To return list
                        return redirect(url('admin/exam_terms/list'))->with('success','Exam Terms Deleted Successfully!');
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    // Check if the exception is due to a foreign key constraint violation
                    if ($e->errorInfo[1] === 1451) {
                        return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                    }
                    return redirect()->back()->with('danger','An error occurred while deleting the record.');
                }
                
            }else{
                return redirect()->back()->with('danger','There is no result with this exam terms.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Exam Terms Deleted Failed!');
        }
    }
}
