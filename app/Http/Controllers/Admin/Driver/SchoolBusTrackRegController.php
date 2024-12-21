<?php

namespace App\Http\Controllers\Admin\Driver;

use App\Http\Controllers\Controller;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\DriverInfo;
use App\Models\SchoolBusTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolBusTrackRegController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(CreateInfoRepositoryInterface $createInfoRepository,UserRepositoryInterface $userRepository) 
    {
        $this->createInfoRepository = $createInfoRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function schoolBusTracktList(Request $request)
    {
        $res = SchoolBusTrack::select('school_bus_track.*');
        if ($request['action'] == 'search') {
            if (request()->has('schoolbustrack_trackno') && request()->input('schoolbustrack_trackno') != '') {
                $res->where('school_bus_track.track_no',request()->input('schoolbustrack_trackno'));
            }
            if (request()->has('schoolbustrack_driverid') && request()->input('schoolbustrack_driverid') != '') {
                $res->where('school_bus_track.driver_id', request()->input('schoolbustrack_driverid'));
            }
        }else {
            request()->merge([
                'schoolbustrack_trackno'   => null,
                'schoolbustrack_driverid'  => null
            ]);
        }  
        
        $res=$res->paginate(20);
        return view('admin.driver.schoolbustrack.index',['list_result' => $res]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $township = $this->userRepository->getTownship();
        return view('admin.driver.schoolbustrack.create',['township'=>$township]);
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
            //'track_no'            =>'required',
            'arrive_student_no'   =>'integer'
        ]); 
        $driverSearch = DriverInfo::where('driver_id',$request->driver_id)->first();
        if (empty($driverSearch)) {
            return redirect()->back()->with('danger','Driver Data not found!');
        }

        $trackNo = $this->createInfoRepository->generateTrackNumber();
       
        DB::beginTransaction();
        try{
            $insertData = array(
                'track_no'          =>$trackNo,
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
                return redirect(url('admin/school_bus_track/list'))->with('success','School Bus Track Created Successfully!');
            }else{
                return redirect()->back()->with('danger','School Bus Track Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','School Bus Track Created Fail !');
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
        return view('admin.driver.schoolbustrack.update',[
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
                return redirect(url('admin/school_bus_track/list'))->with('success','School Bus Track Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','School Bus Track Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','School Bus Track Updared Fail !');
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
                try {
                    // Attempt to delete the record
                    $res = SchoolBusTrack::where('id',$id)->forceDelete();
                   
                    if($res){
                        DB::commit();
                        //To return list
                        return redirect(url('admin/school_bus_track/list'))->with('success','School Bus Track Deleted Successfully!');
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    // Check if the exception is due to a foreign key constraint violation
                    if ($e->errorInfo[1] === 1451) {
                        return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                    }
                    return redirect()->back()->with('danger','An error occurred while deleting the record.');
                }
                
            }else{
                return redirect()->back()->with('danger','There is no result with this school bus track.');
            }            

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','School Bus Track Deleted Failed!');
        }
    }
    
}
