<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\RegistrationRepositoryInterface;
use App\Models\ClassSetup;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamMarks;
use App\Models\ExamTerms;
use App\Models\ExamRules;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ExamMarksController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;
    private RegistrationRepositoryInterface $regRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository,RegistrationRepositoryInterface $regRepository) 
    {
        $this->categoryRepository = $categoryRepository;
        $this->regRepository     = $regRepository;
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function examMarksList(Request $request)
    {
        $res = ExamMarks::select('exam_marks.*')
                ->leftjoin('class_setup','exam_marks.class_id','=','class_setup.id');
        if ($request['action'] == 'search') {
            if (request()->has('exammarks_studentid') && request()->input('exammarks_studentid') != '') {
                $res->where('student_id', request()->input('exammarks_studentid'));
            }
            if (request()->has('exammarks_examterms') && request()->input('exammarks_examterms') != '') {
                $res->where('exam_terms_id', request()->input('exammarks_examterms'));
            }
            if (request()->has('exammarks_classid') && request()->input('exammarks_classid') != '') {
                $res->where('exam_marks.class_id', request()->input('exammarks_classid'));
            }
            if (request()->has('exammarks_gradeid') && request()->input('exammarks_gradeid') != '') {
                $res->where('class_setup.grade_id', request()->input('exammarks_gradeid'));
            }
        }else {
            request()->merge([
                'exammarks_studentid'      => null,
                'exammarks_examterms' => null,
                'exammarks_classid'    => null,
                'exammarks_gradeid'    => null,
            ]);
        }       
        $res = $res->paginate(20);

        $subject_list = $this->categoryRepository->getSubject();
        $subjects=[];
        foreach($subject_list as $a) {
            $subjects[$a->id] = $a->name;
        }

        $grade_list = $this->categoryRepository->getGrade();
        $grades=[];
        foreach($grade_list as $a) {
            $grades[$a->id] = $a->name;
        }

        $examterms_list = $this->getExamTerms();
        $examterms=[];
        foreach($examterms_list as $a) {
            $examterms[$a->id] = $a->name;
        }

        $class_list    = $this->regRepository->getClass();
        $classes=[];
        foreach($class_list as $a) {
            $classes[$a->id] = $a->name;
        }

        $examrules_list = $this->getExamRules($res);

        return view('admin.exam.exammarks.exammarks_list',[
            'list_result' => $res,
            'subjects' => $subjects,
            'examterms' => $examterms, 
            'classes' => $classes,
            'grade_list' => $grades,
            'exam_rules' => $examrules_list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function examMarksCreate()
    {
        $subjects = $this->categoryRepository->getSubject();
        $examterms = $this->getExamTerms();
        $classes   = $this->regRepository->getClass();
        $student_list    = $this->regRepository->getStudentInfo();
        
        return view('admin.exam.exammarks.exammarks_create',[
            'subjects'    => $subjects,
            'examterms'   => $examterms, 
            'classes'     => $classes,
            'student_list'=>$student_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function examMarksSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'mark'            =>'required|integer',
        ]); 
       
        $errmsg =array();
        if ($request->student_id == '99') {
            array_push($errmsg,'Student ID');
        }  
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        }  
        if ($request->exam_terms_id == '99') {
            array_push($errmsg,'Exam Terms');
        } 
        if ($request->subject == '99') {
            array_push($errmsg,'Subject');
        } 
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }
                
        DB::beginTransaction();
        try{
            $insertData = array(
                'class_id'          =>$request->class_id,
                'exam_terms_id'     =>$request->exam_terms_id,
                'student_id'        =>$request->student_id,
                'subject_id'        =>$request->subject_id,
                'mark'              =>$request->mark,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=ExamMarks::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/exam_marks/list'))->with('success','Exam Marks Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Exam Marks Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Exam Marks Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examMarksEdit($id)
    {

        $classes    = $this->regRepository->getClass();
        $res = ExamMarks::leftjoin('class_setup','class_setup.id','exam_marks.class_id')
                    ->where('exam_marks.id',$id)
                    ->select('exam_marks.*','class_setup.grade_id as grade_id')
                    ->get();

        if ($res) {
            $studentList = DB::table('student_registration')
                        ->join('student_info','student_info.student_id','student_registration.student_id')
                        ->select('student_info.*')
                        ->where('new_class_id','=',$res[0]->class_id)
                        ->get();

            $subjectList = Subject::where('subject.grade_id','=',$res[0]->grade_id)
                            ->select('subject.*')
                            ->get();
            $examTermsList = ExamTerms::where('exam_terms.grade_id','=',$res[0]->grade_id)
                            ->select('exam_terms.*')
                            ->get();
        } else {
            $subjects = $this->categoryRepository->getSubject();
            $examterms = $this->getExamTerms();
            $student_list    = $this->regRepository->getStudentInfo();
        }

    
        return view('admin.exam.exammarks.exammarks_update',[
            'subjects'    => $subjectList,
            'examterms'   => $examTermsList, 
            'classes'     => $classes,
            'student_list'=>$studentList,
            'result'      =>$res
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examMarksUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'student_id'       =>'required',
            'mark'             =>'required|integer',
        ]);

        $errmsg =array();
        if ($request->student_id == '99') {
            array_push($errmsg,'Student ID');
        }  
        if ($request->class_id == '99') {
            array_push($errmsg,'Class');
        }  
        if ($request->exam_terms_id == '99') {
            array_push($errmsg,'Exam Terms');
        } 
        if ($request->subject == '99') {
            array_push($errmsg,'Subject');
        } 
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }

        DB::beginTransaction();
        try{
            $marksData = array(
                'class_id'          =>$request->class_id,
                'exam_terms_id'     =>$request->exam_terms_id,
                'student_id'        =>$request->student_id,
                'subject_id'        =>$request->subject_id,
                'mark'              =>$request->mark,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate

            );
            
            $result=ExamMarks::where('id',$id)->update($marksData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/exam_marks/list'))->with('success','Exam Marks Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Exam Marks Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Exam Marks Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examMarksDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = ExamMarks::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = ExamMarks::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/exam_marks/list'))->with('success','Exam Marks Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('danger','There is no result with this exam marks.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Exam Marks Deleted Failed!');
        }
    }

    public function getExamTerms() {
        $examterms_list = ExamTerms::all();      
        return $examterms_list;
    }

    public function getExamRules($res) {
        $examRules = ExamRules::all();
        $examMarkRule = [];
        foreach ($res as $m) {
            $exam_mark = $m->mark;
            foreach ($examRules as $rule) {
                if ($rule->mark_range_from <= $exam_mark && $exam_mark <= $rule->mark_range_to) {
                    $examMarkRule[$m->id] = $rule->title;
                }
            }
        }
        return $examMarkRule;
    }

    public function changeClass(Request $request) {
        
        $classSetup = ClassSetup::where('id',$request->class_id)->first();

        if (!empty($classSetup)) {
            $studentList = DB::table('student_registration')
                            ->join('student_info','student_info.student_id','student_registration.student_id')
                            ->select('student_info.*')
                            ->where('new_class_id','=',$request->class_id)
                            ->get();

            $subjectList = Subject::where('subject.grade_id','=',$classSetup->grade_id)
                            ->select('subject.*')
                            ->get();
            $examTermsList = ExamTerms::where('exam_terms.grade_id','=',$classSetup->grade_id)
                            ->select('exam_terms.*')
                            ->get();
        
            return response()->json(array(
                'msg'                => 'found',
                'student_data'       => $studentList,
                'subject_data'       => $subjectList,
                'exam_terms_data'    => $examTermsList
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    public function changeExamTerms(Request $request) {
        $examTerms = ExamTerms::where('id',$request->exam_terms_id)->first();
    
        if (!empty($examTerms)) {
            $subjectList = Subject::where('grade_id','=',$examTerms->grade_id)
                            ->select('subject.*')
                            ->get();
        
            return response()->json(array(
                'msg'                => 'found',
                'subject_data'       => $subjectList
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }
}
