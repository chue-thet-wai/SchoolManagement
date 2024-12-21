<?php

namespace App\Http\Controllers\API;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\GetChatMessageRequest;
use App\Http\Requests\API\SendChatMessageRequest;
use App\Http\Resources\API\ChatListResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class ChatController extends Controller
{
    public function sendMessage(SendChatMessageRequest $request)
    {
        $guardianId = Auth::id();

        DB::beginTransaction();
        try {
            $filePath = public_path("assets/chat_files/client-chat-room-" . $guardianId . ".txt");

            $data = [
                'sender_id'   => $guardianId,
                'guardian_id' => $guardianId,
                'message'     => $request->message,
                'created_at'  => now()->toDateTimeString(),
            ];

            // Append the message to the file
            file_put_contents($filePath, json_encode($data) . PHP_EOL, FILE_APPEND);

            // Broadcast the message to the WebSocket channel
            broadcast(new ChatMessageSent($data));

            return response()->json([
                'status'  => 200,
                'message' => 'Successfully Message Sent'
            ]);            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    /*public function getChatList(Request $request) {
        try {
            $guardianId = Auth::id();

            $currentDate = date("Y-m-d");
            //current academic year
            $currentAcademic = AcademicYear::where('start_date', '<=', $currentDate)
                            ->where('end_date', '>=', $currentDate)
                            ->first();
            $academicId = $currentAcademic ? $currentAcademic->id : null;

            //current academic year class
            $currentClassIds = ClassSetup::where('academic_year_id', $academicId)
                            ->pluck('id')
                            ->toArray();

            //To get student class id
            $studentClassIds  = DB::table('student_registration')
                            ->leftjoin('student_info','student_registration.student_id','student_info.student_id')
                            ->leftjoin('class_setup','class_setup.id','student_registration.new_class_id')
                            ->leftjoin('grade','grade.id','class_setup.grade_id')
                            ->where('guardian_id',$guardianId)
                            ->whereIn('new_class_id',$currentClassIds)
                            ->whereNull('student_registration.deleted_at')
                            ->pluck('new_class_id')
                            ->toArray();

            $teacherClass = TeacherClass::join('teacher_info','teacher_info.user_id','teacher_class.teacher_id')
                            ->join('class_setup','class_setup.id','teacher_class.class_id')
                            ->whereIn('class_id',$studentClassIds)
                            ->select('teacher_class.*','teacher_info.name as teacher_name','class_setup.name as class_name')
                            ->get();

        
            if (count($teacherClass) > 0) {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => new ChatListResource($teacherClass),
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
                'status'  => 500,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }*/

    public function getMessages(Request $request) {
        try {
            $guardianId = Auth::id();

            // Define the file path based on sender and receiver IDs
            $filePath = public_path("assets/chat_files/client-chat-room-".$guardianId.".txt");

            $lastReadAt = DB::table('chat')
                        ->where('guardian_id', $guardianId)
                        ->value('last_read_at');
            
            $hasUnread = false;
            $messages = [];
            if (file_exists($filePath)) {
                // Read the file line by line
                $file = fopen($filePath, "r");
                if ($file) {
                    while (($line = fgets($file)) !== false) {
                        // Decode each line as JSON and add to messages array
                        $message = json_decode($line, true);
                        if ($message !== null) {
                            if ($hasUnread == false && $message['created_at'] > $lastReadAt) {
                                $hasUnread = true;
                            }
                            $messages[] = $message;
                        }
                    }
                    fclose($file);
                }
                $response['has_unread'] = $hasUnread;
                $response['messages']   = $messages;
                // Process the messages as needed    
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data found!',
                    'data'    => $response,
                ]);
            } else {
                return response()->json([
                    'status'  => 200,
                    'message' => 'Data not found!',
                    'data'    => null
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

    /*public function getMessages(Request $request) {
        $channelName = 'private-private-chat.2';
        $limit = 100; // Limit the number of messages to retrieve
    
        $accessToken = Cache::remember('pusher_access_token', 3600, function () use ($channelName) {
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true
                ]
            );
            $socketId = '666152.302887'; // Replace 'YOUR_SOCKET_ID' with the actual socket ID
            $authResponse = $pusher->socket_auth($channelName, $socketId);
            $auth = $authResponse['auth']; // Access 'auth' directly
            return $auth;
        });
    
        try {
            $client = new Client();
            $response = $client->request('GET', 'http://api.pusherapp.com/apps/1760878/channels/' . $channelName . '/messages', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'query' => [
                    'limit' => $limit,
                ],
            ]);
    
            $messages = json_decode($response->getBody(), true);
            return response()->json($messages);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }*/

    public function saveLastReadAt(Request $request)
    {
        $guardianId = Auth::id();
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try {
            // Check if the record exists
            $existingRecord = DB::table('chat')->where('guardian_id', $guardianId)->first();

            if ($existingRecord) {
                // Update the record and set the updated_at timestamp
                $res = DB::table('chat')
                    ->where('guardian_id', $guardianId)
                    ->update(['last_read_at' => $nowDate, 'updated_at' => $nowDate]);
            } else {
                // Insert a new record without the updated_at timestamp
                $res = DB::table('chat')->insert([
                    'guardian_id'  => $guardianId,
                    'last_read_at' => $nowDate,
                    'created_at'   => $nowDate,
                    'updated_at'   => $nowDate
                ]);
            }
                
            if ($res) {
                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'Last read at time saved'
                ]);   
            } else {
                DB::rollback();
                return response()->json([
                    'status'  => '500',
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);  
            }
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status'  => '500',
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
    
}
