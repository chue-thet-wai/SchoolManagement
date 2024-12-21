<?php

namespace App\Http\Controllers\Parent;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Message;

class MessageController extends Controller
{
    public function parentMessages() {
        $messages = Message::whereNull('class_id')->get();
        $message_arr = [];
        foreach ($messages as $m) {
            $carbonDate = Carbon::parse($m->created_at);
            $day = $carbonDate->format('d');
            $monthAbbreviation = $carbonDate->format('M');

            $one_message = [];
            $one_message['title']       = $m->title;
            $one_message['description'] = $m->description;
            $one_message['remark']      = $m->remark;
            $one_message['date']        = $day.' '.$monthAbbreviation;
            $message_arr[] = $one_message;
        }
        return view('parent.parent_messages',[
            'messages'       =>$message_arr
        ]);        
    }
}
