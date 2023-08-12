<?php

namespace App\Http\Controllers\Admin\Registration;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use App\Models\DriverInfo;
use App\Models\SchoolBusTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolBusTrackRegController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = SchoolBusTrack::paginate(10);
        return view('admin.registration.schoolbustrack.index',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $township = $this->userRepository->getTownship();
        return view('admin.registration.schoolbustrack.create',['township'=>$township]);
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
            'track_no'            =>'required',
            'arrive_student_no'   =>'integer'
        ]); 
        $driverSearch = DriverInfo::where('driver_id',$request->driver_id)->first();
        if (empty($driverSearch)) {
            return redirect()->back()->with('danger','Driver Data not found!');
        }
       
        DB::beginTransaction();
        try{
            $insertData = array(
                'track_no'          =>$request->track_no,
                'driver_id'         =>$request->driver_id,
                'car_type'          =>$request->car_type,
                'car_no'            =>$request->car_no,
                'school_from_time'  =>$request->school_from_time,
                'school_to_time'    =>$request->school_to_time,
                'school_from_period'=>$request->school_from_period,
                'school_to_period'  =>$request->school_to_period,
                'arrive_student_no' =>$request->arrive_student_no,
                'township'          =>$request->township,
                'two_way_amount'    =>$request->two_way_amount,
                'oneway_pickup_amount'=>$request->oneway_pickup,
                'oneway_back_amount'=>$request->oneway_back,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=SchoolBusTrack::insert($insertData);
                        
            if($result){            
                DB::commit();
                return redirect(route('school_bus_track.index'))->with('success','School Bus Track Created Successfully!');
            }else{
                return redirect()->back()->with('danger','School Bus Track Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','School Bus Track Created Fail !');
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
        $res = SchoolBusTrack::join("driver_info", "driver_info.driver_id", "=", "school_bus_track.driver_id")
                ->where('school_bus_track.id',$id)
                ->select('school_bus_track.*','driver_info.name as driver_name','driver_info.phone as driver_phone')
                ->get();
        
        $township = $this->userRepository->getTownship();
        return view('admin.registration.schoolbustrack.update',[
            'result'=>$res,
            'township' =>$township
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
            'track_no'            =>'required',
            'arrive_student_no'   =>'integer'
        ]); 
        $driverSearch = DriverInfo::where('driver_id',$request->driver_id)->first();
        if (empty($driverSearch)) {
            return redirect()->back()->with('danger','Driver Data not found!');
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'track_no'          =>$request->track_no,
                'driver_id'         =>$request->driver_id,
                'car_type'          =>$request->car_type,
                'car_no'            =>$request->car_no,
                'school_from_time'  =>$request->school_from_time,
                'school_to_time'    =>$request->school_to_time,
                'school_from_period'=>$request->school_from_period,
                'school_to_period'  =>$request->school_to_period,
                'arrive_student_no' =>$request->arrive_student_no,
                'township'          =>$request->township,
                'two_way_amount'    =>$request->two_way_amount,
                'oneway_pickup_amount'=>$request->oneway_pickup,
                'oneway_back_amount'=>$request->oneway_back,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            $result=SchoolBusTrack::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(route('school_bus_track.index'))->with('success','School Bus Track Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','School Bus Track Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','School Bus Track Updared Fail !');
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
            $checkData = SchoolBusTrack::where('id',$id)->first();

            if (!empty($checkData)) {
                
                $res = SchoolBusTrack::where('id',$id)->delete();
            }else{
                return redirect()->back()->with('error','There is no result with this school bus track.');
            }
            DB::commit();
            //To return list
            return redirect(route('school_bus_track.index'))->with('success','School Bus Track Deleted Successfully!');

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('error','School Bus Track Deleted Failed!');
        }
    }
    
}
