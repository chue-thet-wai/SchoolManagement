<?php

namespace App\Http\Resources\API;

use App\Models\StudentGuardian;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ChatListResource extends JsonResource
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

                $responsedata[] = [
                    'id'              => $data['id'],
                    'teacher_id'      => $data['teacher_id'],
                    'teacher_name'    => $data['teacher_name'],
                    'class_name'      => $data['class_name']
                ];
            }            
        }

        return $responsedata;
    }
}
