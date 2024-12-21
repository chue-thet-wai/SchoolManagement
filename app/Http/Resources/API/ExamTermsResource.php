<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ExamTermsResource extends JsonResource
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
                    'name'            => $data['name']
                ];
            }            
        }

        return $responsedata;
    }
}
