<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CurriculumResource extends JsonResource
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
                    'subject'         => $data['subject_name'],
                    'start_time'      => $data['start_time'], 
                    'end_time'        => $data['end_time']
                ];
            }            
        }

        return $responsedata;
    }
}
