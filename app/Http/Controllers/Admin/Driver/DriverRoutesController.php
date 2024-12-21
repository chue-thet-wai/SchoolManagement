<?php

namespace App\Http\Controllers\Admin\Driver;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use App\Models\DriverRoutes;
use App\Models\SchoolBusTrack;
use App\Repositories\CreateInfoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DriverRoutesController extends Controller
{
    private CreateInfoRepository $createInfoRepo;

    public function __construct(CreateInfoRepository $createInfoRepo) 
    {
        $this->createInfoRepo = $createInfoRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function DriverRoutesList(Request $request)
    {
        $res = DriverRoutes::select('driver_routes.*');
        if ($request['action'] == 'search') {
            if (request()->has('driverroutes_trackno') && request()->input('driverroutes_trackno') != '') {
                $res->where('driver_routes.track_no', request()->input('driverroutes_trackno'));
            }
        }else {
            request()->merge([
                'driverroutes_trackno'   => null,
            ]);
        } 
        
        $route_types = $this->get_route_types();

        $weekdays = $this->createInfoRepo->getWeekDays();
        
        $res=$res->paginate(20);
        return view('admin.driver.driverroutes.index',[
            'list_result' => $res,
            'route_types' => $route_types,
            'weekdays'    => $weekdays
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $route_types = $this->get_route_types();
        $track_number_list = $this->getTrackNumberList();
        $weekdays = $this->createInfoRepo->getWeekDays();

        return view('admin.driver.driverroutes.create',[
            'route_types'       => $route_types,
            'track_number_list' => $track_number_list,
            'weekdays'          => $weekdays
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
            'track_no'            =>'required',
            'day'                 =>'required',
            'start_time'          =>'required',
            'end_time'            =>'required',
            'type'                => 'required'
        ]); 
        $trackSearch = SchoolBusTrack::where('track_no',$request->track_no)->first();
        if (empty($trackSearch)) {
            return redirect()->back()->with('danger','School Bus Track Data not found!');
        }
       
        DB::beginTransaction();
        try{
            $insertData = array(
                'track_no'          =>$request->track_no,
                'day'               =>$request->day,
                'start_time'        =>$request->start_time,
                'end_time'          =>$request->end_time,
                'type'              =>$request->type,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$nowDate,
                'updated_at'        =>$nowDate
            );
            $result=DriverRoutes::insert($insertData);
                        
            if($result){            
                DB::commit();
                return redirect(url('admin/driver_routes/list'))->with('success','Driver Routes Created Successfully!');
            }else{
                return redirect()->back()->with('danger','Driver Routes Created Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Driver Rotues Created Fail !');
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
        $res = DriverRoutes::select('driver_routes.*')->get();
        $route_types = $this->get_route_types();
        $track_number_list = $this->getTrackNumberList();
        $weekdays = $this->createInfoRepo->getWeekDays();
        
        return view('admin.driver.driverroutes.update',[
            'result'            => $res,
            'route_types'       => $route_types,
            'track_number_list' => $track_number_list,
            'weekdays'          => $weekdays
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
            'day'                 =>'required',
            'start_time'          =>'required',
            'end_time'            =>'required',
            'type'                => 'required'
        ]); 
        $trackSearch = SchoolBusTrack::where('track_no',$request->track_no)->first();
        if (empty($trackSearch)) {
            return redirect()->back()->with('danger','School Bus Track Data not found!');
        }

        DB::beginTransaction();
        try{
            $updateData = array(
                'track_no'          =>$request->track_no,
                'day'               =>$request->day,
                'start_time'        =>$request->start_time,
                'end_time'          =>$request->end_time,
                'type'              =>$request->type,
                'updated_by'        =>$login_id,
                'updated_at'        =>$nowDate
            );
            
            $result=DriverRoutes::where('id',$id)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect(url('admin/driver_routes/list'))->with('success','Driver Route Updated Successfully!');
            }else{
                return redirect()->back()->with('danger','Driver Route Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Driver Route Updared Fail !');
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
            $checkData = DriverRoutes::where('id',$id)->first();

            if (!empty($checkData)) {
                try {
                    // Attempt to delete the record
                    $res = DriverRoutes::where('id',$id)->forceDelete();
                   
                    if($res){
                        DB::commit();
                        //To return list
                        return redirect(url('admin/driver_routes/list'))->with('success','Driver Route Deleted Successfully!');
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    // Check if the exception is due to a foreign key constraint violation
                    if ($e->errorInfo[1] === 1451) {
                        return redirect()->back()->with('danger','Cannot delete this record because it is being used in other.');
                    }
                    return redirect()->back()->with('danger','An error occurred while deleting the record.');
                }
                
            }else{
                return redirect()->back()->with('danger','There is no result with this driver route.');
            }            

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Driver Route Deleted Failed!');
        }
    }

    public function get_route_types(){
        return array(
            "1" => "Pick Up",
            "2" => "Drop Off"
        );
    }

    public function getTrackNumberList(){
        $schoolBusTrackData = SchoolBusTrack::whereNull('deleted_at')->get()->toArray();
        $trackNumbers       = array_column($schoolBusTrackData,'track_no');
        return $trackNumbers;
    }

    public function getWeekDays(){
        $weekdays =array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        $weekdays_list = [];
        foreach($weekdays as $key=>$value){
            $weekdays_list [$key+1] = $value;
        }
        return $weekdays_list;
    }
    
}
