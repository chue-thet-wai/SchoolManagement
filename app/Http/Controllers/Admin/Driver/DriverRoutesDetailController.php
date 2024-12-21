<?php

namespace App\Http\Controllers\Admin\Driver;

use App\Http\Controllers\Controller;
use App\Interfaces\CreateInfoRepositoryInterface;
use Illuminate\Http\Request;
use App\Interfaces\UserRepositoryInterface;
use App\Models\DriverRoutes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolBusTrack;
use App\Repositories\CreateInfoRepository;

class DriverRoutesDetailController extends Controller
{
    private CreateInfoRepositoryInterface $createInfoRepo;

    public function __construct(CreateInfoRepository $createInfoRepo) 
    {
        $this->createInfoRepo = $createInfoRepo;
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function DriverRoutesDetailList($routeId)
    {  
        $weekdays = $this->createInfoRepo->getWeekDays();

        $driverRoute = DriverRoutes::where('driver_routes.id',$routeId)
                            ->select('driver_routes.*')
                            ->first();
        
        $driver_routes_data = [];
        if ($driverRoute) {
            $driver_routes_data['id']            = $routeId;
            $driver_routes_data['track_no']      = $driverRoute->track_no;
            $driver_routes_data['day']           = $weekdays[$driverRoute->day];
            $driver_routes_data['start_time']    = $driverRoute->start_time;
            $driver_routes_data['end_time']      = $driverRoute->end_time;
            $driver_routes_data['type']          = $driverRoute->type;
        }
        $detail_data = DB::table('driver_routes_detail')
                        ->leftjoin('student_info','student_info.student_id','driver_routes_detail.student_id')
                        ->where('driver_route_id',$routeId)
                        ->select('driver_routes_detail.*','student_info.name as name');
        $detail_data = $detail_data->paginate(20);

        $route_status = $this->getRouteStatus();
        
        return view('admin.driver.driverroutesdetail.index',[
            'driver_routes_data'     =>$driver_routes_data,
            'detail_data'            =>$detail_data,
            'route_status'           =>$route_status
        ]);
    }

    public function getRouteStatus() {
        $routeStatus = array(
            "0" => "Route Started",
            "1" => "Pick Up",
            "2" => "Drop Off",
            "3" => "Arrived",
            "4" => "Complete",
            "5" => "Cancel"
        );
        return $routeStatus;
    }
}
