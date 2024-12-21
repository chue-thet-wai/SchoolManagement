<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ListNotificationResource extends JsonResource
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
                    'title'           => $data['title'],
                    'body'            => $data['body'], 
                    'created_at'      => date('Y-m-d',strtotime($data['created_at'])),
                    'updated_at'      => date('Y-m-d',strtotime($data['updated_at'])),
                ];
            }            
        }

        return $responsedata;
    }
}
