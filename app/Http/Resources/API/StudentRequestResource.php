<?php

namespace App\Http\Resources\API;

use App\Models\StudentGuardian;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class StudentRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $responsedata = [];

        foreach ($this as $alldata) {
            foreach ($alldata as $data) {
                $created_name = '';
            
                if ($data['request_by_parent'] != null) {
                    $created_name = StudentGuardian::where('id', $data['request_by_parent'])->value('name');
                } else {
                    $created_name = User::where('user_id', $data['request_by_school'])->value('name');
                }

                $date = new DateTime($data['created_at']);
                $date->add(new DateInterval('PT6H30M'));
                $formattedDate = $date->format('d M y h:i A');

                $comments = DB::table('student_request_comment')
                            ->where('student_request_id',$data['id'])
                            //->orderBy('created_at', 'desc')
                            ->whereNull('deleted_at')
                            ->get();
            
                $responsedata[] = [
                    'id'         => $data['id'],
                    'request_by' => $created_name,
                    'message'    => $data['message'],
                    'photo'      => asset('assets/studentrequest_images/' . $data['photo']),
                    'date'       => date('Y-m-d',strtotime($data['date'])),
                    'created_at' => $formattedDate,
                    'comments'   => new CommentResource($comments)
                ];
            }        
        }
        return $responsedata;
    }
}
