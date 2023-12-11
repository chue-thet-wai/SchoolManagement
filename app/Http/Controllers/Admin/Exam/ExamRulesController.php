<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\ExamRules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamRulesController extends Controller
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
    public function examRulesList(Request $request)
    {
        $res = ExamRules::select('exam_rules.*');
        if ($request['action'] == 'search') {
            if (request()->has('examrules_title') && request()->input('examrules_title') != '') {
                $res->where('title', 'Like', '%' . request()->input('examrules_title') . '%');
            }
        }else {
            request()->merge([
                'examruls_name'      => null,
            ]);
        }       
        $res = $res->paginate(20);

        return view('admin.exam.examrules.examrules_list',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function examRulesCreate()
    {
        
        return view('admin.exam.examrules.examrules_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function examRulesSave(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'title'            =>'required',
        ]); 
       
          
        DB::beginTransaction();
        try{
            $insertData = array(
                'title'              =>$request->title,
                'mark_range_from'    =>$request->mark_range_from,
                'mark_range_to'      =>$request->mark_range_to,
                'created_by'         =>$login_id,
                'updated_by'         =>$login_id,
                'created_at'         =>$nowDate,
                'updated_at'         =>$nowDate
            );
            $result=ExamRules::insert($insertData);
                        
            if($result){      
                DB::commit();
                return redirect(url('admin/exam_rules/list'))->with('success','Exam Rules Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Exam Rules Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Exam Rules Created Fail !');
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examRulesEdit($id)
    {
    
        $res = ExamRules::where('id',$id)->get();
        return view('admin.exam.examrules.examrules_update',[
            'result'=>$res]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examRulesUpdate(Request $request, $id)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        $request->validate([
            'title'            =>'required'
        ]);

        DB::beginTransaction();
        try{
            $updateData = array(
                'title'              =>$request->title,
                'mark_range_from'    =>$request->mark_range_from,
                'mark_range_to'      =>$request->mark_range_to,
                'updated_by'         =>$login_id,
                'updated_at'         =>$nowDate

            );
            
            $result=ExamRules::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/exam_rules/list'))->with('success','Exam Rules Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Exam Rules Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Exam Rules Updared Fail !');
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function examRulesDelete($id)
    {
        DB::beginTransaction();
        try{
            $checkData = ExamRules::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = ExamRules::where('id',$id)->delete();
                if($res){
                    DB::commit();
                    //To return list
                    return redirect(url('admin/exam_rules/list'))->with('success','Exam Rules Deleted Successfully!');
                }
            }else{
                return redirect()->back()->with('error','There is no result with this exam rules.');
            }
           

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Exam Rules Deleted Failed!');
        }
    }
}
