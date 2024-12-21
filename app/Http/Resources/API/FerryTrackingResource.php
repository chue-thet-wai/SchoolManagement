<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FerryTrackingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       
        $response_data = [];

        $license_file_url = '';
        $filename = '';
        $file_extension = '';
        if ($this->type_of_license != null || $this->type_of_license != '') {
            $license_file_url = asset('assets/driver_licenses/' . $this->type_of_license);
            // Get the filename
            $filename = pathinfo($license_file_url, PATHINFO_FILENAME);

            // Get the file extension
            $file_extension = pathinfo($license_file_url, PATHINFO_EXTENSION);
        }
    
        $driver_info['driver_id']         = $this->driver_id;
        $driver_info['name']              = $this->name;
        $driver_info['license']           = $this->license_number;
        $driver_info['license_file']      = $license_file_url;
        $driver_info['filename']          = $filename;
        $driver_info['file_extension']    = $file_extension;
        $driver_info['car_no']            = $this->car_no;
        $driver_info['phone']             = $this->phone;

        $response_data['driver_information'] = $driver_info;

        //to select ferry status
        $nowDate  = date('Y-m-d', time());
        $dayOfWeek = date('N', strtotime($nowDate));
        $track_no   = $this->track_no;
        $student_id = $this->student_id;

        $ferry_status = DB::table('driver_routes_detail')
            ->leftJoin('driver_routes', 'driver_routes.id', 'driver_routes_detail.driver_route_id')
            ->where('driver_routes.track_no', $track_no)
            ->where('driver_routes_detail.student_id', $student_id)
            ->where('day', $dayOfWeek)
            ->whereDate('driver_routes_detail.created_at', $nowDate)
            ->select('driver_routes_detail.status','driver_routes.type')
            ->latest('driver_routes_detail.created_at')
            ->first();

        $status = [];
        if ($ferry_status) {
            $status[] = $ferry_status->type;
            $status[] = $ferry_status->status;
        } else {
            $status[] = "0"; // route start
        }

        $routeStatus = array(
            "0" => "Route Started",
            "1" => "Pick Up",
            "2" => "Drop Off",
            "3" => "Arrived",
            "4" => "Complete",
            "5" => "Cancel"
        );

        $response_data['status'] = $status;
        $response_data['route_status'] = $routeStatus;
       
        return $response_data;
    }
}
