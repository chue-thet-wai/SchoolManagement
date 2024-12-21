<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\AnnoucementResource;
use App\Models\Event;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnnoucementController extends Controller
{
    
    public function annoucementList(Request $request)
    {
        try {
            $currentDate = date("Y-m-d");

            //current academic year
            $todayAllMsg   = Message::whereNull('class_id')
                                ->where('created_at',"=",$currentDate)
                                ->get();
            
            $todayAllMsgArray = $todayAllMsg;
            $todayAllMsgArray = $todayAllMsgArray->toArray();

            $todayAllMsgIds = array_column($todayAllMsgArray, 'id');

            $earlyAllMsg   = Message::whereNull('class_id')
                                ->whereNotIn('id',$todayAllMsgIds)
                                ->get();

                
            //if (!empty($guardian) && Hash::check($password, $guardian->password)) {
            if (count($todayAllMsg) > 0 || count($earlyAllMsg)>0) {
                $response = [];
                
                if (count($todayAllMsg) > 0) {
                    $response['today_annoucement'] = new AnnoucementResource($todayAllMsg);
                } else {
                    $response['today_annoucement'] = [];
                }
                if (count($earlyAllMsg) > 0) {
                    $response['early_annoucement'] = new AnnoucementResource($earlyAllMsg);
                } else {
                    $response['early_annoucement'] = [];
                }
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => $response,
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => null,
                ]);
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
}
