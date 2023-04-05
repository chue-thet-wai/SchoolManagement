<?php

namespace App\Http\Controllers\Admin\Registration;

use App\Http\Controllers\Controller;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\CancelRegistration;
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
            'name'            =>'required|min:3',
        ]); 
        $errmsg =array();
        if ($request->grade_id == '99') {
            array_push($errmsg,'Grade');
        }  
        if (!empty($errmsg)) {
            $errmsg = implode(',',$errmsg);
            return redirect()->back()->with('danger','Please select '.$errmsg.'!');
        }
             
        DB::beginTransaction();
        try{
            $insertData = array(
                'registration_no'   =>$request->registration_no,
                'student_id'        =>$request->phone,
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
        $res = CancelRegistration::where('id',$id)->get();
        return view('admin.registration.cancelreg.update',['result'=>$res]);
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
            'name'            =>'required|min:3',
        ]); 

        DB::beginTransaction();
        try{
            $updateData = array(
                'registration_no'   =>$request->registration_no,
                'student_id'        =>$request->phone,
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
