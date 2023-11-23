<?php

namespace App\Http\Controllers\Admin\Registration;

use App\Http\Controllers\Controller;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\WaitingList;
use App\Models\WaitingRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WaitingListRegController extends Controller
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
    public function waitingRegList(Request $request)
    {
        $res = WaitingRegistration::select('waiting_registration.*');
        if ($request['action'] == 'search') {
            if (request()->has('waitinglist_name') && request()->input('waitinglist_name') != '') {
                $res->where('waiting_registration.name','Like', '%' . request()->input('waitinglist_name') . '%');
            }
            if (request()->has('waitinglist_email') && request()->input('waitinglist_email') != '') {
                $res->where('waiting_registration.email', request()->input('waitinglist_email'));
            }
            if (request()->has('waitinglist_phone') && request()->input('waitinglist_phone') != '') {
                $res->where('waiting_registration.phone', request()->input('waitinglist_phone'));
            }
        }else {
            request()->merge([
                'waitinglist_name'    => null,
                'waitinglist_email'   => null,
                'waitinglist_phone'   => null
            ]);
        }  
        
        $res=$res->paginate(20);

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

        return view('admin.registration.waitingreg.index',[
            'academic_list'=>$academic,
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
        $academic_list = $this->categoryRepository->getAcademicYear();
        $grade_list    = $this->categoryRepository->getGrade();
        //$waiting_number= WaitingRegistration::count();
        return view('admin.registration.waitingreg.create',[
            'academic_list'=>$academic_list,
            'grade_list'   =>$grade_list,
           // 'waiting_number'=>($waiting_number+1),
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
            'enquiry_date'    => 'required'
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
            //To get the waiting number
            $waiting_number= WaitingRegistration::where('grade_id',$request->grade_id)->max('waiting_number');
            $insertData = array(
                'name'              =>$request->name,
                'phone'             =>$request->phone,
                'email'             =>$request->email,
                'grade_id'          =>$request->grade_id,
                'academic_year_id'  =>$request->academic_year_id,
                'waiting_number'    =>$waiting_number + 1,
                'enquiry_date'      =>$request->enquiry_date,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=WaitingRegistration::insert($insertData);
                        
            if($result){            
                DB::commit();
                return redirect(url('admin/waitinglist_reg/list'))->with('success','Waiting List Registration Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Waiting List Registration Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Waiting List Registration Created Fail !');
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
        $res = WaitingRegistration::where('id',$id)->get();
        $grade_list    = $this->categoryRepository->getGrade();
        $academic_list = $this->categoryRepository->getAcademicYear();

        //$waiting_number= WaitingRegistration::where('id','<=',$id)->count();
        return view('admin.registration.waitingreg.update',[
            'result'=>$res, 
            'grade_list'     =>$grade_list,
            'academic_list'  =>$academic_list,
            //'waiting_number' =>$waiting_number
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
            'name'            =>'required|min:3',
            'enquiry_date'    => 'required'
        ]); 

        DB::beginTransaction();
        try{
            $updateData = array(
                'name'              =>$request->name,
                'phone'             =>$request->phone,
                'email'             =>$request->email,
                'grade_id'          =>$request->grade_id,
                'academic_year_id'  =>$request->academic_year_id,
                'enquiry_date'       =>$request->enquiry_date,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            $result=WaitingRegistration::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/waitinglist_reg/list'))->with('success','Waiting List Registration Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Waiting List Registration Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Waiting List Registration Updared Fail !');
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
            $checkData = WaitingRegistration::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = WaitingRegistration::where('id',$id)->delete();
            }else{
                return redirect()->back()->with('error','There is no result with this waiting list registraion.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/waitinglist_reg/list'))->with('success','Waiting List Registration Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','Waiting List Registration Deleted Failed!');
        }
    }
}
