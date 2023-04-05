<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\GradeLevelFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CategoryRepositoryInterface;

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
    public function index()
    {
        $res = GradeLevelFee::paginate(10);
        $academic_list = $this->categoryRepository->getAcademicYear();
        $grade_list    = $this->categoryRepository->getGrade();
        $branch_list   = $this->categoryRepository->getBranch();
        return view('admin.category.gradelevelfee_index',['academic_list'=>$academic_list,
        'grade_list'=>$grade_list,'branch_list'=>$branch_list,'list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'created_by'          =>$login_id,
            'updated_by'          =>$login_id,
            'created_at'          =>$nowDate,
            'updated_at'          =>$nowDate
        );

        $result=GradeLevelFee::insert($insertData);
        
        if($result){
            $res = GradeLevelFee::paginate(10);
            return redirect(route('grade_level_fee.index',['list_result' => $res]))
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
        //
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
        //
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

                return redirect(route('grade_level_fee.index',['list_result' => $listres]))
                            ->with('success','Grade Level Fee Deleted Successfully!');
            }
        }else{
            return redirect()->back()->with('error','There is no result with this grade level fee.');
        }
    }
}
