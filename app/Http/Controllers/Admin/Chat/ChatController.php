<?php

namespace App\Http\Controllers\Admin\Chat;

use App\Events\ChatMessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\CreateInfoRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\ChatMessage;
use App\Models\StudentGuardian;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{

    public function __construct() 
    {
        
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ChatList(Request $request)
    {  
        $login_id = Auth::user()->user_id;

        $lastReadAt = DB::table('chat')
                ->where('user_id', $login_id)
                ->value('last_read_at');

        $guardianList = StudentGuardian::get();
        $guardianDatas = [];

        foreach ($guardianList as $guardian) {
            $hasUnread = $this->hasUnreadMessages($guardian->id, $lastReadAt);

            $guardianData = [
                'id' => $guardian->id,
                'name' => $guardian->name,
                'photo' => $guardian->photo,
                'has_unread' => $hasUnread
            ];

            $guardianDatas[] = $guardianData;
        }

        return view('admin.chat.index', [
            'guardian_list' => $guardianDatas
        ]);
    }

    public function show($guardianId)
    {
        $guardian_data = StudentGuardian::where('id', $guardianId)->first();
        
        // Define the file path based on sender and receiver IDs
        $filePath = public_path("assets/chat_files/client-chat-room-" . $guardianId . ".txt");

        $messages = [];
        if (file_exists($filePath)) {
            // Read the file line by line
            $file = fopen($filePath, "r");
            if ($file) {
                while (($line = fgets($file)) !== false) {
                    // Decode each line as JSON and add to messages array
                    $message = json_decode($line, true);
                    if ($message !== null) {
                        $messages[] = $message;
                    }
                }
                fclose($file);
            }
        }
        return view('admin.chat.chatbox', [
            'guardian_data' => $guardian_data,
            'messages'      => $messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $guardianId = $request->guardian_id;
        $login_id = Auth::user()->user_id;

        DB::beginTransaction();
        try {
            $filePath = public_path("assets/chat_files/client-chat-room-" . $guardianId . ".txt");

            $data = [
                'sender_id'   => $login_id,
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
                'status'  => 500,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    public function saveLastReadAt(Request $request)
    {
        $login_id = Auth::user()->user_id;
        $nowDate  = date('Y-m-d H:i:s', time());

        DB::beginTransaction();
        try {
           
            // Check if the record exists
            $existingRecord = DB::table('chat')->where('user_id', $login_id)->first();

            if ($existingRecord) {
                // Update the record and set the updated_at timestamp
                $res = DB::table('chat')
                    ->where('user_id', $login_id)
                    ->update(['last_read_at' => $nowDate, 'updated_at' => $nowDate]);
            } else {
                // Insert a new record without the updated_at timestamp
                $res = DB::table('chat')->insert([
                    'user_id'     => $login_id,
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
                    'status'  => 500,
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);  
            }
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            return response()->json([
                'status'  => 500,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    private function hasUnreadMessages($guardianId, $lastReadAt)
    {
        $filePath = public_path("assets/chat_files/client-chat-room-" . $guardianId . ".txt");
        $lastCreatedAt = null; 

        if (file_exists($filePath)) {
            $file = fopen($filePath, "r");
            if ($file) {
                while (($line = fgets($file)) !== false) {
                    $message = json_decode($line, true);
                    if ($message !== null && $message['created_at'] > $lastCreatedAt) {
                        $lastCreatedAt = $message['created_at'];
                    }
                }
                fclose($file);
            }
        }

        return $lastCreatedAt != null && $lastCreatedAt > $lastReadAt;
    }
}
