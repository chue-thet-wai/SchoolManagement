<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\GradeLevelFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradeLevelFeeController extends Controller
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
    public function GradeLevelFeeList(Request $request)
    {  
        $res = GradeLevelFee::select('grade_level_fee.*');
        if ($request['action'] == 'search') {
            if (request()->has('grade_level_fee_branch_id') && request()->input('grade_level_fee_branch_id') != '') {
                $res->where('branch_id', request()->input('grade_level_fee_branch_id'));
            }
            if (request()->has('grade_level_fee_academic_year_id') && request()->input('grade_level_fee_academic_year_id') != '') {
                $res->where('academic_year_id', request()->input('grade_level_fee_academic_year_id'));
            }
            if (request()->has('grade_level_fee_grade_id') && request()->input('grade_level_fee_grade_id') != '') {
                $res->where('grade_id', request()->input('grade_level_fee_grade_id'));
            }
        }else {
            request()->merge([
                'grade_level_fee_branch_id'         => null,
                'grade_level_fee_academic_year_id'  => null,
                'grade_level_fee_grade_id'          => null,
            ]);
        }     
        $res = $res->paginate(20);  
        
        $academic_list_data = $this->categoryRepository->getAcademicYear();
        $academic_list=[];
        foreach($academic_list_data as $a) {
            $academic_list[$a->id] = $a->name;
        } 

        $branch_list_data   = $this->categoryRepository->getBranch();
        $branch_list=[];
        foreach($branch_list_data as $b) {
            $branch_list[$b->id] = $b->name;
        } 

        $grade_list_data    = $this->categoryRepository->getGrade();
        $grade_list=[];
        foreach($grade_list_data as $g) {
            $grade_list[$g->id] = $g->name;
        } 

        $feeType = $this->feeType();

        return view('admin.category.gradelevelfee_index',[
            'academic_list'=>$academic_list,
            'grade_list'  =>$grade_list,
            'branch_list' =>$branch_list,
            'fee_type'    =>$feeType,
            'list_result' => $res
        ]);
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
        $branch_list   = $this->categoryRepository->getBranch();
        $feeType = $this->feeType();

        return view('admin.category.gradelevelfee_registration',[
            'academic_list'=>$academic_list,
            'grade_list'  =>$grade_list,
            'branch_list' =>$branch_list,
            'fee_type'    => $feeType,
            'action'=>'Add'
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
            'amount'      =>'required',
        ]);
        $errmsg =array();
        if ($request->academicyr_id == '99') {
           array_push($errmsg,'Academic Year');
        } 
        if ($request->grade_id == '99') {
            array_push($errmsg,'Grade');
        }  
        if ($request->branch_id == '99') {
            array_push($errmsg,'Branch');
        }
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }
        $insertData = array(
            'academic_year_id'    =>$request->academicyr_id,
            'grade_id'            =>$request->grade_id,
            'branch_id'           =>$request->branch_id,
            'grade_level_amount'  =>$request->amount,
            'fee_type'            =>$request->fee_type,
            'created_by'          =>$login_id,
            'updated_by'          =>$login_id,
            'created_at'          =>$nowDate,
            'updated_at'          =>$nowDate
        );

        $result=GradeLevelFee::insert($insertData);
        
        if($result){
            return redirect(url('admin/grade_level_fee/list'))
                            ->with('success','Grade Level Fee Added Successfully!');
        }else{
            return redirect()->back()->with('danger','Grade Level Fee Added Fail !');
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
        $branch_list   = $this->categoryRepository->getBranch();
        $feeType       = $this->feeType();

        $update_res    = GradeLevelFee::where('id',$id)->get();

        return view('admin.category.gradelevelfee_registration',[
            'academic_list'=>$academic_list,
            'grade_list'  =>$grade_list,
            'branch_list' =>$branch_list,
            'fee_type'    => $feeType,
            'result'      => $update_res,
            'action'      => 'Update'
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
            'amount'      =>'required',
        ]);
        $errmsg =array();
        if ($request->academicyr_id == '99') {
           array_push($errmsg,'Academic Year');
        } 
        if ($request->grade_id == '99') {
            array_push($errmsg,'Grade');
        }  
        if ($request->branch_id == '99') {
            array_push($errmsg,'Branch');
        }
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'academic_year_id'    =>$request->academicyr_id,
                'grade_id'            =>$request->grade_id,
                'branch_id'           =>$request->branch_id,
                'grade_level_amount'  =>$request->amount,
                'fee_type'            =>$request->fee_type,
                'updated_by'          =>$login_id,
                'updated_at'          =>$nowDate
            );
            
            $result=GradeLevelFee::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/grade_level_fee/list'))->with('success','Grade Level Fee Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Grade Level Fee Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Grade Level Fee Updared Fail !');
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
        $checkData = GradeLevelFee::where('id',$id)->get()->toArray();

        if (!empty($checkData)) {
            
            $res = GradeLevelFee::where('id',$id)->delete();
            if($res){
                $listres = GradeLevelFee::paginate(10);

                return redirect(url('admin/grade_level_fee/list'))
                            ->with('success','Grade Level Fee Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this grade level fee.');
        }
    }

    public function feeType() {
        return [
            '0'=>'Monthly',
            '1'=>'One Time'
        ];
    }
}
