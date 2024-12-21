<?php

namespace App\Http\Controllers\Admin\Driver;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use App\Models\ClassSetup;
use App\Models\FerryStudent;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FerryStudentController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private $status;

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository = $userRepository;
        $this->status = array(
            '0'=>'Pending',
            '1'=>'Confirm',
            '2'=>'Active',
            '3'=>'Inactive',
            '4'=>'Reject'
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ferryStudentList(Request $request)
    {
        $res = FerryStudent::select('ferry_student.*');
        if ($request['action'] == 'search') {
            if (request()->has('ferrystudent_studentid') && request()->input('ferrystudent_studentid') != '') {
                $res->where('student_id',request()->input('ferrystudent_studentid'));
            }
            if (request()->has('ferrystudent_regno') && request()->input('ferrystudent_regno') != '') {
                $res->where('registration_no', request()->input('ferrystudent_regno'));
            }
        }else {
            request()->merge([
                'ferrystudent_studentid'      => null,
                'ferrystudent_regno'          => null
            ]);
        } 
        $res=$res->paginate(20); 

        $ferry_ways    = $this->getFerryWay();
        $township      = $this->userRepository->getTownship();

        return view('admin.driver.ferrystudent.index',[
            'township'     =>$township,
            'ferry_ways'   =>$ferry_ways,
            'status'       =>$this->status,
            'list_result'  => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ferry_ways    = $this->getFerryWay();
        $township = $this->userRepository->getTownship();

        return view('admin.driver.ferrystudent.create',[
            'ferry_ways'   =>$ferry_ways,
            'status'       =>$this->status,
            'township'     =>$township
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
            return redirect()->back()->with('danger','Registration ID not found!');
        }
             
        DB::beginTransaction();
        try{
            $insertData = array(
                'registration_no'   =>$request->registration_no,
                'student_id'        =>$request->student_id,
                'phone'             =>$request->phone,
                'address'           =>$request->address,
                'township'          =>$request->township,
                'status'            =>0,
                'way'               =>$request->way,
                'remark'            =>$request->remark,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=FerryStudent::insert($insertData);
                        
            if($result){            
                DB::commit();
                return redirect(url('admin/ferry_student/list'))->with('success','Ferry Student Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Ferry Student Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Ferry Student Created Fail !');
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
        $res = FerryStudent::join('student_registration','student_registration.registration_no','=','ferry_student.registration_no')
                ->join('student_info','student_info.student_id','=','student_registration.student_id')
                ->where('ferry_student.id',$id)
                ->select('ferry_student.*','student_info.name as student_name',
                'student_registration.new_class_id')
                ->get();

        $ferry_ways    = $this->getFerryWay();
        $township = $this->userRepository->getTownship();
            
        return view('admin.driver.ferrystudent.update',[
            'result'     =>$res,
            'township'   => $township,
            'ferry_ways' => $ferry_ways,
            'status'     =>$this->status
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
            'registration_no'            =>'required',
        ]); 
        $studentRegSearch = StudentRegistration::where('registration_no',$request->registration_no)->first();
        if (empty($studentRegSearch)) {
            return redirect()->back()->with('danger','Registration ID not found!');
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'registration_no'   =>$request->registration_no,
                'student_id'        =>$request->student_id,
                'phone'             =>$request->phone,
                'address'           =>$request->address,
                'township'          =>$request->township,
                'status'            =>0,
                'way'               =>$request->way,
                'remark'            =>$request->remark,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            $result=FerryStudent::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/ferry_student/list'))->with('success','Ferry Student Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Ferry Student Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Ferry Student Updared Fail !');
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
            $checkData = FerryStudent::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = FerryStudent::where('id',$id)->delete();
            }else{
                return redirect()->back()->with('danger','There is no result with this ferry student.');
            }
            DB::commit();
            //To return list
            return redirect(url('admin/ferry_student/list'))->with('success','Ferry Student Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Ferry Student Deleted Failed!');
        }
    }

    public function studentRegistrationSearch(Request $request)
    {
        
        $studentRegSearch = StudentRegistration::join('student_info','student_info.student_id','=','student_registration.student_id')
                        ->where('student_registration.registration_no',$request->registration_no)
                        ->select('student_registration.*','student_info.name as student_name',
                        'student_info.address_1 as address','student_info.township as township')
                        ->first();

        if (!empty($studentRegSearch)) {
            return response()->json(array(
                'msg'             => 'found',
                'student_id'      => $studentRegSearch->student_id,
                'student_name'    => $studentRegSearch->student_name,
                'address'         => $studentRegSearch->address,
                'township'        => $studentRegSearch->township,
            ), 200);
        } else {
            return response()->json(array(
                'msg'             => 'notfound',
            ), 200);
        }
    }

    public function getFerryWay() {
        return [
            '1' => 'One Way PickUp',
            '2' => 'One Way Back',
            '3' => 'Two Way'
        ];
    }
}
