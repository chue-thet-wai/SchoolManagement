<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ProgressResource extends JsonResource
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
                    'activity_id'     => $data['activity_id'],
                    'activity_name'   => $data['activity_name'],
                    'today_total'     => $data['today_total'],
                    'weekly_total'    => $data['weekly_total'],
                    'monthly_total'   => $data['monthly_total']
                ];
            }            
        }
        return $responsedata;
    }
}
