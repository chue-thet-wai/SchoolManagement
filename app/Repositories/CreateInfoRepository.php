<?php

namespace App\Repositories;

use App\Interfaces\CreateInfoRepositoryInterface;
use App\Models\DriverInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateInfoRepository implements CreateInfoRepositoryInterface 
{
    public function generateDriverID() 
    {
        $maxDriverID = DB::table('driver_info')->select('driver_id')->orderBy('driver_id', 'desc')->first();
        if (!empty($maxDriverID)) {
            $lastNum  = substr($maxDriverID->driver_id,2);
            Log::info($lastNum);
            $currentDriverID = 'D-'.($lastNum+1);
            return $currentDriverID;
        }
        return 'D-10001';

    }
}