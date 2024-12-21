<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Event;

class CalenderController extends Controller
{
    public function parentAnnoucement() {
        $currentDate = date("Y-m-d");

        //current academic year
        $todayEvents   = Event::whereNull('grade_id')
                            ->where('event_from_date',"<=",$currentDate)
                            ->where('event_to_date',">=",$currentDate)
                            ->get()->toArray();
        $todayEventIds = array_column($todayEvents, 'id');
        $earlyEvents   = Event::whereNull('grade_id')
                            ->whereNotIn('id',$todayEventIds)
                            ->get()->toArray();
        return view('parent.parent_annoucement',[
            'today_events'       =>$todayEvents,
            'early_events'       =>$earlyEvents
        ]);
    }
}
