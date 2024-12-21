<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Service;
use App\Models\UserDevice;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;

class NotificationService
{
    private $messaging;

    private $nowDate;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function sendNotification($data)
    {
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try {
            // Save notification to the database
            $saveNoti = Notification::create([
                'receiver_id' => $data['receiver_id'],
                'title'       => $data['title'],
                'body'        => $data['body'],
                'channel'     => 'fcm',
                'source'      => $data['source'],
                'source_id'   => $data['source_id'],
            ]);

            if ($saveNoti) {
                // If the notification is saved successfully, get its ID
                $notiID = $saveNoti->id;
                // Get device token for the receiver
                $userDevice = DB::table('guardian_device')->where('guardian_id', $data['receiver_id'])->latest()->first();
		
                $deviceToken = '';
                if ($userDevice) {
                    $deviceToken = $userDevice->device_token;
                } else {
                    return false;
                }
                
                $noti_data = [
                    'id'             => $notiID,
                    'title'          => $data['title'],
                    'body'           => $data['body'],
                    'sound'          => 'default',
                    'priority'       => 'high',
                    'content_available' => true,
                    'apns-priority'  => 5
                ];
               

                // Create a CloudMessage with the target device token and notification details
                $message = CloudMessage::withTarget('token', $deviceToken)
                                ->withNotification([
                                    'title'    => $data['title'],
                                    'body'     => $data['body'],
                                    'sound'    => 'default'
                                ])
                                ->withData($noti_data);

                try {
                    // Send the message using the messaging service
                    $msgResponse = $this->messaging->send($message);
			        Log::info('Firebase message sent. Response: ' . json_encode($msgResponse));

                    if ($msgResponse) {
                        // Update the notification's 'sent_at' timestamp
                        $updateNoti = Notification::where('id', $notiID)->update(['sent_at' => $nowDate]);
                    }
                    // Commit the database transaction
                    DB::commit();

                    return true;
                } catch (MessagingException $e) {
                    DB::commit();
                    // Handle messaging exception
                    Log::info($e->getMessage());
                    Log::info($e->errors());
                    return false;
                }
            }
        } catch (\Exception $e) {
            // Rollback the database transaction in case of any exception
            DB::rollback();
            Log::info($e->getMessage());
            return false;
        }
    }
}
