<?php

namespace App\Http\Controllers\Admin\Registration;

use App\Http\Controllers\Controller;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\CancelRegistration;
use App\Models\ClassSetup;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelListRegController extends Controller
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
        $res = CancelRegistration::paginate(10);

        $grade_list    = $this->categoryRepository->getGrade();
        $grade=[];
        foreach($grade_list as $a) {
            $grade[$a->id] = $a->name;
        }

        return view('admin.registration.cancelreg.index',[
            'grade_list'   =>$grade,
            'list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grade_list    = $this->categoryRepository->getGrade();
        return view('admin.registration.cancelreg.create',[
            'grade_list'   =>$grade_list
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
            'registration_no'            =>'required',
        ]); 
        $studentRegSearch = StudentRegistration::where('registration_no',$request->registration_no)->first();
        if (empty($studentRegSearch)) {
            return redirect()->back()->with('danger','Registration ID Date not found!');
        }

        /*$errmsg =array();
        if ($request->grade_id == '99') {
            array_push($errmsg,'Grade');
        }  
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }*/
             
        DB::beginTransaction();
        try{
            $insertData = array(
                'registration_no'   =>$request->registration_no,
                'student_id'        =>$request->student_id,
                'cancel_date'       =>$request->cancel_date,
                'refund_amount'     =>$request->refund_amount,
                'remark'            =>$request->remark,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=CancelRegistration::insert($insertData);
                        
            if($result){            
                DB::commit();
                return redirect(route('cancel_reg.index'))->with('success','Cancel Registration Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Cancel Registration Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Cancel Registration Created Fail !');
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
        $grade_name ='';
        $res = CancelRegistration::join('student_registration','student_registration.registration_no','=','cancel_registration.registration_no')
                ->join('student_info','student_info.student_id','=','student_registration.student_id')
                ->where('cancel_registration.id',$id)
                ->select('cancel_registration.*','student_info.name as student_name',
                'student_registration.new_class_id')
                ->get();
        if (count($res) > 0) {
            log::info($res[0]->new_class_id);
            $grade = ClassSetup::join('grade','grade.id','=','class_setup.grade_id')
                ->where('class_setup.id',$res[0]->new_class_id)
                ->select('grade.name as grade_name')->first();
            if (!empty($grade)) {
                $grade_name = $grade->grade_name;
            }
            
        }
        log::info('aaa');
        log::info($grade_name);
        
        return view('admin.registration.cancelreg.update',['result'=>$res,'grade_name'=>$grade_name]);
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
            'registration_no'            =>'required',
        ]); 
        $studentRegSearch = StudentRegistration::where('registration_no',$request->registration_no)->first();
        if (empty($studentRegSearch)) {
            return redirect()->back()->with('danger','Registration ID Date not found!');
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'registration_no'   =>$request->registration_no,
                'student_id'        =>$request->student_id,
                'cancel_date'       =>$request->cancel_date,
                'refund_amount'     =>$request->refund_amount,
                'remark'            =>$request->remark,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            $result=CancelRegistration::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(route('cancel_reg.index'))->with('success','Cancel Registration Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Cancel Registration Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Cancel Registration Updared Fail !');
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
            $checkData = CancelRegistration::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = CancelRegistration::where('id',$id)->delete();
            }else{
                return redirect()->back()->with('error','There is no result with this cancel registraion.');
            }
            DB::commit();
            //To return list
            return redirect(route('cancel_reg.index'))->with('success','Cancel Registration Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Cancel Registration Deleted Failed!');
        }
    }
}
