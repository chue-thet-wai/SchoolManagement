<?php

namespace App\Http\Resources\API;

use App\Models\HomeworkStatus;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class HomeworkResource extends JsonResource
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
                $studenthwremark = '';
                $hwstatus = HomeworkStatus::where('homework_id',$data['id'])->first();
                if ($hwstatus) {
                    $status = $hwstatus->status; 
                    $studenthwremark = $hwstatus->remark;
                } else {
                    $status = '1';//Not Yet
                    $studenthwremark = '';
                }
                $homework_file_url = '';
                $filename = '';
                $file_extension = '';
                if ($data['homework_file'] != null || $data['homework_file'] != '') {
                    $homework_file_url = asset('assets/homework_files/'.$data['homework_file']);
                    // Get the filename
                    $filename = pathinfo($homework_file_url, PATHINFO_FILENAME);

                    // Get the file extension
                    $file_extension = pathinfo($homework_file_url, PATHINFO_EXTENSION);
                }
                $created_name = '';
            
                $created_name = User::where('user_id', $data['created_by'])->value('name');
                
                $responsedata[] = [
                    'id'              => $data['id'],
                    'title'          => $data['title'],
                    'description'     => $data['description'], 
                    'subject'         => $data['subject_name'],
                    'homework_file'   => $homework_file_url,
                    'filename'        => $filename,
                    'file_extension'  => $file_extension,
                    'remark'          => $studenthwremark,
                    'status'          => $status,
                    'due_date'        => date('Y-m-d',strtotime($data['due_date'])),
                    'created_by'      => $created_name,
                    'created_at'      => date('Y-m-d',strtotime($data['created_at'])),
                    'updated_at'      => date('Y-m-d',strtotime($data['updated_at'])),
                ];
            }            
        }

        return $responsedata;
    }
}
