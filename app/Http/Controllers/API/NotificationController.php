<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ModifyNotificationRequest;
use Illuminate\Http\Request;
use App\Http\Requests\API\SaveDeviceRequest;
use Kreait\Firebase\Contract\Messaging;
use App\Http\Resources\API\ListNotificationResource;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{

    public function saveDevice(SaveDeviceRequest $request) {
        $guardianId = Auth::id();
        
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try{
            $deviceData = array(
                'guardian_id'         =>$guardianId,
                'device_id'           =>$request->device_id,
                'device_token'        =>$request->device_token,
                'device_os_type'      =>$request->device_os_type,
                'last_activated_at'   =>$nowDate
            ); 

            $device = DB::table('guardian_device')
                    ->updateOrInsert(
                        ['guardian_id'    => $guardianId],
                        $deviceData
                    );
                                 
            if($device){ 
                
                DB::commit(); 
                return response()->json([
                    'status'  => 200,
                    'message' => 'Successfully Saved'
                ]);            
            }else{
                return response()->json([
                    'status'  => 422,
                    'message' => 'Saved Fail'
                ],422); 
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }    
    }

    public function listNotification(Request $request) {
        try {
            $guardianId = Auth::id();
            
            $result = Notification::where('receiver_id', $guardianId)
                        ->whereNotNull('sent_at')->get();

                
            if (count($result) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new ListNotificationResource($result),
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found !',
                    'data'    => []
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

    public function readNotification(ModifyNotificationRequest $request) {
        $guardianId = Auth::id();
        
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try{
            $updateNoti = Notification::where('id', $request->notification_id)->update(['read_at' => $nowDate]);
                                 
            if($updateNoti){ 
                
                DB::commit(); 
                return response()->json([
                    'status'  => 200,
                    'message' => 'Success'
                ]);            
            }else{
                return response()->json([
                    'status'  => 422,
                    'message' => 'Fail'
                ],422); 
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }    
    }

    public function unreadNotification(ModifyNotificationRequest $request) {
        $guardianId = Auth::id();
        
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try{
            $updateNoti = Notification::where('id', $request->notification_id)->update(['read_at' => null]);
                                 
            if($updateNoti){ 
                
                DB::commit(); 
                return response()->json([
                    'status'  => 200,
                    'message' => 'Success'
                ]);            
            }else{
                return response()->json([
                    'status'  => 422,
                    'message' => 'Fail'
                ],422); 
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }    
    }

    public function destroyNotification(ModifyNotificationRequest $request) {
        DB::beginTransaction();
        try{
            $updateNoti = Notification::where('id', $request->notification_id)->delete();
                                 
            if($updateNoti){ 
                
                DB::commit(); 
                return response()->json([
                    'status'  => 200,
                    'message' => 'Success'
                ]);            
            }else{
                return response()->json([
                    'status'  => 422,
                    'message' => 'Fail'
                ],422); 
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }    
    }
}
