<?php

namespace App\Repositories;

use App\Interfaces\CreateInfoRepositoryInterface;
use App\Models\DriverInfo;
use App\Models\ClassSetup;
use App\Models\TeacherInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateInfoRepository implements CreateInfoRepositoryInterface 
{
    public function generateDriverID() 
    {
        $maxDriverID = DB::table('driver_info')->select('driver_id')->orderBy('driver_id', 'desc')->first();
        if (!empty($maxDriverID)) {
            $lastNum  = substr($maxDriverID->driver_id,2);
            $currentDriverID = 'D-'.($lastNum+1);
            return $currentDriverID;
        }
        return 'D-10001';

    }

    public function getClassSetup(){
        return ClassSetup::all();
    }
    public function getTeacherList(){
        return TeacherInfo::all();
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