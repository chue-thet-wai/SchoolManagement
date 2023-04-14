<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\ExportFerry;
use App\Http\Controllers\Controller;
use App\Models\SchoolBusTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\UserRepositoryInterface;

class FerryReportController extends Controller
{

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function ferryReport(Request $request) {
        $township = $this->userRepository->getTownship();

        $res = SchoolBusTrack::leftJoin('driver_info','driver_info.driver_id','=','school_bus_track.driver_id');
        if ($request['action'] == 'search') {
            if (request()->has('ferry_trackno') && request()->input('ferry_trackno') != '') {
                $res->where('track_no', request()->input('ferry_trackno'));
            }
            if (request()->has('ferry_driverId') && request()->input('ferry_driverId') != '') {
                $res->where('school_bus_track.driver_id', request()->input('ferry_driverId'));
            }
            if (request()->has('ferry_driverName') && request()->input('ferry_driverName') != '') {
                $res->where('name', 'Like', '%' . request()->input('ferry_driverName') . '%');
            }
        } else if ($request['action'] == 'export') {

            if (request()->has('ferry_trackno') && request()->input('ferry_trackno') != '') {
                $res->where('track_no', request()->input('ferry_trackno'));
            }
            if (request()->has('ferry_driverId') && request()->input('ferry_driverId') != '') {
                $res->where('school_bus_track.driver_id', request()->input('ferry_driverId'));
            }
            if (request()->has('ferry_driverName') && request()->input('ferry_driverName') != '') {
                $res->where('name', 'Like', '%' . request()->input('ferry_driverName') . '%');
            }

            $res->select('school_bus_track.*','driver_info.name','driver_info.phone');
            $ferryRes = $res->get();

            $ferryData = [];
            foreach ($ferryRes as $res) {
                $resArr['track_no']             = $res->track_no;
                $resArr['driver_id']            = $res->driver_id;
                $resArr['name']                 = $res->name;
                $resArr['phone']                = $res->phone;
                $resArr['car_type']             = $res->car_type;
                $resArr['car_no']               = $res->car_no;
                $resArr['school_from_time']     = $res->school_from_time;
                $resArr['school_to_time']       = $res->school_to_time;
                $resArr['school_from_period']   = $res->school_from_period;
                $resArr['school_to_period']     = $res->school_to_period;
                $resArr['arrive_student_no']    = $res->arrive_student_no;
                $resArr['township']             = $township[$res->township];
                $resArr['two_way_amount']       = $res->two_way_amount;
                $resArr['oneway_pickup_amount'] = $res->oneway_pickup_amount;
                $resArr['oneway_back_amount']   = $res->oneway_back_amount;
                
                $ferryData[] = $resArr;
            }
    
            return Excel::download(new ExportFerry($ferryData), 'ferry_export.csv');
        } else {
            request()->merge([
                'ferry_trackno' => null,
                'ferry_driverId' => null,
                'ferry_driverName'=>null,
            ]);
        }       
       
        $res = $res->paginate(5);
        return view('admin.report.ferryreport',['list_result' => $res]);
    }

}
