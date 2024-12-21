<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use App\Models\DriverAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DriverInfo;
use App\Models\DriverRoutesDetail;
use App\Models\DriverRoutes;
use App\Models\FerryStudent;
use App\Models\SchoolBusTrack;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private $status;

    public function __construct(UserRepositoryInterface $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function driverHome(){
        return view('driver.driver_home');
    }
    
    public function driverProfile() {
        $id = session()->get('driver_id');      

        //To get driver data
        $driver_data  = DriverInfo::leftjoin('school_bus_track','driver_info.driver_id','school_bus_track.driver_id')
                        ->where('driver_info.driver_id',$id)
                        ->select('driver_info.*','school_bus_track.car_no as car_no')
                        ->latest('school_bus_track.created_at')
                        ->first();

        return view('driver.driver_profile',[
            'driver_data'      =>$driver_data
        ]);       
    }

    public function driverSetting() {
        return view('driver.driver_setting');        
    }

    public function driverSettingSubmit(Request $request) {
        $new_password   = $request->new_password;
        $driverId       = session()->get('driver_id');
       
        $request->validate([
            'current_password' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $driverId       = session()->get('driver_id');
                    $driverInfo     = DriverInfo::where('driver_id',$driverId)->first();

                    if (!$driverInfo || !Hash::check($value, $driverInfo->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password'     => ['required', 'string', 'max:255', 'different:current_password'],
            'confirm_password' => ['required', 'string', 'max:255', 'same:new_password'],
        ]);


        DB::beginTransaction();
        try{
            $updateData = array(
                'password'        =>bcrypt($new_password),
                'updated_by'      =>$driverId,
                'updated_at'      =>now()
            );           
            $result=DriverInfo::where('driver_id',$driverId)->update($updateData);                      
            if($result){ 
                DB::commit(); 
                return redirect(url('driver/setting'))->with('success','Success Password Change!');              
            }else{
                return redirect()->back()->with('danger','Fail Password Change!');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Fail Password Change!');
        }     
    }

    public function driverSchedule(Request $request) {
        $driverId       = session()->get('driver_id');   
        $nowDate  = date('Y-m-d', time());
        $dayOfWeek = date('N', strtotime($nowDate));
        $driver_routes = DriverRoutes::leftjoin('school_bus_track','school_bus_track.track_no','driver_routes.track_no')
                            ->where('driver_id',$driverId)
                            ->where('day',$dayOfWeek)
                           // ->orderBy('driver_routes.created_at', 'desc')
                            ->select('driver_routes.*')
                            ->get();
        $student_number = 0;
        $student_number = FerryStudent::leftJoin('school_bus_track_detail', 'school_bus_track_detail.ferry_student_id', 'ferry_student.id')
                            ->leftJoin('school_bus_track', 'school_bus_track.id', 'school_bus_track_detail.school_bus_track_id')
                            ->where('school_bus_track.driver_id', $driverId)
                            ->count('ferry_student.student_id');
                           
        $route_types = $this->get_route_types();
        return view('driver.driver_schedule',[
            'driver_routes' => $driver_routes,
            'route_types'   => $route_types,
            'student_number'=> $student_number
        ]);
    }

    public function driverRoute(Request $request,$routeId) {
        $driverId       = session()->get('driver_id'); 
        $ferry_student = FerryStudent::leftJoin('school_bus_track_detail', 'school_bus_track_detail.ferry_student_id', 'ferry_student.id')
                            ->leftJoin('school_bus_track', 'school_bus_track.id', 'school_bus_track_detail.school_bus_track_id')
                            ->leftJoin('student_info','student_info.student_id','ferry_student.student_id')
                            ->where('school_bus_track.driver_id', $driverId)
                            ->select('student_info.name as name','ferry_student.*')
                            ->get();
                            
        $routeType = DriverRoutes::where('id',$routeId)->value('type');
        if ($routeType == 1) {
            $routeStatus = array(
                "1" => "Pick Up",
                "3" => "Arrived",
                "4" => "Complete",
                "5" => "Cancel"
            );
        } else {
            $routeStatus = array(
                "2" => "Drop Off",
                "3" => "Arrived",
                "4" => "Complete",
                "5" => "Cancel"
            );
        }
        
        $township      = $this->userRepository->getTownship();

        //select today route
        $driver_route_detail = DriverRoutesDetail::where('driver_route_id',$routeId)->get();
        $todayRoute = [];
        if (count($driver_route_detail)>0) {
            foreach ($driver_route_detail as $detail) {
                $todayRoute[$detail->student_id] = $detail->status;
            }
        }
        

        return view('driver.driver_route',[
            "ferry_student"=>$ferry_student,
            "route_status"=> $routeStatus,
            "township"    => $township,
            "route_id"    => $routeId,
            "today_routes" => $todayRoute
        ]);
    }

    public function updateRouteStatus(Request $request)
    {
        $driverId       = session()->get('driver_id'); 

        $studentId = $request->input('student_id');
        $routeId = $request->input('route_id');
        $newStatus = $request->input('new_status');

        $route_date = date('Y-m-d',time());
        $nowDate    = date('Y-m-d H:i:s',time());

        $data = [
            'driver_route_id' => $routeId,
            'student_id' => $studentId,
            'status' => $newStatus,
            'date' => $route_date,
        ];

        if ($newStatus === '5') { //cancel
            $data['cancel_note'] = $request->input('cancel_note'); // You should replace this with the actual reason
        }

        // Use updateOrCreate to insert or update the record
        $checkDriverRouteDetail = DriverRoutesDetail::where(['driver_route_id' => $routeId, 'student_id' => $studentId])->first();
        if ($checkDriverRouteDetail) {
            $data['updated_by'] = $driverId;
            $data['updated_at'] = $nowDate;
            $driverRouteDetail = DriverRoutesDetail::where(['driver_route_id' => $routeId, 'student_id' => $studentId])->update($data);
        } else {
            $data['created_by'] = $driverId;
            $data['updated_by'] = $driverId;
            $data['created_at'] = $nowDate;
            $data['updated_at'] = $nowDate;
            $driverRouteDetail = DriverRoutesDetail::insert($data);
        }

        if ($driverRouteDetail) {
            return response()->json(['message' => 'Status updated successfully.']);
        }

        return response()->json(['message' => 'Failed to update status.'], 500);
    }

    public function driverAttendance(Request $request) { 
        $driverId       = session()->get('driver_id'); 

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;   

        $driver_attendance = DriverAttendance::where('driver_id', $driverId)
                                ->whereMonth('attendance_date', $currentMonth)
                                ->whereYear('attendance_date', $currentYear)
                                ->orderBy('attendance_date', 'desc')
                                ->select('driver_attendance.*', 'driver_attendance.attendance_date as date')
                                ->get();
        if ($request->ajax()) {
            return response()->json($driver_attendance);
        }
        $nowDate = date('Y-m-d',time());
        $todayCheckIn = DriverAttendance::where('driver_id', $driverId)
                            ->whereDate('attendance_date', $nowDate)
                            ->first();
        $today_checkout = false;
        $today_checkin  = false;
        if (!empty($todayCheckIn)) {
            if ($todayCheckIn->check_in != null) {
                $today_checkin = true;
            }
            if ($todayCheckIn->check_out != null) {
                $today_checkout = true;
            }
        }
        return view('driver.driver_attendance',[
            'driver_attendance' => $driver_attendance,
            'today_checkin'     => $today_checkin,
            'today_checkout'    => $today_checkout
        ]);
    }

    public function checkIn(Request $request)
    {
        // Save the check-in time in the database
        $driverId = session()->get('driver_id');
        // Assuming you have a DriverAttendance model
        DriverAttendance::create([
            'driver_id'       => $driverId,
            'attendance_date' => $request->date,
            'check_in'        => date('H:m:s',time()),
            'status'          => 'present' 
        ]);

        return response()->json(['message' => 'Checked in successfully']);
    }

    public function checkOut(Request $request)
    {
        // Update the check-out time in the database
        $driverId = session()->get('driver_id');
        // Assuming you have a DriverAttendance model
        $attendance = DriverAttendance::where('driver_id', $driverId)
            ->where('attendance_date', $request->date)
            ->first();

        if ($attendance) {
            $attendance->update([
                'check_out' => date('H:m:s',time()),
            ]);
            return response()->json(['message' => 'Checked out successfully']);
        }

        return response()->json(['message' => 'Attendance not found'], 404);
    }

    public function get_route_types(){
        return array(
            "1" => "Pick Up",
            "2" => "Drop Off"
        );
    }
    
}
