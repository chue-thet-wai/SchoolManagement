<?php

namespace App\Http\Resources\API;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MessageResource extends JsonResource
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
                $carbonDate = Carbon::parse($data['created_at']);
                $day = $carbonDate->format('d');
                $monthAbbreviation = $carbonDate->format('M');
                $responsedata[] = [
                    'id'              => $data['id'],
                    'title'           => $data['title'],
                    'description'     => $data['description'], 
                    'remark'          => $data['remark'],
                    'date'            => $day.' '.$monthAbbreviation,
                ];
            }            
        }

        return $responsedata;
    }
}
